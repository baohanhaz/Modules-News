<?php

namespace Modules\News\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class NewsCategoryPath extends Model
{
  use \App\Concerns\FiltAndSortManager;

  protected $table = 'news_category_path';
  public $incrementing = false;
  public $timestamps = false;

  /**
   * The attributes that are not mass assignable.
   *
   * @var array
   */
  protected $guarded = ['created_at','updated_at'];
}
