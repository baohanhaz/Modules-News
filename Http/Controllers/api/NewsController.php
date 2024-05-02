<?php

namespace Modules\News\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Modules\News\Http\Resources\News as NewsResource;
use Modules\News\Models\News;

class NewsController extends Controller
{
  // Define route.
  public static function routes(){
    return \Route::group(['prefix' => 'news',      "namespace"=>"api\\news"], function () {
      \Route::get('search_statistic',         [self::class, 'search_statistic'])
                                              ->name('api.news.search_statistic');

      \Route::any('menu',                     [self::class, 'menu'])
                                              ->name('api.news.menu');

      \Route::get('/',                        [self::class, 'list'])
                                              ->name('api.news');

      \Route::get('/{news_id}/info',          [self::class, 'info'])
                                              ->name('api.news.info');

    });
  }

  // Request without authorization.
  public function search_statistic(){

    $query = new News;

    $query = $query->customFilter();
    // echo $query->toSql();
    // List
    $query = $query->sorter()->paginated();
    // Info of pagination.
    $data = $query->getInfoPagination();

    $data['results'] = NewsResource::collection($query->get());

    foreach ($data['results'] as $key => $result) {
      $data['results'][$key]['link'] = route('news.info', $result['slug']);
    }

    return $data;
  }

  public function list(){
    $filter = request()->get('filter');

    $filter['status'] = 1;

    request()->merge(['filter'=> $filter,'sort'=>'sort_order','order' => 'asc']);

    $query = new News;

    $query = $query->customFilter();
    // Info of pagination.
    $data = $query->getInfoPagination();
    // List
    $query = $query->sorter()->paginated();
    // echo $query->toSql();
    $data['results'] = NewsResource::collection($query->get());

    foreach ($data['results'] as $key => $result) {
      $data['results'][$key]['link'] = route('news.info', $result['slug']);
    }

    return $data;
  }

  public function menu(){
    $json['results'] = array();

    $categories = \Modules\News\Models\NewsCategory::getCategoriesOnLevel(0);
    // print_r($categories);
    foreach ($categories as $category) {
        // Level 2 
        $children_data = array();

        $children = $category->getChilds();

        foreach ($children as $child) {

          $children_data[] = array(
            'id' => $child->id,
            'image' => asset($child->image),
            'name'  => $child->name,
          );
        }
        // Level 1
        $json['results'][] = array(
          'id'       => $category->id,
          'image'    => asset($category->image),
          'name'     => html_entity_decode($category->name),
          'children' => $children_data,
        );
    }
    
    return response()->json($json);
  }

  public function info($news_id){
    $json = array();

    $news = \Modules\News\Models\News::find($news_id);
    if ($news) {
      // $json = $news->toArray();
      $json = json_decode(json_encode(\Modules\News\Http\Resources\News::make($news)),true);
      $json['description'] = $news->description;
      $json['descriptions'] = $news->descriptions()->orderBy('sort_order','asc')->get();
    }

    return response()->json($json);
  }
}
