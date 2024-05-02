<?php

namespace Modules\News\Http\Controllers\api\extension\dashboard;

use App\Contracts\extension\dashboard\DashboardAdminController;

class NewsTotal extends DashboardAdminController
{
  public function form($data = array()){

    $resp['info'] = $data;

    return $resp;
  }

  public function validator($request=array()){

    $validator = \Validator::make($request, [

    ]);

    return $validator;
  }
}
