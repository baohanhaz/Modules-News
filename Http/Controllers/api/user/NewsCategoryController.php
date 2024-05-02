<?php

namespace Modules\News\Http\Controllers\api\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NewsCategoryController extends Controller
{
  // Define route.
  public static function routes(){
    return \Route::group(['prefix' => 'user/news-category',  "namespace"=>"api\user", "middleware" => "auth:api"], function () {
      \Route::any('list',                     [self::class, 'list'])
                                              ->name('api.user.news.category.list')
                                              ->middleware('permission:access-news-category');

      \Route::post('deletes',                 [self::class, 'deletes'])
                                              ->name('api.user.news.category.deletes')
                                              ->middleware('permission:delete-news-category');

      \Route::any('form',                     [self::class, 'form'])
                                              ->name('api.user.news.category.form')
                                              ->middleware('permission:edit-news-category');

      \Route::post('add',                      [self::class, 'add'])
                                              ->name('api.user.news.category.add')
                                              ->middleware('permission:add-news-category');

      \Route::get('{news_category_id}/info',       [self::class, 'info'])
                                              ->name('api.user.news.category.info')
                                              ->middleware('permission:access-news-category');

      \Route::any('{news_category_id}/delete',     [self::class, 'delete'])
                                              ->name('api.user.news.category.delete')
                                              ->middleware('permission:delete-news-category');

      \Route::post('{news_category_id}/update',    [self::class, 'update'])
                                              ->name('api.user.news.category.update')
                                              ->middleware('permission:edit-news-category');
    });
  }

  public function list(){

    $data = \Modules\News\Models\NewsCategory::getListWithAllGetRequest();

    foreach ($data['results'] as $key => $result) {
      $data['results'][$key]['link'] = route('news.category', $result['slug'] ?? '#');
      $data['results'][$key]['image'] = !empty($result['image']) ? asset($result['image']) : '';
      $data['results'][$key]['created_at'] = (new \DateTime($result['created_at']))->format('Y-m-d H:i');
    }

    return response()->json($data);
  }
  public function info($news_category_id){
    $data  = array();
    $info = \Modules\News\Models\NewsCategory::find($news_category_id);

    if (!$info) {
      return response()->json($data);
    }

    $data['info'] = $info->toArray();

    return response()->json($data);
  }

  public function add(\App\Http\Requests\api\news\NewsCategory $request){
    $data  = array();

    $query = new \Modules\News\Models\NewsCategory;
    $inputs = $request->all();

    $info = $query->fill($inputs);
    $info->save();
    $this->saveReLatedData($info);

    return response()->json($data);
  }

  public function form(){
    $data  = array();

    if (request()->get('id')) {
      $info = \Modules\News\Models\NewsCategory::find(request()->get('id'));
      if ($info) {
        $data['info'] = $info->toArray();
        $data['info']['translation'] = [];
        // print_r(count($info->getTranslationsArray()));
        foreach ($info->translations()->get() as $translation) {
          $data['info']['translation'][$translation['locale']] = $translation;
        }
      }
    }
    $data['categories'] = \Modules\News\Models\NewsCategory::getCategoryStructure();
    $data['languages'] = \Languages::list();

    return response()->json($data);
  }

  public function delete($news_category_id){
    $info = \Modules\News\Models\NewsCategory::find($news_category_id);
    if($info){
      $info->delete();
      return response()->json($info->toArray());
    }
    return response()->json([]);
  }

  public function update($news_category_id, \App\Http\Requests\api\news\NewsCategory $request){

    $data = array();
    $info = \Modules\News\Models\NewsCategory::find($news_category_id);

    if (!$info) {
      return abort(404);
    }

    if (request()->has('image')) {
      $info->image = request()->get('image');
    }

    if (request()->has('slug')) {
      $info->slug = request()->get('slug');
    }

    if (request()->has('parent_id')) {
      $info->parent_id = (int)request()->get('parent_id');
    }

    if (request()->has('header')) {
      $info->header = (int)request()->get('header');
    }

    if (request()->has('status')) {
      $info->status = request()->get('status');
    }

    if (request()->has('sort_order')) {
      $info->sort_order = (int)request()->get('sort_order');
    }

    $info->save();

    $this->saveReLatedData($info);

    return response()->json($data);
  }

  protected function saveReLatedData($info){
    if (request()->has('translation') &&
        !empty(request()->get('translation')[config('app.fallback_locale')]['name'])) {
      $info->translations()->delete();
      if (is_array(request()->get('translation'))) {
        $translations = request()->get('translation');
        foreach ($translations as $code => $translation) {
          if (!empty($translation['name'])) {
            $info->translations()->create(array_merge($translation, [
              'locale' => $code
            ]));
          }
        }
      }
    }
  }

  public function deletes(){
    $ids = request()->get('selected',array());
    if (is_array($ids) ) {
      \Modules\News\Models\NewsCategory::wherein('id',$ids)->delete();
      \Cache::tags([(new \Modules\News\Models\NewsCategory)->getTable()])->flush();
    }
  }
}
