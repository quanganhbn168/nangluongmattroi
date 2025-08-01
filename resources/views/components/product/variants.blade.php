@props([
    'attributesUrl' => route('admin.ajax.attributes'),
    'attributeValuesUrl' => route('admin.ajax.attribute-values'),
    'product' => null,
])
<div
    x-data="variantFormV3()"
    x-init="init('{{ $attributesUrl }}', '{{ $attributeValuesUrl }}')"
>
    <div class="form-group">
        <div class="custom-control custom-switch">
            <input type="checkbox" class="custom-control-input" id="selectVariant" x-model="hasVariants">
            <label class="custom-control-label" for="selectVariant">Thêm biến thể</label>
        </div>
        <small class="form-text text-muted">Tối đa 3 nhóm biến thể: màu sắc, kích thước, chất liệu...</small>
    </div>

    <template x-if="hasVariants">
        <div class="mt-3">
            <!-- Thuộc tính -->
            <template x-for="(attr, aIndex) in attributes" :key="attr.uid">
                <div class="product-properties border rounded p-3 mb-3">
                    <!-- Đang chỉnh sửa -->
                    <template x-if="attr.isEditing">
                        <div>
                            <div class="form-group">
                                <label>Tên biến thể <span class="text-danger">*</span></label>
                                <select class="form-control select2-variants"
                                        x-model="attr.id"
                                        x-ref="select2_${aIndex}"
                                        data-placeholder="Nhập để tìm hoặc thêm mới"
                                        data-url="{{ $attributesUrl }}"
                                        @change="loadAttributeName(aIndex)">
                                    <option value="">-- chọn --</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Tùy chọn <span class="text-danger">*</span></label>
                                <template x-for="(val, vIndex) in attr.values" :key="val.uid">
                                    <div class="form-group d-flex align-items-center">
                                        <select class="form-control select2-value"
                                                x-model="val.value"
                                                x-ref="value_select2_${aIndex}_${vIndex}"
                                                data-placeholder="Chọn hoặc nhập giá trị"
                                                data-url="{{ $attributeValuesUrl }}"
                                                @change="addValueIfLast(aIndex, vIndex)">
                                            <option value="">-- chọn --</option>
                                        </select>
                                        <template x-if="attr.values.length > 1 || val.value.trim() !== ''">
                                            <button type="button" class="btn btn-outline-dark ml-2"
                                                    @click="removeValue(aIndex, vIndex)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </template>
                                    </div>
                                </template>
                            </div>

                            <button type="button" class="btn btn-success" @click="finishAttribute(aIndex)">
                                Xong
                            </button>
                        </div>
                    </template>

                    <!-- Đã xong -->
                    <template x-if="!attr.isEditing">
                        <div>
                            <div class="form-group mb-2">
                                <label class="text-secondary font-weight-bold" x-text="attr.name"></label>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex flex-wrap">
                                    <template x-for="val in attr.values" :key="val.uid">
                                        <span class="badge badge-pill badge-secondary mr-2 mb-1" x-text="val.value"></span>
                                    </template>
                                </div>
                                <button type="button" class="btn btn-secondary btn-sm" @click="editAttribute(aIndex)">
                                    Chỉnh sửa
                                </button>
                            </div>
                        </div>
                    </template>
                </div>
            </template>

            <!-- Thêm thuộc tính -->
            <template x-if="attributes.length < 3">
                <button type="button" class="btn btn-outline-primary btn-sm" @click="addAttribute()">
                    <i class="fas fa-plus"></i> Thêm nhóm biến thể khác
                </button>
            </template>

            <!-- Danh sách tổ hợp biến thể -->
            <template x-if="combinations.length > 0">
                <div id="table-product-variant" class="mt-4">
                    <h5><span class="text-danger">*</span>Danh sách biến thể</h5>
                    <button type="button" class="btn btn-secondary mb-2">
                        Chỉnh sửa hàng loạt
                    </button>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead>
                                <tr>
                                    <template x-for="(attr, i) in attributes" :key="attr.uid">
                                        <th x-text="attr.name"></th>
                                    </template>
                                    <th>Giá bán</th>
                                    <th>SKU</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="(combo, index) in combinations" :key="combo.id">
                                    <tr>
                                        <template x-for="val in combo.values" :key="val.attr_id">
                                            <td x-text="val.value"></td>
                                        </template>
                                        <td>
                                            <input type="number" class="form-control form-control-sm" x-model="combo.price">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control form-control-sm" x-model="combo.sku">
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-outline-danger"
                                                    @click="removeCombination(index)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </template>

            <!-- Input ẩn để gửi dữ liệu biến thể -->
            <input type="hidden" name="variants_attributes" x-model="JSON.stringify(attributes)">
            <input type="hidden" name="variants_combinations" x-model="JSON.stringify(combinations)">
        </div>
    </template>
</div>
@push('js')
<script>
    function variantFormV3() {
    return {
        hasVariants: false,
        attributeList: [],
        attributes: @json(old('variants_attributes', [])),
        combinations: @json(old('variants_combinations', [])),
        uidCounter: {{ count(old('variants_attributes', [])) + count(old('variants_combinations', [])) + 1 }},
        defaultIndex: 0,

        init(attributesUrl, attributeValuesUrl) {
            // Tải danh sách thuộc tính
            fetch(attributesUrl)
                .then(res => {
                    if (!res.ok) throw new Error('Không thể tải danh sách thuộc tính');
                    return res.json();
                })
                .then(data => {
                    this.attributeList = data;
                    this.$nextTick(() => this.initSelect2());
                })
                .catch(err => {
                    console.error(err);
                    alert('Không thể tải danh sách thuộc tính. Vui lòng thử lại.');
                });

            // Theo dõi hasVariants để thêm thuộc tính mới khi bật
            this.$watch('hasVariants', val => {
                if (val && this.attributes.length === 0) {
                    this.addAttribute();
                } else if (!val) {
                    this.attributes = [];
                    this.combinations = [];
                }
            });

            // Theo dõi attributes để khởi tạo lại Select2 khi cần
            this.$watch('attributes', () => {
                this.$nextTick(() => {
                    this.initSelect2();
                    this.initValueSelect2(attributeValuesUrl);
                });
            });
        },

        initSelect2() {
            this.attributes.forEach((attr, index) => {
                if (attr.isEditing) {
                    const select = this.$refs[`select2_${index}`];
                    if (select && !$(select).hasClass('select2-hidden-accessible')) {
                        $(select).select2({
                            theme: 'bootstrap4',
                            placeholder: $(select).data('placeholder'),
                            allowClear: true,
                            tags: true,
                            ajax: {
                                url: $(select).data('url'),
                                dataType: 'json',
                                delay: 250,
                                data: params => ({ q: params.term }),
                                processResults: data => ({
                                    results: data.map(item => ({ id: item.id, text: item.name }))
                                }),
                                cache: true
                            },
                            createTag: params => {
                                const term = $.trim(params.term);
                                if (term === '') return null;
                                return {
                                    id: `new_${this.uidCounter++}`,
                                    text: term,
                                    newTag: true
                                };
                            },
                            templateResult: data => {
                                if (data.loading) return data.text;
                                if (data.newTag) {
                                    return $(`<span><i class="fas fa-plus text-white mr-1"></i> Thêm "<strong>${data.text}</strong>"</span>`);
                                }
                                return data.text;
                            },
                            templateSelection: data => data.text,
                            language: {
                                inputTooShort: () => 'Nhập để tìm...',
                                noResults: () => 'Không có dữ liệu',
                                searching: () => 'Đang tìm...',
                            }
                        });

                        $(select).on('select2:select', e => {
                            const data = e.params.data;
                            this.attributes[index].id = data.id;
                            this.attributes[index].name = data.text;

                            if (data.newTag) {
                                $.ajax({
                                    type: 'POST',
                                    url: $(select).data('url'),
                                    data: {
                                        _token: '{{ csrf_token() }}',
                                        name: data.text
                                    },
                                    success: res => {
                                        if (res.id) {
                                            this.attributes[index].id = res.id;
                                            this.attributes[index].name = res.text;
                                            const newOption = new Option(res.text, res.id, true, true);
                                            $(select).append(newOption).trigger('change');
                                            this.loadValueSuggestions(index, $(select).data('url').replace('attributes', 'attribute-values'));
                                        }
                                    },
                                    error: () => {
                                        alert('Không thể tạo mới thuộc tính. Vui lòng thử lại.');
                                        $(select).find(`option[value="${data.id}"]`).remove();
                                        this.attributes[index].id = '';
                                        this.attributes[index].name = '';
                                    }
                                });
                            } else {
                                this.loadValueSuggestions(index, $(select).data('url').replace('attributes', 'attribute-values'));
                            }
                        });

                        $(select).on('select2:clear', () => {
                            this.attributes[index].id = '';
                            this.attributes[index].name = '';
                            this.attributes[index].valueSuggestions = [];
                            this.attributes[index].values = [{ value: '', uid: this.uidCounter++ }];
                            this.$nextTick(() => this.initValueSelect2($(select).data('url').replace('attributes', 'attribute-values')));
                        });
                    }
                }
            });
        },

        initValueSelect2(attributeValuesUrl) {
            this.attributes.forEach((attr, aIndex) => {
                if (attr.isEditing) {
                    attr.values.forEach((val, vIndex) => {
                        const select = this.$refs[`value_select2_${aIndex}_${vIndex}`];
                        if (select && !$(select).hasClass('select2-hidden-accessible')) {
                            $(select).select2({
                                theme: 'bootstrap4',
                                placeholder: $(select).data('placeholder'),
                                allowClear: true,
                                tags: true,
                                ajax: {
                                    url: () => {
                                        if (!attr.id || attr.id.startsWith('new_')) return null;
                                        return attributeValuesUrl + '?attribute_id=' + attr.id;
                                    },
                                    dataType: 'json',
                                    delay: 250,
                                    data: params => ({ q: params.term }),
                                    processResults: data => ({
                                        results: data.map(item => ({ id: item.text, text: item.text }))
                                    }),
                                    cache: true
                                },
                                createTag: params => {
                                    const term = $.trim(params.term);
                                    if (term === '') return null;
                                    return {
                                        id: term,
                                        text: term,
                                        newTag: true
                                    };
                                },
                                templateResult: data => {
                                    if (data.loading) return data.text;
                                    if (data.newTag) {
                                        return $(`<span><i class="fas fa-plus text-white mr-1"></i> Thêm "<strong>${data.text}</strong>"</span>`);
                                    }
                                    return data.text;
                                },
                                templateSelection: data => data.text,
                                language: {
                                    inputTooShort: () => 'Nhập để tìm...',
                                    noResults: () => 'Không có dữ liệu',
                                    searching: () => 'Đang tìm...',
                                }
                            });

                            $(select).on('select2:select', e => {
                                const data = e.params.data;
                                this.attributes[aIndex].values[vIndex].value = data.text;
                                if (data.newTag && attr.id && !attr.id.startsWith('new_')) {
                                    $.ajax({
                                        type: 'POST',
                                        url: attributeValuesUrl,
                                        data: {
                                            _token: '{{ csrf_token() }}',
                                            attribute_id: attr.id,
                                            value: data.text
                                        },
                                        success: res => {
                                            if (res.id) {
                                                this.attributes[aIndex].values[vIndex].value = res.text;
                                                const newOption = new Option(res.text, res.text, true, true);
                                                $(select).append(newOption).trigger('change');
                                            }
                                        },
                                        error: () => {
                                            alert('Không thể tạo mới giá trị. Vui lòng thử lại.');
                                            this.attributes[aIndex].values[vIndex].value = '';
                                            $(select).val('').trigger('change');
                                        }
                                    });
                                }
                                this.addValueIfLast(aIndex, vIndex);
                            });

                            $(select).on('select2:clear', () => {
                                this.attributes[aIndex].values[vIndex].value = '';
                                this.addValueIfLast(aIndex, vIndex);
                            });
                        }
                    });
                }
            });
        },

        loadValueSuggestions(aIndex, attributeValuesUrl) {
            const attr = this.attributes[aIndex];
            if (!attr.id || attr.id.startsWith('new_')) {
                attr.valueSuggestions = [];
                this.$nextTick(() => this.initValueSelect2(attributeValuesUrl));
                return;
            }

            fetch(attributeValuesUrl + '?attribute_id=' + attr.id)
                .then(res => {
                    if (!res.ok) throw new Error('Không thể tải giá trị gợi ý');
                    return res.json();
                })
                .then(data => {
                    this.attributes[aIndex].valueSuggestions = data;
                    this.$nextTick(() => this.initValueSelect2(attributeValuesUrl));
                })
                .catch(err => {
                    console.error(err);
                    this.attributes[aIndex].valueSuggestions = [];
                    this.$nextTick(() => this.initValueSelect2(attributeValuesUrl));
                });
        },

        addValueIfLast(attrIndex, valIndex) {
            const attr = this.attributes[attrIndex];
            if (valIndex === attr.values.length - 1 && attr.values[valIndex].value.trim() !== '') {
                attr.values.push({ value: '', uid: this.uidCounter++ });
                this.generateCombinations();
                this.$nextTick(() => this.initValueSelect2('{{ $attributeValuesUrl }}'));
            }
        },

        addAttribute() {
            if (this.attributes.length >= 3) return;

            this.attributes.push({
                uid: this.uidCounter++,
                id: '',
                name: '',
                isEditing: true,
                valueSuggestions: [],
                values: [{ value: '', uid: this.uidCounter++ }]
            });
            this.$nextTick(() => this.initSelect2());
        },

        removeValue(attrIndex, valIndex) {
            this.attributes[attrIndex].values.splice(valIndex, 1);
            this.generateCombinations();
            this.$nextTick(() => this.initValueSelect2('{{ $attributeValuesUrl }}'));
        },

        availableAttributes(index) {
            const selectedIds = this.attributes
                .map((a, i) => i !== index ? String(a.id) : null)
                .filter(id => id !== null && id !== '');
            return this.attributeList.filter(attr => !selectedIds.includes(String(attr.id)));
        },

        finishAttribute(index) {
            const attr = this.attributes[index];

            if (!attr.id || !attr.name) {
                alert('Bạn cần chọn hoặc nhập thuộc tính trước khi bấm Xong');
                return;
            }

            attr.values = attr.values.filter(v => v.value.trim() !== '');
            if (attr.values.length === 0) {
                alert('Bạn cần nhập ít nhất 1 giá trị');
                return;
            }

            attr.isEditing = false;
            this.generateCombinations();
            this.$nextTick(() => {
                const select = this.$refs[`select2_${index}`];
                if (select && $(select).hasClass('select2-hidden-accessible')) {
                    $(select).select2('destroy');
                }
            });
        },

        editAttribute(index) {
            this.attributes[index].isEditing = true;
            this.$nextTick(() => {
                this.initSelect2();
                this.initValueSelect2('{{ $attributeValuesUrl }}');
            });
        },

        loadAttributeName(index) {
            const attr = this.attributes[index];
            const selected = this.attributeList.find(a => a.id == attr.id || a.name.toLowerCase() === attr.name.toLowerCase());
            if (selected) {
                attr.id = selected.id;
                attr.name = selected.name;
                this.loadValueSuggestions(index, '{{ $attributeValuesUrl }}');
            }
        },

        removeCombination(index) {
            this.combinations.splice(index, 1);
        },

        generateCombinations() {
            const valueGroups = this.attributes.map(attr =>
                attr.values.filter(v => v.value.trim() !== '').map(val => ({
                    attr_id: attr.id,
                    attr_name: attr.name,
                    value: val.value
                }))
            );

            if (valueGroups.some(g => g.length === 0)) {
                this.combinations = [];
                return;
            }

            const cartesian = (a, b) => {
                const result = [];
                a.forEach(aItem => {
                    b.forEach(bItem => {
                        result.push([...aItem, bItem]);
                    });
                });
                return result;
            };

            let combos = valueGroups.reduce((acc, group) => {
                if (acc.length === 0) return group.map(item => [item]);
                return cartesian(acc, group);
            }, []);

            this.combinations = combos.map((combo, index) => ({
                id: this.uidCounter++,
                values: combo,
                sku: '',
                price: '',
                original_price: '',
                is_default: index === this.defaultIndex
            }));
        }
    }
}
</script>
@endpush