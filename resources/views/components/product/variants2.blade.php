{{-- resources/views/admin/products/_variants.blade.php --}}

<x-form.switch name="has_variants" label="Thêm biến thể" />

<div id="variant-form"
     x-data="VariantForm({ attributesMaster: @js($attributeJson) })"
     x-init="init()">

    {{-- Danh sách block attribute --}}
    <div id="variant-attributes">
        <template x-for="(attr, aIndex) in attributes" :key="attr.uid">
            <x-form.variant-attribute :index="0" />
        </template>
    </div>

    {{-- Nút thêm thuộc tính --}}
    <button type="button" class="btn btn-primary mt-2"
            @click="addAttribute()"
            x-show="attributes.length < 3">
        + Thêm thuộc tính
    </button>

    {{-- Bảng tổ hợp biến thể realtime --}}
    <x-form.variant-table id="variant-combinations" />

    {{-- Hidden inputs để submit JSON --}}
    <input type="hidden" name="variants_attributes" id="input-variants-attributes">
    <input type="hidden" name="variants_combinations" id="input-variants-combinations">
</div>
