<?php

namespace Modules\News\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class NewToHashTag extends Model
{
  use HasFactory;
  use \App\Concerns\FiltAndSortManager;
  protected $table = 'news_to_hashtags';
  public $timestamps = false;

  /**
   * The attributes that are not mass assignable.
   *
   * @var array
   */
  protected $guarded = ['created_at','updated_at'];

  public static function boot()
  {
    parent::boot();
    static::updated(function($model)
    {
      \Cache::tags([(new self)->getTable()])->flush();
    });
    static::saved(function($model)
    {
      \Cache::tags([(new self)->getTable()])->flush();
    });
    static::deleted(function($model)
    {
      \Cache::tags([(new self)->getTable()])->flush();
    });
  }
}
