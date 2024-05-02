<?php

namespace Modules\News\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Modules\News\Models\News as NewsModel;
use Modules\News\Models\NewsToCategory;

class News extends Controller
{
  // Define route
  public static function routes(){
    return \Route::group(['prefix' => (new \Modules\News\Models\News)->seo_prefix,  "namespace"=>"web\\news"], function () {
      \Route::get('',                               [self::class, 'index'])
                                                    ->name('news');

      \Route::get('{news_id}',                      [self::class, 'info'])
                                                    ->name('news.info');
    });
  }

  public function index(){
    $newss = NewsModel::list(['status' => 1]);
    $data['newss'] = json_decode(json_encode(\Modules\News\Http\Resources\News::collection($newss)),true);

    return view('web.news.news', $data);
  }

  public function info($news_id){

    $data['info'] = NewsModel::findByIdOrSlug($news_id);
    // print_r($news_id);exit;
    if (!$data['info']||!$data['info']->status) {
      abort(404);
    }

    // Page init.
    app('page')->initials($data['info']->toArray());
    // Breadcrumb init.
    $this->setBreadCrumb($data['info']);

    $data['banners'] = $data['info']->getBanners();

    return \Theme::view('web.news.news_info', $data);
  }

  protected function setBreadCrumb($info){
    $categories = $info->getCategories(['limit' => 1]);
    $category = $categories[0]?? array();
    // print_r($info);
    // Breadcrumb init.
    $breadcrumb = array(
      array(
        'name'    =>    __('Home'),
        'href'    =>    route('home')
      ),
    );
    if ($category) {
      $paths = $category->getNewsCategoryPaths();
      if ($paths) {
        foreach ($paths as $path) {
          $breadcrumb[] = array(
            'name'    =>    $path->name,
            'href'    =>    !empty($path->slug) ? url($path->slug) : ''
          );
        }
      }
      $breadcrumb[] = array(
        'name'    =>    $category->name,
        'href'    =>    !empty($category->slug) ? url($category->slug) : ''
      );
    }

    // $breadcrumb[] = array(
    //   'name'    =>    mb_strimwidth($info->title, 0, 25, " ..."),
    //   'href'    =>    !empty($info->slug) ? route('news',$info->slug) : ''
    // );

    app('page')->set('breadcrumbs', $breadcrumb);
  }
}
