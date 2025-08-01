{{-- resources/views/components/form/variant-table.blade.php --}}
@props([
    'id' => 'variant-combinations', // id container
])

<div id="{{ $id }}" x-show="combinations.length > 0" class="mt-3">
    <table class="table table-bordered table-sm">
        <thead class="thead-light">
            <tr>
                <template x-for="attr in attributes" :key="attr.uid">
                    <th x-text="attr.name"></th>
                </template>
                <th>Giá bán</th>
                <th>SKU</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <template x-for="(combo, cIndex) in combinations" :key="combo.id">
                <tr>
                    <!-- Giá trị của từng attribute -->
                    <template x-for="val in combo.values" :key="val.attr_id">
                        <td x-text="val.value"></td>
                    </template>

                    <!-- Giá bán -->
                    <td>
                        <input type="number" class="form-control form-control-sm"
                               x-model="combo.price">
                    </td>

                    <!-- SKU -->
                    <td>
                        <input type="text" class="form-control form-control-sm"
                               x-model="combo.sku">
                    </td>

                    <!-- Xóa combination -->
                    <td>
                        <button type="button" class="btn btn-sm btn-danger"
                                @click="removeCombination(cIndex)">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            </template>
        </tbody>
    </table>
</div>
