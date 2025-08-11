<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        $productId = $this->route('product')->id ?? null;

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:255', Rule::unique('products', 'code')->ignore($productId)],
            'brand_id' => ['nullable', 'integer', 'exists:brands,id'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'status' => ['required', 'boolean'],
            'description' => ['nullable', 'string'],
            'content' => ['nullable', 'string'],
            'image' => $productId ? ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'] : ['required', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
            'gallery' => ['nullable', 'array'],
            'gallery.*' => ['image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
            
            'price_discount' => ['required_if:has_variants,false', 'nullable', 'numeric', 'min:0'],
            'price' => ['required_if:has_variants,false', 'nullable', 'numeric', 'min:0', 'gte:price_discount'],
            'has_variants' => ['nullable', 'boolean'],
        ];

        if ($this->boolean('has_variants')) {
            $rules['variants'] = ['required', 'array', 'min:1'];
            $rules['variants.*.price'] = ['required', 'numeric', 'min:0'];
            $rules['variants.*.stock'] = ['nullable', 'integer', 'min:0'];
            // $rules['variants.*.attributes'] = ['required', 'array'];
            $rules['variants.*.id'] = ['nullable', 'integer', 'exists:product_variants,id'];
            $rules['variants.*._delete'] = ['nullable', 'boolean'];

            foreach ($this->input('variants', []) as $key => $variant) {
                $variantId = $variant['id'] ?? null;
                $rules["variants.{$key}.sku"] = [
                    'nullable',
                    'string',
                    'max:255',
                    Rule::unique('product_variants', 'sku')->ignore($variantId),
                ];
                if (empty($variant['_delete'])) {
                    $rules["variants.{$key}.attributes"] = ['required', 'array'];
                }
            }
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Tên sản phẩm là bắt buộc.',
            'category_id.required' => 'Danh mục sản phẩm là bắt buộc.',
            'image.required' => 'Ảnh đại diện là bắt buộc khi tạo mới.',
            'price.gte' => 'Giá so sánh phải lớn hơn hoặc bằng giá bán.',

            'has_variants.boolean' => 'Trường "có biến thể" không hợp lệ.',
            'variants.required' => 'Vui lòng tạo ít nhất một biến thể.',
            'variants.min' => 'Vui lòng tạo ít nhất một biến thể.',
            
            'variants.*.price.required' => 'Giá của biến thể là bắt buộc.',
            'variants.*.price.numeric' => 'Giá của biến thể phải là một số.',
            'variants.*.price.min' => 'Giá của biến thể phải lớn hơn hoặc bằng 0.',

            'variants.*.stock.integer' => 'Số lượng tồn kho phải là số nguyên.',
            'variants.*.stock.min' => 'Số lượng tồn kho phải lớn hơn hoặc bằng 0.',

            'variants.*.sku.unique' => 'SKU của biến thể đã tồn tại.',
            
            'variants.*.attributes.required' => 'Mỗi biến thể phải có thông tin thuộc tính.',
        ];
    }
}