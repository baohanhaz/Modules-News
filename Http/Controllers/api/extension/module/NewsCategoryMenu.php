<?php

namespace Modules\News\Http\Controllers\api\extension\module;

use App\Contracts\extension\module\ModuleAdminController;

class NewsCategoryMenu extends ModuleAdminController
{

  public function form($data = array()){

    $resp['info'] = $data;

    return $resp;
  }

  public function validator($request=array()){

    $validator = \Validator::make($request, [
      'name'  =>  ['required', 'min:2', 'max:255']
    ]);

    return $validator;
  }
}
