<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Slide;
use App\Models\Category;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\PostCategory;
use App\Models\Post;
use App\Models\Intro;
use App\Models\Testimonial;
use App\Models\Team;
use App\Models\Product;
use App\Models\Branch;

class HomeController extends Controller
{
    public function index()
    {
        $products = Product::where("status",1)->get();
        $hotProducts = $products->where('is_featured',1);
        $saleProducts =  $products->where('is_on_sale',1);
        $partnerSlide = Slide::where('type',3)->where("status",1)->get();
        $slides = Slide::where('status',1)->where('type',1)->get();
        $sliders = Slide::where('status',1)->where("type",'2')->get();
        $intros = Intro::select("id","image","description","title")->get();
        $categories = Category::where('status',1)->where("is_home",1)->where("parent_id",0)->get();
        $serviceCategory = ServiceCategory::where('status', 1)->where("is_home",1)->where("parent_id",0)->get();
        $services = Service::where("status",1)->get();
        $homeCategories = PostCategory::where('status', 1)
            ->where('is_home', 1)
            ->with(['posts' => function ($q) {
                $q->where('status', 1)->latest()->limit(6);
            }])->get();
        $testimonials = Testimonial::where("status",1)->get();
        $teams = Team::get();
        $branches = Branch::get();
        return view('frontend.index', compact(
            "products",
            "branches",
            "hotProducts",
            "saleProducts",
            'slides',
            'sliders',
            'categories',
            'serviceCategory',
            'homeCategories',
            'intros',
            'testimonials',
            'teams',
            'services',
            'partnerSlide'
        ));
    }
 
    public function search(Request $request)
        {
            $keyword = trim($request->input('q'));

            if (empty($keyword)) {
                return redirect()->back();
            }

            // 1. Tìm kiếm Bài viết (Post)
            $posts = Post::where('status', 1)
                ->where(function ($query) use ($keyword) {
                    $query->where('title', 'LIKE', "%{$keyword}%")
                        ->orWhere('content', 'LIKE', "%{$keyword}%");
                })
                ->get()
                ->map(function ($item) {
                    $item->type = 'Bài viết';
                    // Thay 'frontend.post.detail' bằng tên route chi tiết bài viết của bạn
                    $item->url = route('frontend.post.detail', $item->slug); 
                    return $item;
                });

            // 2. Tìm kiếm Sản phẩm (Product)
            $products = Product::where('status', 1)
                ->where(function ($query) use ($keyword) {
                    $query->where('name', 'LIKE', "%{$keyword}%")
                        ->orWhere('description', 'LIKE', "%{$keyword}%");
                })
                ->get()
                ->map(function ($item) {
                    $item->type = 'Sản phẩm';
                     // Thay 'frontend.product.detail' bằng tên route chi tiết sản phẩm của bạn
                    $item->url = route('frontend.product.detail', $item->slug);
                    $item->title = $item->name; // Đồng bộ hóa trường tiêu đề
                    return $item;
                });

            // 3. Tìm kiếm Dịch vụ (Service)
            $services = Service::where('status', 1)
                ->where(function ($query) use ($keyword) {
                    $query->where('name', 'LIKE', "%{$keyword}%")
                        ->orWhere('description', 'LIKE', "%{$keyword}%");
                })
                ->get()
                ->map(function ($item) {
                    $item->type = 'Dịch vụ';
                     // Thay 'frontend.service.detail' bằng tên route chi tiết dịch vụ của bạn
                    $item->url = route('frontend.service.detail', $item->slug); 
                    $item->title = $item->name; // Đồng bộ hóa trường tiêu đề
                    return $item;
                });

            // Gộp tất cả kết quả vào một Collection
            $results = collect($posts)->concat($products)->concat($services)->sortByDesc('created_at');

            // Tự tạo phân trang cho Collection
            $perPage = 10;
            $currentPage = $request->input('page', 1);
            $pagedResults = $results->slice(($currentPage - 1) * $perPage, $perPage)->all();
            $paginatedResults = new LengthAwarePaginator(
                $pagedResults,
                $results->count(),
                $perPage,
                $currentPage,
                ['path' => $request->url(), 'query' => $request->query()]
            );

            // Trả về view mới, thay thế 'frontend.posts.result'
            return view('frontend.search_result', [
                'results' => $paginatedResults,
                'keyword' => $keyword
            ]);
        }
}
