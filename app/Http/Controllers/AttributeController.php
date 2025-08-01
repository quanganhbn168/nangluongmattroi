<?php

namespace App\Http\Controllers;

use App\Models\Attribute;
use Illuminate\Http\Request;

class AttributeController extends Controller
{
    public function ajax(Request $request)
    {
        $query = $request->input('q');

        $attributes = Attribute::query()
            ->when($query, fn($q) => $q->where('name', 'like', "%{$query}%"))
            ->select('id', 'name')
            ->limit(20)
            ->get();

        return response()->json(
            $attributes->map(fn($attr) => [
                'id' => $attr->id,
                'text' => $attr->name,
            ])
        );
    }

}
