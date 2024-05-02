<?php

namespace Modules\News\Http\Controllers\api\extension\module;

use App\Contracts\extension\module\ModuleAdminController;

class News extends ModuleAdminController
{

  public function form($data = array()){

    $resp['info'] = $data;
    $news_ids = $resp['info']['news'] ?? array();
    $resp['news'] = \Modules\News\Models\News::wherein('id',$news_ids)->get();
    return $resp;
  }

  public function validator($request=array()){

    $validator = \Validator::make($request, [
      'name'  =>  ['required', 'min:2', 'max:255']
    ]);

    return $validator;
  }
}
