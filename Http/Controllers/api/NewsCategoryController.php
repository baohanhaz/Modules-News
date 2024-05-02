<?php

namespace Modules\News\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Modules\News\Http\Resources\News as NewsResource;
use Modules\News\Http\Resources\NewsCategory as NewsCategoryResource;

class NewsCategoryController extends Controller
{
  // Define route.
  public static function routes(){
    return \Route::group(['prefix' => 'news-category',      "namespace"=>"api\\news"], function () {

      \Route::get('/',                        [self::class, 'list'])
                                              ->name('api.news.category');

      \Route::any('/structure',               [self::class, 'structure'])
                                              ->name('api.news.category.structure');

      \Route::get('/{news_id}/info',          [self::class, 'info'])
                                              ->name('api.news.category.info');

    });
  }

  public function structure(){
    $json = \Modules\News\Models\NewsCategory::getCategoryStructure();
    return response()->json($json);
  }

  public function list(){
    $filter = request()->get('filter');

    $filter['status'] = 1;

    request()->merge(['filter'=> $filter]);

    $query = new \Modules\News\Models\NewsCategory;

    $query = $query->newsFilter();
    // Info of pagination.
    $data = $query->getInfoPagination();
    // List
    $query = $query->sorter()->paginated();
    // echo $query->toSql();
    $data['results'] = NewsCategoryResource::collection($query->get());

    foreach ($data['results'] as $key => $result) {
      $data['results'][$key]['link'] = route('news.category.info', $result['slug']);
    }

    return $data;
  }

  public function info($news_category_id){
    $data = array();

    $info = \Modules\News\Models\NewsCategory::find($news_category_id);
    if (!$info) {
      abort(404);
    }

    $data = json_decode(json_encode(NewsCategoryResource::make($info)),true);

    $data['description'] = $info->description;

    $data['parents'] = array();
    $categories = $info->getChilds(['sort' => 'sort_order','order' => 'asc']);
    // print_r($categories);
    
    foreach ($categories as $key => $category) {
      $news = json_decode(json_encode(NewsResource::collection($category->news()->orderBy('sort_order','asc')->get())),true);
      $data['categories'][] = array_merge(json_decode(json_encode(NewsCategoryResource::make($category)),true),[
        'news'  =>  $news
      ]);
    }
    $parent = $info->getParent();
    if ($parent) {
      $data['parents'][] = json_decode(json_encode(NewsCategoryResource::make($parent)),true);
    }
    $filter = array(
      'status' => 1,
      'categories' => [$info->id],
      'sort'  => request()->get('sort','sort_order'),
      'order' => request()->get('order','asc'),
    );

    $newss = \Modules\News\Models\News::list($filter);

    $data = array_merge($data, \Modules\News\Models\News::listPaginationInfo($filter));

    $data['news'] = NewsResource::collection($newss);

    return response()->json($data);
  }
}
