<?php

namespace Modules\News\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NewsCategory extends Controller
{
  // Define route
  public static function routes(){
    return \Route::group(['prefix' => (new \Modules\News\Models\NewsCategory)->seo_prefix,  "namespace"=>"web\\news"], function () {

      \Route::get('',                               [self::class, 'index'])
                                                    ->name('news.categories');

      \Route::get('{category_id}',                  [self::class, 'info'])
                                                    ->name('news.category');



    });
  }

  public function index(){
    return view('web.news.news-categories', $data);
  }

  public function info($category_id){
    $info = $data['category'] = \Modules\News\Models\NewsCategory::findByIdOrSlug($category_id);
    // echo $category_id;exit;
    if (!$data['category']) {
      abort(404);
    }

    // Page init.
    app('page')->initials($info->toArray());
    // Breadcrumb init.

    $breadcrumbs[] = array(
      'name'    =>    __('Home'),
      'href'    =>    route('home')
    );

    $paths = $info->getNewsCategoryPaths();
    // print_r($paths->toArray());
    if ($paths) {
      foreach ($paths as $path) {
        $breadcrumbs[] = array(
          'name'    =>    $path->name,
          'href'    =>    !empty($path->slug) ? url($path->slug) : ''
        );
      }
    }

    $breadcrumbs[] = array(
      'name'    =>    $info->name,
      'href'    =>    !empty($info->slug) ? url($info->slug) : ''
    );

    app('page')->set('breadcrumbs',$breadcrumbs);

    $childs = $info->getChilds();
    $data['childs'] = json_decode(json_encode(\Modules\News\Http\Resources\NewsCategory::collection($childs)),true);

    $filter = [
      'news_category_id'  =>  $info->id,
      'status' => 1,
      'limit' => 20,
      'page' => request()->get('page', 1)
    ];
    // print_r($filter);
    $data['newss'] = \Modules\News\Models\News::list($filter);

    $pagination = \Modules\News\Models\News::listPaginationInfo($filter);

    $data = array_merge($data, $pagination);

    return \Theme::view('web.news.news-category', $data);
  }
}
