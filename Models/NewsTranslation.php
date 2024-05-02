<?php

namespace Modules\News\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class NewsTranslation extends Model
{
  protected $table = 'news_translation';
  public $incrementing = false;
  public $timestamps = false;

  /**
   * The attributes that are not mass assignable.
   *
   * @var array
   */
  protected $guarded = ['created_at','updated_at'];
}
