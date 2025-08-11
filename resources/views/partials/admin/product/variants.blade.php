@push('css')
<style>
        .select2-container--bootstrap4 .select2-selection--multiple .select2-selection__choice {
            background-color: #17a2b8; border-color: #1593a5; color: #fff;
        }
        .select2-container--bootstrap4 .select2-selection--multiple .select2-selection__choice__remove { color: rgba(255,255,255,.7); }
        .attribute-value-group { border: 1px solid #ddd; padding: 15px; border-radius: 5px; margin-bottom: 15px; position: relative; }
        .remove-attribute-group-btn { position: absolute; top: 5px; right: 10px; cursor: pointer; color: #dc3545; }
        .variant-row { cursor: pointer; }
        .row-deleted { text-decoration: line-through; opacity: 0.6; background-color: #f8d7da !important; }
        .row-updated { animation: highlight 1.5s ease-out; }
        @keyframes highlight {
            from { background-color: #d1ecf1; }
            to { background-color: transparent; }
        }
    </style>
@endpush
<div class="card card-info">
    <div class="card-header">
        <h3 class="card-title">3. Các biến thể sản phẩm</h3>
    </div>
    <div class="card-body">
        <div id="variant-generator-section">
            <div class="form-group">
                <label>a. Chọn thuộc tính</label>
                <select class="form-control" id="add-attribute-selector">
                    <option selected disabled>-- Thêm thuộc tính --</option>
                </select>
            </div>
            <hr>
            <label>b. Chọn giá trị cho mỗi thuộc tính</label>
            <div id="attribute-values-area"></div>
            <hr>
            <label>c. Tạo biến thể</label><br>
            <button type="button" class="btn btn-secondary mt-2 mb-3" id="generate-variants-btn">Tạo các biến thể</button>
        </div>
        <div class="card bg-light p-2 mb-3" id="bulk-actions-container" style="display: none;">
            <div class="row align-items-center">
                <div class="col-12 mb-2">
                    <span id="bulk-count">0</span> biến thể đã được chọn.
                </div>
                <div class="col-md-3"><input type="number" id="bulk-price" class="form-control form-control-sm" placeholder="Đặt giá hàng loạt"></div>
                <div class="col-md-3"><input type="text" id="bulk-sku" class="form-control form-control-sm" placeholder="Đặt SKU hàng loạt"></div>
                <div class="col-md-3"><input type="number" id="bulk-stock" class="form-control form-control-sm" placeholder="Đặt tồn kho hàng loạt"></div>
                <div class="col-md-3"><button type="button" class="btn btn-primary btn-sm btn-block" id="apply-bulk-action-btn">Áp dụng cho mục đã chọn</button></div>
            </div>
        </div>
        <div id="variants-container">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th style="width: 30px;"><input type="checkbox" id="select-all-variants"></th>
                        <th>Biến thể</th>
                        <th style="width: 150px;">Giá</th>
                        <th style="width: 150px;">SKU</th>
                        <th style="width: 120px;">Tồn kho</th>
                        <th style="width: 150px;">Hành động</th>
                    </tr>
                </thead>
                <tbody id="variants-tbody"></tbody>
            </table>
        </div>
    </div>
</div>
<div class="modal fade" id="variant-edit-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Chỉnh sửa biến thể: <strong id="modal-variant-name"></strong></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="modal-editing-variant-id">
                <div class="form-group"><label for="modal-price">Giá</label><input type="number" id="modal-price" class="form-control"></div>
                <div class="form-group"><label for="modal-sku">SKU</label><input type="text" id="modal-sku" class="form-control"></div>
                <div class="form-group"><label for="modal-stock">Tồn kho</label><input type="number" id="modal-stock" class="form-control"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                <button type="button" class="btn btn-primary" id="save-modal-changes-btn">Lưu thay đổi</button>
            </div>
        </div>
    </div>
</div>
@push('js')
<script>
$(document).ready(function() {
    const hasVariantsSwitch = $('input[name="has_variants"]');
    const variantsSection = $('#variants-section');
    const addAttributeSelector = $('#add-attribute-selector');
    const attributeValuesArea = $('#attribute-values-area');
    const generateVariantsBtn = $('#generate-variants-btn');
    const variantsTbody = $('#variants-tbody');
    const codeInput = $('input[name="code"]');
    const codeStatusDiv = $('#code-check-status');
    const bulkContainer = $('#bulk-actions-container');
    const bulkCount = $('#bulk-count');
    const applyBulkBtn = $('#apply-bulk-action-btn');
    const selectAllCheckbox = $('#select-all-variants');
    const allAttributes = @json($allAttributes ?? $attributes ?? []);
    const initialProductData = @json($product ?? null);
    const productIdToIgnore = initialProductData ? initialProductData.id : null;
    let variantIndexCounter = {{ optional(optional($product ?? null)->variants)->max('id') ?? 0 }} + 1;
    function formatCurrency(number) {
        return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(number);
    }
    function debounce(func, delay) {
        let timeout;
        return function(...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), delay);
        };
    }
    /**
     * Render HTML cho một hàng biến thể trong bảng
     * @param {object} variant - Dữ liệu của biến thể
     * @param {string|number} index - Index hoặc ID của biến thể
     * @returns {string} - Chuỗi HTML
     */
    function renderVariantRow(variant, index) {
        const variantName = (variant.attribute_values && Array.isArray(variant.attribute_values))
            ? variant.attribute_values.map(v => v.value).join(' / ')
            : (variant.name || 'N/A');
        const price = variant.price || 0;
        const sku = variant.sku || '';
        const stock = variant.quantity || 0;
        let hiddenInputs = `<input type="hidden" class="variant-name" value="${variantName}">`;
        hiddenInputs += `<input type="hidden" name="variants[${index}][price]" value="${price}">`;
        hiddenInputs += `<input type="hidden" name="variants[${index}][sku]" value="${sku}">`;
        hiddenInputs += `<input type="hidden" name="variants[${index}][stock]" value="${stock}">`;
        if (variant.id) { 
            hiddenInputs += `<input type="hidden" name="variants[${index}][id]" value="${variant.id}">`;
        }
        if (variant.attribute_values && Array.isArray(variant.attribute_values)) {
            variant.attribute_values.forEach(attrValue => {
                hiddenInputs += `<input type="hidden" name="variants[${index}][attributes][${attrValue.attribute_id}]" value="${attrValue.value}">`;
            });
        }
        return `
            <tr class="variant-row" data-variant-id="${index}">
                <td class="text-center"><input type="checkbox" class="variant-checkbox"></td>
                <td class="variant-main-info">${variantName}</td>
                <td class="variant-price"><span>${formatCurrency(price)}</span></td>
                <td class="variant-sku"><span>${sku}</span></td>
                <td class="variant-stock"><span>${stock}</span></td>
                <td class="actions-cell text-center">
                    <button type="button" class="btn btn-sm btn-info btn-edit-variant" title="Sửa"><i class="fas fa-pencil-alt"></i></button>
                    <button type="button" class="btn btn-sm btn-danger btn-delete-variant" title="Xóa"><i class="fas fa-trash"></i></button>
                </td>
                ${hiddenInputs}
            </tr>
        `;
    }
    /**
     * Tạo một nhóm thuộc tính (ví dụ: Màu sắc) với các giá trị
     * @param {number} attributeId - ID của thuộc tính
     * @param {array} selectedValues - Các giá trị cần được chọn sẵn
     */
    function createAttributeGroup(attributeId, selectedValues = []) {
        const attribute = allAttributes.find(a => a.id == attributeId);
        if (!attribute || $(`#attribute-group-${attributeId}`).length > 0) return;
        const groupHtml = `
            <div class="attribute-value-group" id="attribute-group-${attributeId}" data-attribute-id="${attributeId}">
                <i class="fas fa-times remove-attribute-group-btn" title="Xóa nhóm thuộc tính"></i>
                <label class="font-weight-bold">${attribute.name}</label>
                <select class="form-control attribute-value-select" multiple="multiple"></select>
            </div>`;
        attributeValuesArea.append(groupHtml);
        const newSelect = $(`#attribute-group-${attributeId} .attribute-value-select`);
        const optionsData = attribute.values.map(val => ({ id: val.value, text: val.value }));
        newSelect.select2({
            theme: 'bootstrap4',
            placeholder: `Chọn hoặc thêm giá trị cho ${attribute.name}`,
            tags: true,
            data: optionsData
        });
        if (selectedValues.length > 0) {
            newSelect.val(selectedValues).trigger('change');
        }
    }
    hasVariantsSwitch.on('change', () => variantsSection.slideToggle(hasVariantsSwitch.is(':checked'))).trigger('change');
    addAttributeSelector.on('change', function() {
        const attributeId = $(this).val();
        if (attributeId) {
            createAttributeGroup(attributeId);
        }
        $(this).val($(this).find('option:first').val()); 
    });
    attributeValuesArea.on('click', '.remove-attribute-group-btn', function() {
        $(this).closest('.attribute-value-group').remove();
    });
    $('#generate-variants-btn').on('click', function() {
        const existingVariants = new Map();
        variantsTbody.find('.variant-row').each(function() {
            const row = $(this);
            const name = row.find('.variant-name').val();
            if (name) {
                existingVariants.set(name, {
                    id: row.find('input[name*="[id]"]').val(),
                    price: row.find('input[name*="[price]"]').val(),
                    sku: row.find('input[name*="[sku]"]').val(),
                    stock: row.find('input[name*="[stock]"]').val(),
                    attributes: Array.from(row.find('input[name*="[attributes]"]')).map(input => {
                        const match = $(input).attr('name').match(/\[(\d+)\]/);
                        return { attribute_id: match[1], value: $(input).val() };
                    })
                });
            }
        });
        const valueGroups = $('.attribute-value-group');
        if (valueGroups.length === 0) {
            alert('Vui lòng thêm ít nhất một thuộc tính.');
            return;
        }
        let hasEmptyValues = false;
        const valueArrays = [];
        valueGroups.each(function() {
            const attributeId = $(this).data('attribute-id');
            const selectedValues = $(this).find('.attribute-value-select').val();
            if (!selectedValues || selectedValues.length === 0) {
                hasEmptyValues = true;
            } else {
                valueArrays.push(selectedValues.map(v => ({ attribute_id: attributeId, value: v })));
            }
        });
        if (hasEmptyValues) {
            alert('Tất cả các nhóm thuộc tính phải có ít nhất một giá trị được chọn.');
            return;
        }
        const getCombinations = arrays => arrays.reduce((a, b) => a.flatMap(x => b.map(y => [...x, y])), [[]]);
        const combinations = getCombinations(valueArrays);
        variantsTbody.empty();
        const productCode = $('input[name="code"]').val().toUpperCase() || 'SP';
        combinations.forEach(combo => {
            if (combo.length === 0) return;
            const variantName = combo.map(c => c.value).join(' / ');
            const existingVariantData = existingVariants.get(variantName);
            let variantData;
            let variantIndex;
            if (existingVariantData) {
                variantData = {
                    id: existingVariantData.id,
                    attribute_values: existingVariantData.attributes,
                    price: existingVariantData.price,
                    sku: existingVariantData.sku,
                    stock: existingVariantData.stock
                };
                variantIndex = existingVariantData.id;
            } else {
                const skuSuggestion = productCode + '-' + combo.map(c => c.value.replace(/\s+/g, '').substring(0, 3)).join('-');
                variantData = {
                    id: null,
                    attribute_values: combo,
                    price: $('input[name="price_discount"]').val() || 0,
                    sku: skuSuggestion.toUpperCase(),
                    stock: 0
                };
                variantIndex = `new_${variantIndexCounter++}`;
            }
            const rowHtml = renderVariantRow(variantData, variantIndex);
            variantsTbody.append(rowHtml);
        });
        selectAllCheckbox.prop('checked', false);
    });
    variantsTbody.on('click', '.btn-edit-variant', function(e) {
        e.stopPropagation();
        const row = $(this).closest('.variant-row');
        const variantId = row.data('variant-id');
        const name = row.find('.variant-name').val();
        const price = row.find('input[name*="[price]"]').val();
        const sku = row.find('input[name*="[sku]"]').val();
        const stock = row.find('input[name*="[stock]"]').val();
        $('#modal-variant-name').text(name);
        $('#modal-price').val(price);
        $('#modal-sku').val(sku);
        $('#modal-stock').val(stock);
        $('#modal-editing-variant-id').val(variantId);
        $('#variant-edit-modal').modal('show');
    });
    $('#save-modal-changes-btn').on('click', function() {
        const variantId = $('#modal-editing-variant-id').val();
        const row = $(`.variant-row[data-variant-id="${variantId}"]`);
        const newPrice = $('#modal-price').val();
        const newSku = $('#modal-sku').val();
        const newStock = $('#modal-stock').val();
        row.find('input[name*="[price]"]').val(newPrice);
        row.find('input[name*="[sku]"]').val(newSku);
        row.find('input[name*="[stock]"]').val(newStock);
        row.find('.variant-price span').text(formatCurrency(newPrice));
        row.find('.variant-sku span').text(newSku);
        row.find('.variant-stock span').text(newStock);
        row.addClass('row-updated');
        setTimeout(() => row.removeClass('row-updated'), 1500);
        $('#variant-edit-modal').modal('hide');
    });
    variantsTbody.on('click', '.btn-delete-variant', function(e) {
        e.stopPropagation();
        const row = $(this).closest('.variant-row');
        if (confirm('Bạn có chắc muốn xóa biến thể này?')) {
            const variantId = String(row.data('variant-id'));
            if (!variantId.startsWith('new_')) {
                row.addClass('row-deleted');
                if (row.find('input[name*="[_delete]"]').length === 0) {
                    row.append(`<input type="hidden" name="variants[${variantId}][_delete]" value="1">`);
                }
                row.find('.variant-checkbox').prop('checked', false).prop('disabled', true);
            } else {
                row.remove();
            }
            updateBulkActionsVisibility();
        }
    });
    function updateBulkActionsVisibility() {
        const selectedCount = variantsTbody.find('.variant-checkbox:not(:disabled):checked').length;
        selectedCount > 0 ? bulkContainer.slideDown('fast') : bulkContainer.slideUp('fast');
        bulkCount.text(selectedCount);
    }
    variantsTbody.on('change', '.variant-checkbox', updateBulkActionsVisibility);
    selectAllCheckbox.on('change', function() {
        variantsTbody.find('.variant-checkbox:not(:disabled)').prop('checked', $(this).prop('checked'));
        updateBulkActionsVisibility();
    });
    applyBulkBtn.on('click', function() {
        const bulkPrice = $('#bulk-price').val();
        const bulkSku = $('#bulk-sku').val();
        const bulkStock = $('#bulk-stock').val();
        if (bulkPrice === '' && bulkSku === '' && bulkStock === '') return;
        variantsTbody.find('.variant-checkbox:checked').closest('tr').each(function() {
            const row = $(this);
            if (bulkPrice !== '') {
                const newPrice = parseFloat(bulkPrice);
                row.find('input[name*="[price]"]').val(newPrice);
                row.find('.variant-price span').text(formatCurrency(newPrice));
            }
            if (bulkSku !== '') {
                row.find('input[name*="[sku]"]').val(bulkSku);
                row.find('.variant-sku span').text(bulkSku);
            }
            if (bulkStock !== '') {
                const newStock = parseInt(bulkStock);
                row.find('input[name*="[stock]"]').val(newStock);
                row.find('.variant-stock span').text(newStock);
            }
            row.addClass('row-updated');
        });
        $('#bulk-price, #bulk-sku, #bulk-stock').val('');
        setTimeout(() => $('.row-updated').removeClass('row-updated'), 1500);
    });
    if (allAttributes && allAttributes.length > 0) {
        allAttributes.forEach(attr => addAttributeSelector.append(new Option(attr.name, attr.id)));
    }
    codeInput.on('keyup', debounce(function() {
        const code = $(this).val();
        if (code.length < 3) { codeStatusDiv.html(''); return; }
        codeStatusDiv.html('<i class="fas fa-spinner fa-spin"></i> Đang kiểm tra...');
        $.ajax({
            url: "{{ route('admin.ajax.products.check_code') }}",
            data: { code: code, ignore_id: productIdToIgnore },
            success: function(response) {
                if(response.available) {
                    codeStatusDiv.html('<span class="text-success"><i class="fas fa-check-circle"></i> Mã hợp lệ.</span>');
                } else {
                    codeStatusDiv.html('<span class="text-danger"><i class="fas fa-exclamation-circle"></i> Mã đã tồn tại!</span>');
                }
            }
        });
    }, 500));
    /**
     * Hàm chính để khởi tạo giao diện ở chế độ sửa
     */
    function initializeEditMode() {
        if (!initialProductData || !initialProductData.variants || initialProductData.variants.length === 0) {
            return;
        }
        const usedAttributes = new Map();
        initialProductData.variants.forEach(variant => {
            if (variant.attribute_values && Array.isArray(variant.attribute_values)) {
                variant.attribute_values.forEach(attrValue => {
                    if (!usedAttributes.has(attrValue.attribute_id)) {
                        usedAttributes.set(attrValue.attribute_id, new Set());
                    }
                    usedAttributes.get(attrValue.attribute_id).add(attrValue.value);
                });
            }
        });
        if (usedAttributes.size === 0) return;
        usedAttributes.forEach((valuesSet, attributeId) => {
            const selectedValues = Array.from(valuesSet);
            createAttributeGroup(attributeId, selectedValues);
        });
        variantsTbody.empty();
        initialProductData.variants.forEach(variant => {
            const rowHtml = renderVariantRow(variant, variant.id);
            variantsTbody.append(rowHtml);
        });
    }
    initializeEditMode();
});
</script>
@endpush