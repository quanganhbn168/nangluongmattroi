<?php

namespace App\Http\View\Composers;

use App\Models\Category;
use Illuminate\View\View;

class MenuComposer
{
    public function compose(View $view)
    {
        // Lấy các danh mục CHA là menu, và eager-load các danh mục CON cũng là menu
        $categoryMenus = Category::where('parent_id', 0) // Chỉ lấy danh mục cha
                                   ->where('status', 1)
                                   ->where('is_menu', 1)
                                   ->with(['children' => function($query) {
                                       $query->where('status', 1)->where('is_menu', 1);
                                   }])
                                   ->get();

        // Gửi biến $categoryMenus đến view
        $view->with('categoryMenus', $categoryMenus);
    }
}