@props(['index'])

<div class="product-properties border rounded p-3 mb-3"
     x-data
     x-bind:data-index="{{ $index }}"
     x-bind:class="{'bg-light': !attributes[{{ $index }}].isEditing}">

    {{-- Chế độ edit --}}
    <template x-if="attributes[{{ $index }}].isEditing">
        <div>
            <div class="form-group">
                <label>Tên biến thể <span class="text-danger">*</span></label>
                <select class="form-control select2-attribute"
                        x-bind:name="'attr_' + {{ $index }}"
                        x-init="$nextTick(() => initSelect2Attribute($el, {{ $index }}))">
                    <option value="">-- chọn --</option>
                </select>
            </div>

            <div class="form-group">
                <label>Tuỳ chọn <span class="text-danger">*</span></label>
                <div class="value-block" x-bind:id="'value-block-'+{{ $index }}">
                    <template x-for="(val, vIndex) in attributes[{{ $index }}].values" :key="val.uid">
                        <div class="form-group d-flex align-items-center value-row mb-2"
                             x-bind:data-vidx="vIndex">
                            <select class="form-control select2-value mr-2"
                                    x-bind:name="'attr_' + {{ $index }} + '_value_' + vIndex"
                                    x-init="$nextTick(() => initSelect2Value($el, {{ $index }}, vIndex))">
                                <option value="">-- chọn --</option>
                            </select>

                            <button type="button" class="btn btn-outline-dark ml-2"
                                    @click="removeValue({{ $index }}, vIndex)"
                                    x-show="attributes[{{ $index }}].values.length > 1 && vIndex < attributes[{{ $index }}].values.length - 1">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </template>
                </div>
            </div>

            <button type="button" class="btn btn-success"
                    @click="finishAttribute({{ $index }})">
                Xong
            </button>
        </div>
    </template>

    {{-- Chế độ summary --}}
    <template x-if="!attributes[{{ $index }}].isEditing">
        <div>
            <div class="form-group mb-2">
                <label class="text-secondary font-weight-bold"
                       x-text="attributes[{{ $index }}].name"></label>
            </div>
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex flex-wrap">
                    <template x-for="val in attributes[{{ $index }}].values" :key="val.uid">
                        <span class="badge badge-pill badge-secondary mr-2 mb-1"
                              x-text="val.value"></span>
                    </template>
                </div>
                <button type="button" class="btn btn-secondary btn-sm"
                        @click="editAttribute({{ $index }})">
                    Chỉnh sửa
                </button>
            </div>
        </div>
    </template>
</div>