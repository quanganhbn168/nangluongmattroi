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
                        <th style="width: 80px;">Hành động</th>
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
        function debounce(func, delay) {
            let timeout;
            return function(...args) {
                clearTimeout(timeout);
                timeout = setTimeout(() => func.apply(this, args), delay);
            };
        }

        const codeInput = $('input[name="code"]');
        const codeStatusDiv = $('#code-check-status');
        const productIdToIgnore = {{ $product->id ?? 'null' }};

        const checkCode = debounce(function() {
            const code = codeInput.val();
            
            if (code.length < 3) {
                codeStatusDiv.html('');
                return;
            }

            codeStatusDiv.html('<i class="fas fa-spinner fa-spin"></i><span class="ml-2">Đang kiểm tra...</span>');

            $.ajax({
                url: "{{ route('admin.ajax.products.check_code') }}",
                type: 'GET',
                data: { 
                    code: code,
                    ignore_id: productIdToIgnore 
                },
                success: function(response) {
                    if (response.available) {
                        codeStatusDiv.html('<span class="text-success"><i class="fas fa-check-circle"></i><span class="ml-2">Mã sản phẩm hợp lệ.</span></span>');
                        codeInput.removeClass('is-invalid').addClass('is-valid');
                    } else {
                        codeStatusDiv.html('<span class="text-danger"><i class="fas fa-exclamation-circle"></i><span class="ml-2">Mã sản phẩm đã tồn tại!</span></span>');
                        codeInput.removeClass('is-valid').addClass('is-invalid');
                    }
                },
                error: function() {
                     codeStatusDiv.html('<span class="text-warning"><i class="fas fa-exclamation-triangle"></i><span class="ml-2">Không thể kiểm tra mã.</span></span>');
                }
            });
        }, 500);

        codeInput.on('keyup', checkCode);
    function toggleVariantsSection() {
        if (hasVariantsSwitch.is(':checked')) {
            variantsSection.slideDown();
        } else {
            variantsSection.slideUp();
        }
    }
    hasVariantsSwitch.on('change', toggleVariantsSection);
    toggleVariantsSection();

    const allAttributes = @json($allAttributes ?? $attributes ?? []);
    let initialProductData = @json($product ?? null);
    const variantsTbody = $('#variants-tbody');
    let variantIndexCounter = {{ optional(optional($product ?? null)->variants)->max('id') ?? 0 }} + 1;
    function formatCurrency(number) {
        return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(number);
    }

    function renderVariantRow(variant, index) {
        const variantName = (variant.attribute_values && Array.isArray(variant.attribute_values))
        ? variant.attribute_values.map(v => v.value).join(' / ')
        : 'Biến thể lỗi';
        const price = variant.price || 0;
        const sku = variant.sku || '';
        const stock = variant.stock || 0;
        
        // ... hiddenInputs không đổi ...
        let hiddenInputs = `<input type="hidden" class="variant-name" value="${variantName}">`;
        if(variant.attribute_values && Array.isArray(variant.attribute_values)) {
            variant.attribute_values.forEach(attrValue => {
                hiddenInputs += `<input type="hidden" name="variants[${index}][attributes][${attrValue.attribute_id}]" value="${attrValue.value}">`;
            });
        }
        if (variant.id) { hiddenInputs += `<input type="hidden" name="variants[${index}][id]" value="${variant.id}">`; }

        return `
            <tr class="variant-row" data-variant-id="${index}">
                <td class="text-center"><input type="checkbox" class="variant-checkbox"></td>
                <td class="variant-main-info">${variantName}</td>
                <td class="variant-price"><span>${formatCurrency(price)}</span><input type="hidden" name="variants[${index}][price]" value="${price}"></td>
                <td class="variant-sku">
                    <div class="input-group">
                        <input type="text" name="variants[${index}][sku]" value="${sku}" class="form-control">
                        <div class="input-group-append">
                            <button type="button" class="btn btn-sm btn-default btn-generate-sku" title="Tạo SKU gợi ý">
                                <i class="fas fa-dice-d6"></i>
                            </button>
                        </div>
                    </div>
                    <span style="display:none;">${sku}</span>
                </td>
                <td class="variant-stock"><span>${stock}</span><input type="hidden" name="variants[${index}][stock]" value="${stock}"></td>
                <td class="actions-cell text-center">
                    <button type="button" class="btn btn-sm btn-danger btn-delete-variant" title="Xóa"><i class="fas fa-trash"></i></button>
                </td>
                ${hiddenInputs}
            </tr>
        `;
    }
    // Thêm đoạn code này vào trong $(document).ready(...)
    variantsTbody.on('click', '.btn-generate-sku', function() {
        const row = $(this).closest('.variant-row');
        const productCode = $('input[name="code"]').val().toUpperCase() || 'SP';
        const variantName = row.find('.variant-name').val(); // "Đỏ / S"

        // Tạo SKU gợi ý từ mã sản phẩm và tên biến thể
        const suggestedSku = productCode + '-' + variantName.replace(/ \/ /g, '-').replace(/\s+/g, '');
        
        // Điền vào ô input SKU
        row.find('input[name*="[sku]"]').val(suggestedSku.toUpperCase());
    });
    const addAttributeSelector = $('#add-attribute-selector');
    if (allAttributes && allAttributes.length > 0) {
        allAttributes.forEach(attr => addAttributeSelector.append(new Option(attr.name, attr.id)));
    }

    addAttributeSelector.on('change', function() {
        const attributeId = $(this).val();
        const attribute = allAttributes.find(a => a.id == attributeId);
        if (!attribute) return;
        if ($(`#attribute-group-${attributeId}`).length > 0) { alert('Thuộc tính này đã được thêm.'); return; }

        const groupHtml = `<div class="attribute-value-group" id="attribute-group-${attributeId}" data-attribute-id="${attributeId}"><i class="fas fa-times remove-attribute-group-btn" title="Xóa nhóm thuộc tính"></i><label class="font-weight-bold">${attribute.name}</label><select class="form-control attribute-value-select" multiple="multiple"></select></div>`;
        $('#attribute-values-area').append(groupHtml);

        const newSelect = $(`#attribute-group-${attributeId} .attribute-value-select`);
        const optionsData = attribute.values.map(val => {
            const valueText = typeof val === 'object' ? val.value : val;
            return { id: valueText, text: valueText };
        });
        
        newSelect.select2({
            theme: 'bootstrap4',
            placeholder: `Chọn hoặc thêm giá trị cho ${attribute.name}`,
            tags: true,
            data: optionsData
        });
        
        $(this).val($(this).find('option:first').val());
    });

    $('#attribute-values-area').on('click', '.remove-attribute-group-btn', function() {
        $(this).closest('.attribute-value-group').remove();
    });

    $('#generate-variants-btn').on('click', function() {
        const valueGroups = $('.attribute-value-group');
        if (valueGroups.length === 0) { alert('Vui lòng thêm ít nhất một thuộc tính.'); return; }
        const mainPrice = $('input[name="price_discount"]').val() || 0;
        const productCode = $('input[name="code"]').val().toUpperCase() || 'SKU';

        const valueArrays = [];
        valueGroups.each(function() {
            const attributeId = $(this).data('attribute-id');
            const selectedValues = $(this).find('.attribute-value-select').val();
            if (selectedValues && selectedValues.length > 0) {
                valueArrays.push(selectedValues.map(v => ({ attributeId: attributeId, value: v })));
            }
        });

        if (valueArrays.length !== valueGroups.length) { alert('Tất cả các nhóm thuộc tính phải có ít nhất một giá trị được chọn.'); return; }

        const getCombinations = arrays => arrays.reduce((a, b) => a.flatMap(x => b.map(y => [...x, y])), [[]]);
        const combinations = getCombinations(valueArrays);

        variantsTbody.empty();
        combinations.forEach(combo => {
            const skuSuggestion = combo.map(c => c.value.substring(0, 3).toUpperCase()).join('-');
            
            const variantData = { 
                attributeValues: combo,
                price: mainPrice,
                sku_placeholder: `${productCode}-${skuSuggestion}`
            };
            const rowHtml = renderVariantRow(variantData, `new_${variantIndexCounter++}`);
            variantsTbody.append(rowHtml);
        });
    });
    variantsTbody.on('click', '.variant-row', function(e) {
        if ($(e.target).is('input:checkbox') || $(e.target).closest('.actions-cell').length > 0) return;
        openEditModal($(this));
    });

    function openEditModal(row) {
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
    }

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
            const variantId = row.data('variant-id');
            if (initialProductData && !String(variantId).startsWith('new_')) {
                row.addClass('row-deleted');
                row.append(`<input type="hidden" name="variants[${variantId}][_delete]" value="1">`);
                row.find('.variant-checkbox').prop('checked', false).prop('disabled', true);
            } else {
                row.remove();
            }
            updateBulkActionsVisibility();
        }
    });

    const bulkContainer = $('#bulk-actions-container');
    const bulkCount = $('#bulk-count');

    function updateBulkActionsVisibility() {
        const selectedCount = variantsTbody.find('.variant-checkbox:not(:disabled):checked').length;
        selectedCount > 0 ? bulkContainer.slideDown('fast') : bulkContainer.slideUp('fast');
        bulkCount.text(selectedCount);
    }
    variantsTbody.on('change', '.variant-checkbox', updateBulkActionsVisibility);
    $('#select-all-variants').on('change', function() {
        variantsTbody.find('.variant-checkbox:not(:disabled)').prop('checked', $(this).prop('checked'));
        updateBulkActionsVisibility();
    });

    $('#apply-bulk-action-btn').on('click', function() {
        const bulkPrice = $('#bulk-price').val();
        const bulkSku = $('#bulk-sku').val();
        const bulkStock = $('#bulk-stock').val();
        if (bulkPrice === '' && bulkSku === '' && bulkStock === '') {
            alert('Vui lòng nhập ít nhất một giá trị để áp dụng hàng loạt.');
            return;
        }

        variantsTbody.find('.variant-checkbox:checked').closest('tr').each(function() {
            const row = $(this);
            if (bulkPrice !== '') {
                const newPrice = parseFloat(bulkPrice);
                if (!isNaN(newPrice)) {
                    row.find('input[name*="[price]"]').val(newPrice);
                    row.find('.variant-price span').text(formatCurrency(newPrice));
                }
            }
            if (bulkSku !== '') {
                row.find('input[name*="[sku]"]').val(bulkSku);
                row.find('.variant-sku span').text(bulkSku);
            }
            if (bulkStock !== '') {
                const newStock = parseInt(bulkStock);
                if (!isNaN(newStock)) {
                    row.find('input[name*="[stock]"]').val(newStock);
                    row.find('.variant-stock span').text(newStock);
                }
            }
            row.addClass('row-updated');
        });

        $('#bulk-price, #bulk-sku, #bulk-stock').val('');
        setTimeout(() => $('.row-updated').removeClass('row-updated'), 1500);
    });

    // ===================================================================================
    // === PHẦN 6: KHỞI TẠO BAN ĐẦU =======================================================
    // ===================================================================================

    function initializeEditMode() {
        if (!initialProductData) return;
        $('#variant-generator-section').hide();
        initialProductData.variants.forEach(variant => {
            const rowHtml = renderVariantRow(variant, variant.id);
            variantsTbody.append(rowHtml);
        });
    }

    initializeEditMode();
});
</script>
@endpush