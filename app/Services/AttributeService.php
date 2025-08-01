<?php

namespace App\Services;

use App\Models\Attribute;
use App\Models\AttributeValue;
use Illuminate\Support\Collection;

class AttributeService
{
    /**
     * Lấy danh sách attribute kèm value phổ biến để preload cho trang Create
     *
     * @param int $valueLimit Giới hạn số value cho mỗi attribute
     * @return \Illuminate\Support\Collection
     */
    public function getAttributesWithValues(int $valueLimit = 20): Collection
    {
        return Attribute::with(['values' => function ($q) use ($valueLimit) {
                $q->orderBy('value')->limit($valueLimit);
            }])
            ->orderBy('name')
            ->get()
            ->map(function ($attr) {
                return [
                    'id' => $attr->id,
                    'name' => $attr->name,
                    'values' => $attr->values->map(fn($v) => [
                        'id' => $v->id,
                        'value' => $v->value,
                    ]),
                ];
            });
    }

    /**
     * Tìm hoặc tạo mới attribute theo tên
     *
     * @param string $name
     * @return int ID của attribute
     */
    public function resolveOrCreate(string $name): int
    {
        $attribute = Attribute::firstOrCreate(['name' => $name]);
        return $attribute->id;
    }

    /**
     * Tìm hoặc tạo mới value cho attribute
     *
     * @param int $attributeId
     * @param string $value
     * @return int ID của attribute value
     */
    public function resolveOrCreateValue(int $attributeId, string $value): int
    {
        $attributeValue = AttributeValue::firstOrCreate([
            'attribute_id' => $attributeId,
            'value' => $value,
        ]);

        return $attributeValue->id;
    }

    /**
     * Lấy danh sách attribute cho Select2 Ajax
     *
     * @param string|null $keyword
     * @return array
     */
    public function getAttributesForSelect2(?string $keyword = null): array
    {
        $query = Attribute::query();

        if ($keyword) {
            $query->where('name', 'LIKE', "%{$keyword}%");
        }

        return $query->orderBy('name')
            ->limit(20)
            ->get(['id', 'name'])
            ->map(fn($attr) => ['id' => $attr->id, 'name' => $attr->name])
            ->toArray();
    }

    /**
     * Lấy danh sách value cho Select2 Ajax theo attribute_id
     *
     * @param int $attributeId
     * @param string|null $keyword
     * @return array
     */
    public function getValuesForSelect2(int $attributeId, ?string $keyword = null): array
    {
        $query = AttributeValue::query()->where('attribute_id', $attributeId);

        if ($keyword) {
            $query->where('value', 'LIKE', "%{$keyword}%");
        }

        return $query->orderBy('value')
            ->limit(20)
            ->get(['id', 'value'])
            ->map(fn($v) => ['id' => $v->id, 'name' => $v->value])
            ->toArray();
    }
}
