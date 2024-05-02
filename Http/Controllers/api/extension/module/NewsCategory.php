<?php

namespace Modules\News\Http\Controllers\api\extension\module;

use App\Contracts\extension\module\ModuleAdminController;

class NewsCategory extends ModuleAdminController
{

  public function form($data = array()){

    $resp['info'] = $data;
    if (!empty($resp['info']['category_id'])) {
      $resp['category'] = \Modules\News\Models\NewsCategory::find($resp['info']['category_id']);
    }

    if (!empty($resp['info']['news'])) {
      $resp['news'] = \Modules\News\Models\News::whereIn('id', $resp['info']['news'])->get();
    }
    return $resp;
  }

  public function validator($request=array()){

    $validator = \Validator::make($request, [
      'name'  =>  ['required', 'min:2', 'max:255'],
      'category_id' => ['required', 'exists:news_categories,id'],
    ]);

    return $validator;
  }
}
