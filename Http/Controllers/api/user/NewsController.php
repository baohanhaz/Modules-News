<?php

namespace Modules\News\Http\Controllers\api\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NewsController extends Controller
{
  // Define route.
  public static function routes(){
    return \Route::group(['prefix' => 'user/news',  "namespace"=>"api\user", "middleware" => "auth:api"], function () {
      \Route::any('list',                     [self::class, 'list'])
                                              ->name('api.user.news.list')
                                              ->middleware('permission:access-news');

      \Route::post('deletes',                 [self::class, 'deletes'])
                                              ->name('api.user.news.deletes')
                                              ->middleware('permission:delete-news');

      \Route::any('form',                     [self::class, 'form'])
                                              ->name('api.user.news.form')
                                              ->middleware('permission:edit-news');

      \Route::post('add',                     [self::class, 'add'])
                                              ->name('api.user.news.add')
                                              ->middleware('permission:add-news');

      \Route::get('{news_id}/info',           [self::class, 'info'])
                                              ->name('api.user.news.info')
                                              ->middleware('permission:access-news');

      \Route::any('{news_id}/delete',         [self::class, 'delete'])
                                              ->name('api.user.news.delete')
                                              ->middleware('permission:delete-news');

      \Route::post('{news_id}/update',        [self::class, 'update'])
                                              ->name('api.user.news.update')
                                              ->middleware('permission:edit-news');
    });
  }

  public function list(){

    $data = \Modules\News\Models\News::getPageWithAllFilterRequest();
    
    foreach ($data['results'] as $key => $result) {
      $data['results'][$key]['link'] = route('news.info', $result['slug'] ?? '#');
      $data['results'][$key]['image'] = !empty($result['image']) ? asset($result['image']) : '';
      $categoryIds = \Modules\News\Models\NewsToCategory::where('news_id',$result['id'])->get()->pluck('nc_id');
      $categories = \Modules\News\Models\NewsCategory::wherein('id', $categoryIds)->get();
      $categories = json_decode(json_encode(\Modules\News\Http\Resources\NewsCategory::collection($categories)), true);
      $data['results'][$key]['categories'] = $categories;
    }

    return response()->json($data);
  }
  public function info($news_id){
    $data  = array();
    $info = \Modules\News\Models\News::find($news_id);

    if (!$info) {
      return response()->json($data);
    }

    $data['info'] = $info->toArray();

    return response()->json($data);
  }

  public function add(\App\Http\Requests\api\news\News $request){
    $data  = array();

    $query = new \Modules\News\Models\News;
    $inputs = $request->all();

    $info = $query->fill($inputs);
    $info->save();

    $this->saveReLatedData($info);

    return response()->json($data);
  }

  public function form(){
    $data  = array();

    if (request()->get('id')) {
      $info = \Modules\News\Models\News::find(request()->get('id'));
      if ($info) {
        $data['info'] = $info->toArray();
        $data['info']['translation'] = [];
        // print_r(count($info->getTranslationsArray()));
        foreach ($info->translations()->get() as $translation) {
          $data['info']['translation'][$translation['locale']] = $translation;
        }

        $data['info']['categories'] = [];
        foreach ($info->categories as $cat) {
          $data['info']['categories'][] = $cat['id'];
        }
        $data['info']['descriptions'] = [];
        foreach ($info->descriptions()->orderBy('sort_order','asc')->get() as $key => $des) {
          $tmp = $des->toArray();
          foreach ($des->translations as $translation) {
            $tmp['translations'][$translation->locale] = $translation->toArray();
          }
          $data['info']['descriptions'][] = $tmp;
        }
        $data['info']['banners'] = $info->banners;
      }
    }
    $data['categories'] = \Modules\News\Models\NewsCategory::getCategoryStructure();
    $data['languages'] = \Languages::list();
    $data['mucdichxemngays'] = (new \App\Models\sale\Order)->mucdichxemngays;

    return response()->json($data);
  }

  public function delete($news_id){
    $info = \Modules\News\Models\News::find($news_id);
    if($info){
      $info->delete();
      return response()->json($info->toArray());
    }
    return response()->json([]);
  }

  public function update($news_id, \App\Http\Requests\api\news\News $request){

    $data = array();
    $info = \Modules\News\Models\News::find($news_id);

    if (!$info) {
      return abort(404);
    }

    if (request()->has('image')) {
      $info->image = request()->get('image');
    }

    if (request()->has('slug')) {
      $info->slug = request()->get('slug');
    }
    if (request()->has('status')) {
      $info->status = (int)request()->get('status');
    }
    if (request()->has('tag')) {
      $info->tag = request()->get('tag');
    }

    if (request()->has('date_type')) {
      $info->date_type = (int)request()->get('date_type');
    }

    if (request()->has('date')) {
      $info->date = request()->get('date');
    }

    if (request()->has('date_each_year')) {
      $info->date_each_year = request()->get('date_each_year');
    }

    if (request()->has('services')) {
      $info->services = request()->get('services');
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
        !empty(request()->get('translation')[config('app.fallback_locale')]['title'])) {
      $info->translations()->delete();
      if (is_array(request()->get('translation'))) {
        $translations = request()->get('translation');
        foreach ($translations as $code => $translation) {
          if (!empty($translation['title'])) {
            $info->translations()->create(array_merge($translation, [
              'locale' => $code
            ]));
          }
        }
      }
    }
    
    // Categories
    if (request()->has('categories')) {
      \Modules\News\Models\NewsToCategory::where('news_id', $info->id)->delete();
      foreach (request()->get('categories') as $category_id) {
        if (!empty($category_id)) {
          $info->categories()->attach($category_id);
        }
      }
    }
    if (request()->has('descriptions')) {
      // $info->descriptions()->delete();
      // print_r($info->descriptions->toArray());
      if (is_array(request()->get('descriptions'))) {
        $desIds = [];
        foreach (request()->get('descriptions') as $key => $des) {
          
          $description = null;
          if(!empty($des['id'])){
            $description = $info->descriptions()->where('id',$des['id'])->first();
            $description->sort_order = $key;
            $description->save();
          }

          if(!$description){
            $description = $info->descriptions()->create([
              'sort_order' => $key
            ]);
          }
          $desIds[] = $description->id;
          $description->translations()->delete();
          foreach (($des['translations'] ?? []) as $code => $translation) {
            $description->translations()->create(array_merge($translation, [
              'locale' => $code
            ]));
          }
        }
        $info->descriptions()->whereNotIn('id',$desIds)->delete();
      }
    }
    // Banners
    if (request()->has('banners')) {
      \Modules\News\Models\NewToBanner::where('news_id', $info->id)->delete();
      foreach (request()->get('banners') as $banner_id) {
        if (!empty($banner_id)) {
          $info->banners()->attach($banner_id);
        }
      }
    }
  }

  public function deletes(){
    $ids = request()->get('selected',array());
    if (is_array($ids) ) {
      \Modules\News\Models\News::wherein('id',$ids)->delete();
      \Cache::tags([(new \Modules\News\Models\News)->getTable()])->flush();
    }
  }
}
