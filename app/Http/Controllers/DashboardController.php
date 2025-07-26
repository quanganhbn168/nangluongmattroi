<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Product;
use App\Models\Post;
use App\Models\Contact;

class DashboardController extends Controller
{
    public function dashboard()
    {
        // Sử dụng hàm count() của Eloquent để đếm số lượng bản ghi
        $totalProducts = Product::count();
        $totalPosts = Post::count();
        $totalContacts = Contact::count();

        // Trả về view và truyền các biến ra ngoài
        return view('admin.dashboard', [
            'totalProducts' => $totalProducts,
            'totalPosts' => $totalPosts,
            'totalContacts' => $totalContacts,
        ]);

        return view('admin.dashboard');
    }

    public function toggleField(Request $request)
    {
        $request->validate([
            'model' => 'required|string',
            'id' => 'required|integer',
            'field' => 'required|string',
        ]);

        $modelClass = $this->resolveModelClass($request->model);
        if (!class_exists($modelClass)) {
            return response()->json(['error' => 'Model không tồn tại.'], 404);
        }

        $record = $modelClass::findOrFail($request->id);

        $field = $request->field;

        if (!array_key_exists($field, $record->getAttributes())) {
            return response()->json(['error' => 'Trường không hợp lệ.'], 422);
        }

        $record->$field = !$record->$field;
        $record->save();

        return response()->json([
            'success' => true,
            'value' => $record->$field,
            'message' => "Đã cập nhật $field thành " . ($record->$field ? '✓' : '✗')
        ]);
    }

// Helper nội bộ: resolve tên model từ string
    protected function resolveModelClass($model)
    {
        $model = Str::studly($model);
        return "App\\Models\\{$model}";
    }
}
