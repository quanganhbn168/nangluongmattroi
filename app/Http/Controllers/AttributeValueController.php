<?php
namespace App\Http\Controllers;

use App\Models\AttributeValue;
use Illuminate\Http\Request;

class AttributeValueController extends Controller
{
    public function ajax(Request $request)
    {
        $attributeId = $request->get('attribute_id');

        if ($request->isMethod('post')) {
            $valueText = trim($request->input('value'));

            if (!$attributeId || $valueText === '') {
                return response()->json(['error' => 'Thiếu dữ liệu'], 422);
            }

            $value = AttributeValue::firstOrCreate([
                'attribute_id' => $attributeId,
                'value' => $valueText,
            ]);

            return response()->json([
                'id' => $value->id,
                'text' => $value->value,
            ]);
        }

        $query = $request->get('q', '');

        $values = AttributeValue::query()
            ->where('attribute_id', $attributeId)
            ->when($query, fn($q) => $q->where('value', 'like', "%{$query}%"))
            ->limit(20)
            ->get(['id', 'value']);

        return response()->json(
            $values->map(fn($val) => [
                'id' => $val->id,
                'text' => $val->value,
            ])
        );
    }
}
