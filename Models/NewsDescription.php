<?php

namespace Modules\News\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class NewsDescription extends Model
{
  use \Translatable;
  protected $table = 'news_descriptions';
  protected $primaryKey = 'id';
  public $incrementing = false;
  public $timestamps = false;

  public $translatedAttributes = ['name','description'];
  public $translationForeignKey = 'des_id';

  /**
   * The attributes that are not mass assignable.
   *
   * @var array
   */
  protected $guarded = ['created_at','updated_at'];

  public static function boot(){
      parent::boot();

      self::creating(function($model){
        $model->id = \Str::uuid();
      });

      static::created(function($model)
      {

      });

      static::saved(function($model)
      {

      });

      self::updated(function($model){

      });

      self::deleted(function($model){

      });
  }
}
