<?php

namespace App\Services;

use App\Handlers\ImageGalleryHandler;
use App\Models\Product;
use App\Models\Category;
use App\Traits\UploadImageTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Services\AttributeService;
class ProductService
{
    use UploadImageTrait;

    /**
     * @param ImageGalleryHandler $imageGallery
     */
    public function __construct(
        protected ImageGalleryHandler $imageGallery,
        protected AttributeService $attributeService
    )
    {}

    /**
     * Lấy danh sách sản phẩm với phân trang.
     * Eager load 'category' để tránh vấn đề N+1 query.
     *
     * @return LengthAwarePaginator
     */
    public function getAll(): LengthAwarePaginator
    {
        return Product::with('category')->latest()->paginate(20);
    }

    /**
     * Lấy danh sách các danh mục để hiển thị trong form (ví dụ: thẻ <select>).
     *
     * @return array
     */
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
    public function create(Request $request): Product
    {
        $data = $request->validate($this->getValidationRules());

        return DB::transaction(function () use ($request, $data) {
            $data['slug'] ??= Str::slug($data['name']);

            if ($request->hasFile('image')) {
                $data['image'] = $this->uploadImage($request->file('image'), 'uploads/products', 800, true);
            }

            if ($request->hasFile('banner')) {
                $data['banner'] = $this->uploadImage($request->file('banner'), 'uploads/products', 1920, true);
            }

            $product = Product::create($data);

            // Đồng bộ thư viện ảnh
            $this->imageGallery->sync($product, $request, 'gallery', 'uploads/products/gallery');
            if ($request->has('variants')) {
                foreach ($request->input('variants') as $variantData) {
                    $attributeIds = $variantData['attribute_value_ids'] ?? [];

                    unset($variantData['attribute_value_ids']);

                    $variant = $product->variants()->create($variantData);

                    if ($attributeIds) {
                        $variant->attributeValues()->sync($attributeIds);
                    }
                }
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
        $data = $request->validate($this->getValidationRules($product));

        return DB::transaction(function () use ($request, $product, $data) {
            $data['slug'] ??= Str::slug($data['name']);

            if ($request->hasFile('image')) {
                $this->deleteImage($product->image); // Xóa ảnh cũ
                $data['image'] = $this->uploadImage($request->file('image'), 'uploads/products', 800, true);
            }

            if ($request->hasFile('banner')) {
                $this->deleteImage($product->banner); // Xóa banner cũ
                $data['banner'] = $this->uploadImage($request->file('banner'), 'uploads/products', 1920, true);
            }

            $product->update($data);

            // Đồng bộ thư viện ảnh
            $this->imageGallery->sync($product, $request, 'gallery', 'uploads/products/gallery');

            return $product;
        });
    }

    /**
     * Xóa một sản phẩm.
     * Bao gồm cả việc xóa các file ảnh và thư viện ảnh liên quan.
     *
     * @param Product $product
     * @return void
     * @throws \Throwable
     */
    public function delete(Product $product): void
    {
        DB::transaction(function () use ($product) {
            $this->deleteImage($product->image);
            $this->deleteImage($product->banner);
            
            // Xóa tất cả ảnh trong thư viện của sản phẩm
            $this->imageGallery->deleteAll($product);

            $product->delete();
        });
    }

    /**
     * Lấy các quy tắc xác thực cho việc tạo và cập nhật sản phẩm.
     *
     * @param Product|null $product
     * @return array
     */
    private function getValidationRules(?Product $product = null): array
    {
        // Nếu là cập nhật, slug phải là duy nhất ngoại trừ chính sản phẩm đó.
        $slugRule = 'nullable|string|max:255|unique:products,slug';
        if ($product) {
            $slugRule .= ',' . $product->id;
        }

        return [
            'category_id'    => 'required|exists:categories,id',
            'name'           => 'required|string|max:255',
            'slug'           => $slugRule,
            'price'          => 'nullable|numeric|min:0',
            'price_discount' => 'nullable|numeric|min:0',
            'description'    => 'nullable|string',
            'code'           => 'required|string|max:255',
            'content'        => 'nullable|string',
            'specifications' => 'nullable|string',
            'image'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'banner'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'status'         => 'boolean',
            'is_home'        => 'boolean',
            'sm'             => 'nullable|string',
            'll'             => 'nullable|string',
            'gallery'        => 'nullable|array',
            'gallery.*'      => 'image|mimes:jpg,jpeg,png,webp|max:4096',
        ];
    }

    public function syncVariants(Product $product, array $variants)
    {
        foreach ($variants as $variantData) {
            $attributeValueIds = $variantData['attribute_value_ids'] ?? [];
            unset($variantData['attribute_value_ids']);

            $variant = $product->variants()->create([
                'sku' => $variantData['sku'] ?? null,
                'price' => $variantData['price'] ?? 0,
                'original_price' => $variantData['original_price'] ?? 0,
                'quantity' => $variantData['quantity'] ?? 0,
                'is_default' => $variantData['is_default'] ?? false,
            ]);

            if (!empty($attributeValueIds)) {
                $variant->attributeValues()->sync($attributeValueIds);
            }
        }
    }


}