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
        $categoriesWithProducts = Category::where('status', 1)
            ->whereHas('products') 
            ->with(['products' => function ($query) {
                $query->where('status', 1)->take(8);
            }])
            ->get();
        $featuredCategories = Category::where('status', 1)
                                      ->take(6) 
                                      ->get();
        $banners = Slide::where('status',1)->where("type",'4')->get();
        $products = Product::where("status",1)->get();
        $hotProducts = $products->where('is_featured',1);
        $saleProducts =  $products->where('is_on_sale',1);
        $partnerSlide = Slide::where('type',3)->where("status",1)->get();
        $slides = Slide::where('status',1)->where('type',1)->get();
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
            "categoriesWithProducts",
            "featuredCategories",
            "products",
            "branches",
            "hotProducts",
            "saleProducts",
            'slides',
            'banners',
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
            $posts = Post::where('status', 1)
                ->where(function ($query) use ($keyword) {
                    $query->where('title', 'LIKE', "%{$keyword}%")
                        ->orWhere('content', 'LIKE', "%{$keyword}%");
                })
                ->get()
                ->map(function ($item) {
                    $item->type = 'Bài viết';
                    $item->url = route('frontend.post.detail', $item->slug); 
                    return $item;
                });
            $products = Product::where('status', 1)
                ->where(function ($query) use ($keyword) {
                    $query->where('name', 'LIKE', "%{$keyword}%")
                        ->orWhere('description', 'LIKE', "%{$keyword}%");
                })
                ->get()
                ->map(function ($item) {
                    $item->type = 'Sản phẩm';
                    $item->url = route('frontend.product.detail', $item->slug);
                    $item->title = $item->name; 
                    return $item;
                });
            $services = Service::where('status', 1)
                ->where(function ($query) use ($keyword) {
                    $query->where('name', 'LIKE', "%{$keyword}%")
                        ->orWhere('description', 'LIKE', "%{$keyword}%");
                })
                ->get()
                ->map(function ($item) {
                    $item->type = 'Dịch vụ';
                    $item->url = route('frontend.service.detail', $item->slug); 
                    $item->title = $item->name; 
                    return $item;
                });
            $results = collect($posts)->concat($products)->concat($services)->sortByDesc('created_at');
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
            return view('frontend.search_result', [
                'results' => $paginatedResults,
                'keyword' => $keyword
            ]);
        }
}