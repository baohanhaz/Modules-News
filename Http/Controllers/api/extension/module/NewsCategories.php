<?php

namespace Modules\News\Http\Controllers\api\extension\module;

use App\Contracts\extension\module\ModuleAdminController;

class NewsCategories extends ModuleAdminController
{

  public function form($data = array()){

    $resp['info'] = $data;
    if (isset($resp['info']['categories'])) {
      $resp['categories'] = \Modules\News\Models\NewsCategory::list(['ids' => $resp['info']['categories']]);
    }
    if (isset($resp['info']['news'])) {
      $resp['news'] = \Modules\News\Models\News::list(['ids' => $resp['info']['news']]);
    }
    return $resp;
  }

  public function validator($request=array()){

    $validator = \Validator::make($request, [
      'name'  =>  ['required', 'min:2', 'max:255']
    ]);

    return $validator;
  }
}
