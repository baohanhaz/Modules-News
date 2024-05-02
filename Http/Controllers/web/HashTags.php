<?php

namespace Modules\News\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HashTags extends Controller
{
  // Define route
  public static function routes(){
    return \Route::group(['prefix' => 'hashtag',  "namespace"=>"web\\news"], function () {

      \Route::get('{slug}',                       [self::class, 'info'])
                                                    ->name('hashtag.info');
    });
  }

  public function info($slug){

    $info = $data['info'] = \Modules\News\Models\HashTag::findByIdOrSlug($slug);

    if (!$data['info']||!$data['info']->status) {
      abort(404);
    }

    // Page init.
    app('page')->initials($data['info']->toArray());
    // Breadcrumb init.
    app('page')->set('breadcrumbs',array(
      array(
        'name'    =>    __('Home'),
        'href'    =>    route('home')
      ),
      array(
        'name'    =>    strlen($info->title) > 20 ? substr($info->title,0,20) . '...' : $info->title,
        'href'    =>    !empty($info->slug) ? route('news',$info->slug) : ''
      ),
    ));

    return \Theme::view('web.news.hashtag_info', $data);
  }
}
