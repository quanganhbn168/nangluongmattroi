function VariantForm({ attributesMaster = [] } = {}) {
    return {
        attributesMaster,
        attributes: [],
        combinations: [],
        hasVariants: false,
        uidCounter: 1,

        init() {
            this.updateHidden();
        },

        toggleVariants() {
            if (this.hasVariants && this.attributes.length === 0) {
                this.addAttribute();
            }
            if (!this.hasVariants) {
                this.attributes = [];
                this.combinations = [];
                this.updateHidden();
            }
        },

        // === ATTRIBUTE BLOCK ===
        addAttribute() {
            if (this.attributes.length >= 3) return;
            this.attributes.push({
                uid: this.uidCounter++,
                id: '',
                name: '',
                isEditing: true,
                values: [{ uid: this.uidCounter++, value: '' }]
            });
            this.updateHidden();
        },

        finishAttribute(index) {
            const attr = this.attributes[index];
            attr.values = attr.values.filter(v => v.value.trim() !== '');
            if (attr.values.length === 0 || attr.values[attr.values.length - 1].value.trim() !== '') {
                attr.values.push({ uid: this.uidCounter++, value: '' });
            }
            if (!attr.id || attr.values.length === 1 && attr.values[0].value.trim() === '') {
                alert('Chọn thuộc tính và ít nhất 1 giá trị');
                return;
            }
            attr.isEditing = false;
            this.generateCombinations();
            this.updateHidden();
        },

        editAttribute(index) {
            const attr = this.attributes[index];
            attr.isEditing = true;
            if (attr.values.length === 0 || attr.values[attr.values.length - 1].value.trim() !== '') {
                attr.values.push({ uid: this.uidCounter++, value: '' });
            }
            this.updateHidden();

            this.$nextTick(() => {
                document.querySelectorAll(`#value-block-${index} .select2-value`)
                    .forEach((el, idx) => initSelect2Value(el, index, idx));
            });
        },

        removeValue(attrIndex, valueIndex) {
            const attr = this.attributes[attrIndex];
            if (attr.values.length > 1 && valueIndex < attr.values.length - 1) {
                attr.values.splice(valueIndex, 1);

                this.$nextTick(() => {
                    document.querySelectorAll(`#value-block-${attrIndex} .select2-value`)
                        .forEach((el, idx) => initSelect2Value(el, attrIndex, idx));
                    const lastValue = attr.values[attr.values.length - 1].value.trim();
                    const buttons = document.querySelectorAll(`#value-block-${attrIndex} .value-row .btn-outline-dark`);
                    buttons.forEach((button, idx) => {
                        if (idx === attr.values.length - 1 && lastValue === '') {
                            button.classList.add('d-none');
                        } else {
                            button.classList.remove('d-none');
                        }
                    });
                    console.log('After remove:', attr.values, 'Last value:', lastValue, 'Buttons:', buttons.length);
                });

                this.generateCombinations();
                this.updateHidden();
            }
        },

        updateValue(attrIndex, valueIndex, newValue) {
            const attr = this.attributes[attrIndex];
            // Kiểm tra và lấy oldValue an toàn, xử lý giá trị không hợp lệ
            let oldValue = '';
            if (valueIndex >= 0 && valueIndex < attr.values.length) {
                oldValue = attr.values[valueIndex].value || '';
            } else {
                console.log('Invalid valueIndex in updateValue:', { attrIndex, valueIndex, values: attr.values });
                valueIndex = attr.values.length > 0 ? attr.values.length - 1 : 0;
                if (attr.values.length === 0) {
                    attr.values.push({ uid: this.uidCounter++, value: '' });
                }
            }
            attr.values[valueIndex] = attr.values[valueIndex] || { uid: this.uidCounter++, value: '' };
            attr.values[valueIndex].value = newValue || '';

            console.log('Update value:', { attrIndex, valueIndex, oldValue, newValue, values: attr.values });

            // Kiểm tra trạng thái ô trống trước và sau khi cập nhật
            const hasEmptyBefore = oldValue.trim() === '' || attr.values.some((v, idx) => idx !== valueIndex && v.value.trim() === '');
            const hasEmptyAfter = attr.values.some((v, idx) => idx !== valueIndex && v.value.trim() === '');

            // Thêm ô trống mới nếu giá trị hợp lệ và không còn ô trống
            if (newValue && newValue.trim() !== '' && !hasEmptyAfter) {
                attr.values.push({ uid: this.uidCounter++, value: '' });
            }

            this.$nextTick(() => {
                document.querySelectorAll(`#value-block-${attrIndex} .select2-value`)
                    .forEach((el, idx) => initSelect2Value(el, attrIndex, idx));
                const lastValue = attr.values[attr.values.length - 1].value.trim();
                const buttons = document.querySelectorAll(`#value-block-${attrIndex} .value-row .btn-outline-dark`);
                buttons.forEach((button, idx) => {
                    if (idx === attr.values.length - 1 && lastValue === '') {
                        button.classList.add('d-none');
                    } else {
                        button.classList.remove('d-none');
                    }
                });
                console.log('After update:', attr.values, 'Last value:', lastValue, 'Buttons:', buttons.length);
            });

            this.generateCombinations();
            this.updateHidden();
        },

        // === COMBINATIONS ===
        generateCombinations() {
            const sets = this.attributes
                .filter(attr => attr.id && attr.values.some(v => v.value.trim() !== ''))
                .map(attr =>
                    attr.values
                        .filter(v => v.value.trim() !== '')
                        .map(v => ({
                            attr_id: attr.id,
                            attr_name: attr.name,
                            value: v.value
                        }))
                );

            if (sets.length === 0) {
                this.combinations = [];
                this.updateHidden();
                return;
            }

            const cartesian = sets.reduce((acc, group) => {
                if (acc.length === 0) return group.map(item => [item]);
                return acc.flatMap(a => group.map(b => [...a, b]));
            }, []);

            this.combinations = cartesian.map((combo, i) => ({
                id: i + 1,
                values: combo,
                sku: this.combinations[i] ? this.combinations[i].sku : '',
                price: this.combinations[i] ? this.combinations[i].price : ''
            }));

            this.updateHidden();
        },

        removeCombination(index) {
            this.combinations.splice(index, 1);
            this.updateHidden();
        },

        updateHidden() {
            document.getElementById('input-variants-attributes').value = JSON.stringify(this.attributes);
            document.getElementById('input-variants-combinations').value = JSON.stringify(this.combinations);
        }
    }
}

function initSelect2Attribute(el, attrIndex) {
    const cmp = Alpine.$data(el.closest('[x-data]'));
    const selectEl = $(el);

    const renderOptions = () => {
        selectEl.empty().append('<option value="">-- chọn --</option>');
        cmp.attributesMaster.forEach(attr => {
            const isSelf = String(cmp.attributes[attrIndex].id) === String(attr.id);
            const isUsed = cmp.attributes.some((a, i) =>
                i !== attrIndex && String(a.id) === String(attr.id)
            );
            if (!isUsed || isSelf) {
                selectEl.append(new Option(attr.name, attr.id, isSelf, isSelf));
            }
        });
    };

    renderOptions();

    selectEl.select2({ theme: 'bootstrap4', tags: true, allowClear: true })
        .on('select2:select', function (e) {
            const data = e.params.data;
            cmp.attributes[attrIndex].id = data.id;
            cmp.attributes[attrIndex].name = data.text;
            cmp.attributes[attrIndex].values = [{ uid: cmp.uidCounter++, value: '' }];

            document.querySelectorAll('.select2-attribute').forEach((s, idx) => {
                if (idx !== attrIndex) {
                    const sel = $(s);
                    sel.off('select2:select');
                    initSelect2Attribute(s, idx);
                }
            });

            cmp.$nextTick(() => {
                document.querySelectorAll(`#value-block-${attrIndex} .select2-value`)
                    .forEach((el, idx) => initSelect2Value(el, attrIndex, idx));
            });

            cmp.generateCombinations();
            cmp.updateHidden();
        });
}

function initSelect2Value(el, attrIndex, valueIndex) {
    const cmp = Alpine.$data(el.closest('[x-data]'));
    const selectEl = $(el);

    const attrId = cmp.attributes[attrIndex].id;
    const masterValues = (cmp.attributesMaster.find(a => String(a.id) === String(attrId))?.values || []);
    const currentValues = cmp.attributes[attrIndex].values;

    // Đảm bảo valueIndex hợp lệ
    if (valueIndex < 0 || valueIndex >= currentValues.length) {
        console.log('Invalid valueIndex:', { attrIndex, valueIndex, currentValues });
        if (currentValues.length === 0) {
            currentValues.push({ uid: cmp.uidCounter++, value: '' });
        }
        valueIndex = currentValues.length - 1;
    }

    // Lọc các giá trị chưa được sử dụng (trừ giá trị tại valueIndex)
    const usedValues = currentValues
        .filter((v, idx) => idx !== valueIndex && v.value.trim() !== '')
        .map(v => v.value.trim().toLowerCase());

    const availableValues = masterValues.filter(v => 
        !usedValues.includes(v.value.trim().toLowerCase())
    );

    // Thêm giá trị hiện tại (nếu có) vào danh sách để hiển thị trong dropdown
    const currentValue = currentValues[valueIndex]?.value.trim();
    if (currentValue && !availableValues.some(v => v.value.trim().toLowerCase() === currentValue.toLowerCase())) {
        availableValues.push({ value: currentValue });
    }

    // Render options
    selectEl.empty().append('<option value="">-- chọn --</option>');
    availableValues.forEach(v => {
        const isSelected = currentValue && v.value.trim().toLowerCase() === currentValue.toLowerCase();
        selectEl.append(new Option(v.value, v.value, isSelected, isSelected));
    });

    selectEl.select2({ theme: 'bootstrap4', tags: true, allowClear: true })
        .on('select2:select', function (e) {
            const data = e.params.data;
            console.log('Select2 select:', { attrIndex, valueIndex, data });
            cmp.updateValue(attrIndex, valueIndex, data.text);
        });
}