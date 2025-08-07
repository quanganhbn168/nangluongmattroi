<?php

namespace App\Services;

use App\Handlers\ImageGalleryHandler;
use App\Models\Product;
use App\Models\Category;
use App\Models\AttributeValue;
use App\Models\ProductVariant;
use App\Traits\UploadImageTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Services\AttributeService;

class ProductService
{
    use UploadImageTrait;

    public function __construct(
        protected ImageGalleryHandler $imageGallery,
        protected AttributeService $attributeService
    ) {}

    public function getAll(): LengthAwarePaginator
    {
        return Product::with('category')->latest()->paginate(20);
    }

    public function getCategoryOptions(): array
    {
        return Category::select('id', 'name', 'parent_id')->get()->toArray();
    }

    public function getAttribute()
    {
        return $this->attributeService->getAttributesWithValues(20);
    }

    /**
     * Tạo một sản phẩm mới.
     *
     * @param Request $request
     * @return Product
     * @throws \Throwable
     */
    public function store(Request $request): Product
    {
        // Lấy dữ liệu đã được validate từ request
        $data = $request->validated();

        return DB::transaction(function () use ($request, $data) {
            if (empty($data['slug'])) {
                $data['slug'] = Str::slug($data['name']);
            }
            
            // Xử lý upload ảnh từ file trong request
            if ($request->hasFile('image')) {
                $data['image'] = $this->uploadImage($request->file('image'), 'uploads/products', 800, 800, true);
            }
            if ($request->hasFile('banner')) {
                $data['banner'] = $this->uploadImage($request->file('banner'), 'uploads/products', 1920, 600, true);
            }

            $variantData = $data['variants'] ?? [];
            unset($data['variants']);
            
            $product = Product::create($data);
            
            // SỬA Ở ĐÂY: Truyền thẳng $request cho ImageGalleryHandler
            $this->imageGallery->sync($product, $request, 'gallery', 'uploads/products/gallery');

            if (isset($data['has_variants']) && $data['has_variants'] && !empty($variantData)) {
                $this->syncVariants($product, $variantData);
            } else {
                $product->variants()->create([
                    'price' => $data['price_discount'] ?? 0,
                    'original_price' => $data['price'] ?? 0,
                    'stock' => $data['stock'] ?? 0,
                    'is_default' => true,
                ]);
            }

            return $product;
        });
    }

    /**
     * Cập nhật thông tin một sản phẩm đã có.
     *
     * @param Request $request
     * @param Product $product
     * @return Product
     * @throws \Throwable
     */
    public function update(Request $request, Product $product): Product
    {
        // Lấy dữ liệu đã được validate từ request
        $data = $request->validated();

        return DB::transaction(function () use ($request, $data, $product) {
            $data['slug'] ??= Str::slug($data['name']);

            if ($request->hasFile('image')) {
                $this->deleteImage($product->image);
                $data['image'] = $this->uploadImage($request->file('image'), 'uploads/products', 800, 800, true);
            }

            if ($request->hasFile('banner')) {
                $this->deleteImage($product->banner);
                $data['banner'] = $this->uploadImage($request->file('banner'), 'uploads/products', 1920, true);
            }
            
            $variantData = $data['variants'] ?? [];
            unset($data['variants']);

            $product->update($data);

            // SỬA Ở ĐÂY: Truyền thẳng $request cho ImageGalleryHandler
            $this->imageGallery->sync($product, $request, 'gallery', 'uploads/products/gallery');

            if (isset($data['has_variants']) && $data['has_variants']) {
                if (!empty($variantData)) {
                    $this->syncVariants($product, $variantData);
                }
            } else {
                $product->variants()->delete();
                $product->variants()->create([
                    'price' => $data['price_discount'] ?? 0,
                    'original_price' => $data['price'] ?? 0,
                    'stock' => $data['stock'] ?? 0,
                    'is_default' => true,
                ]);
            }

            return $product;
        });
    }
    
    public function delete(Product $product): void
    {
        DB::transaction(function () use ($product) {
            $this->deleteImage($product->image);
            $this->deleteImage($product->banner);
            $this->imageGallery->deleteAll($product);
            $product->delete();
        });
    }

/**
     * Hàm lõi để đồng bộ (tạo, cập nhật, xóa) các biến thể.
     *
     * @param Product $product
     * @param array $variantsData
     */
    public function syncVariants(Product $product, array $variantsData)
    {
        foreach ($variantsData as $variantKey => $variantData) {
            if (isset($variantData['_delete']) && $variantData['_delete'] == '1') {
                if (isset($variantData['id'])) {
                    $product->variants()->where('id', $variantData['id'])->delete();
                }
                continue;
            }

            // === PHẦN LOGIC TỰ ĐỘNG TẠO SKU ===
            // Nếu người dùng không nhập SKU, chúng ta tự tạo
            if (empty($variantData['sku'])) {
                $valueNames = [];
                // Lấy ra mảng tên các giá trị thuộc tính, ví dụ: ['Đỏ', 'S']
                if (!empty($variantData['attributes'])) {
                    foreach ($variantData['attributes'] as $attributeId => $valueName) {
                        $valueNames[] = $valueName;
                    }
                }
                // Gọi hàm trợ giúp để tạo SKU duy nhất
                $variantData['sku'] = $this->generateUniqueSku($product->code, $valueNames);
            }
            // ===================================

            $variant = $product->variants()->updateOrCreate(
                ['id' => $variantData['id'] ?? null],
                [
                    'sku' => $variantData['sku'], // SKU bây giờ luôn có giá trị
                    'price' => $variantData['price'] ?? 0,
                    'stock' => $variantData['stock'] ?? 0,
                ]
            );

            $valueIds = [];
            if (!empty($variantData['attributes'])) {
                foreach ($variantData['attributes'] as $attributeId => $valueName) {
                    $attributeValue = AttributeValue::firstOrCreate(
                        ['attribute_id' => $attributeId, 'value' => trim($valueName)]
                    );
                    $valueIds[] = $attributeValue->id;
                }
            }

            if (!empty($valueIds)) {
                $variant->attributeValues()->sync($valueIds);
            }
        }
    }

    /**
     * Hàm trợ giúp để tạo một SKU duy nhất.
     *
     * @param string $productCode Mã sản phẩm cha
     * @param array $valueNames Mảng các tên giá trị, ví dụ: ['Đỏ', 'S']
     * @return string SKU duy nhất
     */
    private function generateUniqueSku(string $productCode, array $valueNames): string
    {
        // 1. Tạo SKU cơ bản
        $suffix = collect($valueNames)->map(fn($value) => Str::slug($value))->implode('-');
        $baseSku = Str::upper($productCode . ($suffix ? '-' . $suffix : ''));

        // 2. Kiểm tra xem SKU đã tồn tại chưa
        if (!ProductVariant::where('sku', $baseSku)->exists()) {
            return $baseSku;
        }

        // 3. Nếu đã tồn tại, thêm số vào cuối và kiểm tra lại cho đến khi duy nhất
        $counter = 2;
        while (true) {
            $newSku = "{$baseSku}-{$counter}";
            if (!ProductVariant::where('sku', $newSku)->exists()) {
                return $newSku;
            }
            $counter++;
        }
    }
}