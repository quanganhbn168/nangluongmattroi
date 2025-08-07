document.addEventListener('alpine:init', () => {
    Alpine.data('variantForm', (initialData) => ({
        attributes: initialData.oldAttributes.map(attr => ({
            uid: Math.random().toString(36).substr(2, 9),
            id: attr.id || '',
            values: attr.values || []
        })),
        attributesMaster: initialData.attributesMaster,
        combinations: initialData.oldVariants.map(v => ({
            key: v.attributes.join('-'),
            attributes: v.attributes,
            values: v.attributes,
            price: v.price || 0,
            sku: v.sku || '',
            qty: v.qty || 0
        })),
        editVariant: {
            index: null,
            price: 0,
            sku: '',
            qty: 0
        },
        bulkEdit: {
            price: '',
            sku: '',
            qty: ''
        },

        init() {
            this.$nextTick(() => {
                this.attributes.forEach((_, index) => {
                    this.initSelect2(index);
                });
                this.updateAvailableAttributes();
            });
        },

        get combinationsJson() {
            return JSON.stringify(this.combinations);
        },

        availableAttributes(index) {
            const usedIds = this.attributes
                .filter((_, i) => i !== index)
                .map(attr => attr.id)
                .filter(id => id);
            return this.attributesMaster.filter(master => 
                !usedIds.includes(master.id)
            );
        },

        getAttrName(id) {
            const attr = this.attributesMaster.find(a => a.id === id);
            return attr ? attr.name : '';
        },

        addAttribute() {
            if (this.attributes.length < 3) {
                this.attributes.push({
                    uid: Math.random().toString(36).substr(2, 9),
                    id: '',
                    values: []
                });
                this.$nextTick(() => {
                    const newIndex = this.attributes.length - 1;
                    this.initSelect2(newIndex); // Initialize Select2 for new block
                });
            }
        },

        removeAttribute(index) {
            this.attributes.splice(index, 1);
            this.updateAvailableAttributes();
        },

        updateAvailableAttributes() {
            this.attributes.forEach((_, index) => {
                const $select = $(this.$refs[`valueSelect_${index}`]);
                if ($select.data('select2')) {
                    $select.select2('destroy');
                }
                this.$nextTick(() => {
                    this.initSelect2(index);
                });
            });
        },

        initSelect2(index) {
            const attr = this.attributes[index];
            const $select = $(this.$refs[`valueSelect_${index}`]);
            
            if (!$select.length) {
                console.error(`Select element with ref 'valueSelect_${index}' not found`);
                return;
            }

            $select.select2({
                tags: true,
                placeholder: "Chọn hoặc nhập giá trị",
                allowClear: true,
                data: attr.values.map(value => ({
                    id: value,
                    text: value,
                    selected: true
                }))
            });

            $select.on('select2:select', (e) => {
                if (e.params.data.isNew) {
                    this.saveNewValue(attr.id, e.params.data.id);
                }
                this.updateAttributeValues(index, $select);
            });

            $select.on('select2:unselect', () => {
                this.updateAttributeValues(index, $select);
            });

            // Ensure existing values are selected
            if (attr.values.length > 0) {
                $select.val(attr.values).trigger('change');
            }
        },

        updateValuesSelect(index) {
            const attr = this.attributes[index];
            const $select = $(this.$refs[`valueSelect_${index}`]);
            
            if (!$select.length) {
                console.error(`Select element with ref 'valueSelect_${index}' not found`);
                return;
            }

            if ($select.data('select2')) {
                $select.select2('destroy');
            }

            // Fetch values for the selected attribute from attributesMaster
            const selectedAttr = this.attributesMaster.find(a => a.id === attr.id);
            const availableValues = selectedAttr?.values || [];

            $select.select2({
                tags: true,
                placeholder: "Chọn hoặc nhập giá trị",
                allowClear: true,
                data: availableValues.map(value => ({
                    id: value,
                    text: value,
                    selected: attr.values.includes(value)
                }))
            });

            $select.on('select2:select', (e) => {
                if (e.params.data.isNew) {
                    this.saveNewValue(attr.id, e.params.data.id);
                }
                this.updateAttributeValues(index, $select);
            });

            $select.on('select2:unselect', () => {
                this.updateAttributeValues(index, $select);
            });

            // Set current values
            if (attr.values.length > 0) {
                $select.val(attr.values).trigger('change');
            }
        },

        updateAttributeValues(index, select) {
            this.attributes[index].values = $(select).val() || [];
            // Trigger change to update combinations if needed
            this.$nextTick(() => {
                if (this.combinations.length > 0) {
                    this.generateCombinations();
                }
            });
        },

        saveNewValue(attributeId, value) {
            if (!attributeId) return;
            
            $.ajax({
                url: '/api/attribute-values',
                method: 'POST',
                data: {
                    attribute_id: attributeId,
                    value: value,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: (response) => {
                    console.log('Value saved:', response);
                    const attr = this.attributesMaster.find(a => a.id === attributeId);
                    if (attr && !attr.values.includes(value)) {
                        attr.values = attr.values ? [...attr.values, value] : [value];
                        this.updateValuesSelect(this.attributes.findIndex(a => a.id === attributeId));
                    }
                },
                error: (error) => {
                    console.error('Error saving value:', error);
                }
            });
        },

        generateCombinations() {
            const combinations = [];
            const values = this.attributes
                .filter(attr => attr.id && attr.values.length > 0)
                .map(attr => attr.values);

            if (values.length === 0) return;

            const generate = (current, depth) => {
                if (depth === values.length) {
                    const key = current.join('-');
                    const existing = this.combinations.find(c => c.key === key);
                    combinations.push({
                        key,
                        attributes: current,
                        values: current,
                        price: existing?.price || 0,
                        sku: existing?.sku || '',
                        qty: existing?.qty || 0
                    });
                    return;
                }

                for (const value of values[depth]) {
                    generate([...current, value], depth + 1);
                }
            };

            generate([], 0);
            this.combinations = combinations;
        },

        openEditModal(index) {
            this.editVariant = {
                index,
                price: this.combinations[index].price || 0,
                sku: this.combinations[index].sku || '',
                qty: this.combinations[index].qty || 0
            };
        },

        saveVariant() {
            const { index, price, sku, qty } = this.editVariant;
            this.combinations[index] = {
                ...this.combinations[index],
                price: price || 0,
                sku: sku || '',
                qty: qty || 0
            };
            $('#editVariantModal').modal('hide');
        },

        applyBulkEdit() {
            this.combinations = this.combinations.map(combo => ({
                ...combo,
                price: this.bulkEdit.price !== '' ? this.bulkEdit.price : combo.price,
                sku: this.bulkEdit.sku !== '' ? this.bulkEdit.sku : combo.sku,
                qty: this.bulkEdit.qty !== '' ? this.bulkEdit.qty : combo.qty
            }));
            this.bulkEdit = { price: '', sku: '', qty: '' };
            $('#bulkEditModal').modal('hide');
        }
    }));
});