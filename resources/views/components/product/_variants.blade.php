<div x-data="VariantForm({ attributesMaster: @js($attribute) })" x-init="init()">

    {{-- Switch --}}
    <div class="form-group">
        <div class="custom-control custom-switch">
            <input type="checkbox" class="custom-control-input"
                   id="selectVariant"
                   x-model="hasVariants"
                   @change="toggleVariants()">
            <label class="custom-control-label" for="selectVariant">Thêm biến thể</label>
        </div>
    </div>

    <div id="variant-form">

        {{-- Danh sách block attribute --}}
        <template x-for="(attr, aIndex) in attributes" :key="attr.uid">
            <div class="product-properties border rounded p-3 mb-3"
                 x-bind:class="{'bg-light': !attr.isEditing}">
                 
                {{-- Chế độ edit --}}
                <template x-if="attr.isEditing">
                    <div>
                        <div class="form-group">
                            <label>Tên biến thể <span class="text-danger">*</span></label>
                            <select class="form-control select2-attribute"
                                    x-bind:name="'attr_'+aIndex"
                                    x-init="$nextTick(() => initSelect2Attribute($el, aIndex))">
                                <option value="">-- chọn --</option>
                                <template x-for="am in getAvailableAttributes(aIndex)" :key="am.id">
                                    <option x-bind:value="am.id"
                                            x-bind:selected="attr.id === am.id"
                                            x-text="am.name"></option>
                                </template>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Tuỳ chọn <span class="text-danger">*</span></label>
                            <div class="value-block" x-bind:id="'value-block-'+aIndex">
                                <template x-for="(val, vIndex) in attr.values" :key="val.uid">
                                    <div class="form-group d-flex align-items-center value-row mb-2">
                                        <select class="form-control select2-value mr-2"
                                                x-bind:name="'attr_'+aIndex+'_value_'+vIndex"
                                                x-init="$nextTick(() => initSelect2Value($el, aIndex, vIndex))">
                                            <option value="">-- chọn --</option>
                                            <template x-for="v in getAvailableValues(attr.id, aIndex)" :key="v">
                                                <option x-bind:value="v"
                                                        x-bind:selected="val.value === v"
                                                        x-text="v"></option>
                                            </template>
                                        </select>
                                        <button type="button" class="btn btn-outline-dark ml-2"
                                                @click="removeValue(aIndex, vIndex)"
                                                x-show="attr.values.length > 1">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <button type="button" class="btn btn-success"
                                @click="finishAttribute(aIndex)">
                            Xong
                        </button>
                    </div>
                </template>

                {{-- Chế độ summary --}}
                <template x-if="!attr.isEditing">
                    <div>
                        <div class="form-group mb-2">
                            <label class="text-secondary font-weight-bold"
                                   x-text="attr.name"></label>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex flex-wrap">
                                <template x-for="val in attr.values" :key="val.uid">
                                    <span class="badge badge-pill badge-secondary mr-2 mb-1"
                                          x-text="val.value"></span>
                                </template>
                            </div>
                            <div>
                                <button type="button" class="btn btn-secondary btn-sm"
                                        @click="editAttribute(aIndex)">Chỉnh sửa</button>
                                <button type="button" class="btn btn-danger btn-sm ml-1"
                                        @click="removeAttribute(aIndex)">Xoá</button>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </template>

        {{-- Nút thêm thuộc tính --}}
        <button type="button" class="btn btn-primary mt-2"
                @click="addAttribute()"
                x-show="hasVariants && attributes.length < 3">
            + Thêm thuộc tính
        </button>

        {{-- Bảng combinations realtime --}}
        <x-form.variant-table id="variant-combinations" />

        {{-- Hidden inputs --}}
        <input type="hidden" name="variants_attributes" id="input-variants-attributes">
        <input type="hidden" name="variants_combinations" id="input-variants-combinations">
    </div>
</div>

@push('js')
    <script src="{{ asset('js/variant-form.js') }}"></script>
@endpush
