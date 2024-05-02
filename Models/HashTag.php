<?php

namespace Modules\News\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class HashTag extends Model
{
  use HasFactory;
  use \Sluggable;
  use \App\Concerns\FiltAndSortManager;

  protected $table = 'news_hashtags';

  /**
   * The attributes that are not mass assignable.
   *
   * @var array
   */
  protected $guarded = ['created_at','updated_at'];

  // The attributes can be filted with.
  public $filtable = [
    'status',
    'title',
  ];
  // The attributes can be sorted with.
  public $sortable = [
    'created_at','title','status','updated_at'
  ];
  public $ordered = 'desc';
  /**
   * The attributes that should be cast to native types.
   *
   * @var array
   */
  protected $casts = [
    'created_at' => 'datetime',
    'updated_at' => 'datetime',
  ];
  /**
   * Return the sluggable configuration array for this model.
   *
   * @return array
   */
  public function sluggable() : array{
      return [
          'slug' => [
              'source' => ['title']
          ]
      ];
  }

  public static function boot(){
      parent::boot();

      static::creating(function($model)
      {
      });

      static::created(function($model)
      {
        \Cache::tags([(new self)->getTable()])->flush();
      });

      static::saving(function($model)
      {

      });

      static::saved(function($model)
      {
        \Cache::tags([(new self)->getTable()])->flush();
      });

      self::updating(function($model){
        
      });

      self::updated(function($model){
        // ... code here
        \Cache::tags([(new self)->getTable()])->flush();
      });

      self::deleted(function($model){
        \Cache::tags([(new self)->getTable()])->flush();
      });
  }
}
