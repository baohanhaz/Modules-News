<?php

namespace Modules\News\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Laravel\Scout\Searchable;

use Modules\News\Models\NewsCategory;

class News extends Model
{
  use HasFactory;
  use Searchable;
  use \Translatable;
  use \Sluggable;
  // Custom filtable.
  // use Filtable;
  use \App\Concerns\FiltAndSortManager;
  use \App\Concerns\SeoFriendly;

  protected $table = 'news';

  public $translatedAttributes = ['title','short_description','description','meta_title','meta_description','meta_keyword'];
  protected $translationForeignKey = 'news_id';

  public $seo_prefix = 'news';
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
    'news_category_id',
    'categories'
  ];
  // The attributes can be sorted with.
  public $sortable = [
    'created_at',
    'sort_order',
    'title',
    'status',
    'updated_at'
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
    'status'     => 'integer',
    'store_id'   => 'integer',
  ];
  public function getImageAttribute($value)
  {
      return $value ? asset($value) : '';
  }
  /**
   * Set the status.
   *
   * @param  string  $value
   * @return void
   */
  public function setStatusAttribute($value)
  {
      $this->attributes['status'] = (int)$value;
  }
  public function setServicesAttribute($value)
  {
      $this->attributes['services'] = is_array($value) ? json_encode($value) : $value;
  }
  public function getServicesAttribute($value)
  {
      return json_decode($value, true);
  }
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
        if (!app('page')->isOriginDomain()) {
          $model->store_id = app('store_id');
        }
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
        $model->updateSeoUrl();
        \Cache::tags([(new self)->getTable()])->flush();
      });

      // self::updating(function($model){
      //
      // });
      //
      // self::updated(function($model){
      //   echo '222';
      //   // ... code here
      //   \Cache::tags([(new self)->getTable()])->flush();
      // });

      self::deleted(function($model){
        $model->deleteSeoUrl();
        \Cache::tags([(new self)->getTable()])->flush();
      });
  }
  public function toJson($options = 0){
    return json_decode(json_encode(\Modules\News\Http\Resources\News::make($this)),true);
  }
  // Info of news.
  public static function getInfo($key){

    $data = array();

    $cache_key = (new self)->getTable() . '.' . md5('getInfo.' . $key);

    $info = \Cache::tags([(new self)->getTable()])->remember($cache_key, env('CACHE_LIFETIME', 3600), function() use($key)
    {
      $query = new self;

      $query = $query->where('id', $key)->orwhere('slug',$key)->first();

      return $query->get();
    });

    $data = $info[0] ?? array();

    return $data;
  }

  /**
   * Get the category record associated with the news.
   */

  public function categories() {
    return $this->belongsToMany(\Modules\News\Models\NewsCategory::class,'news_to_categories','news_id','nc_id');
  }

  public function getCategories($data = array()){
    $cache_key = (new self)->getTable() . '.' . md5('.categories.' . $this->id . json_encode($data));
    return \Cache::tags([(new \Modules\News\Models\NewsCategory)->getTable()])->remember($cache_key, env('CACHE_LIFETIME', 3600), function() use($data)
    {
      $query = $this->categories();

      return $query->get();
    });
  }

  public function descriptions(){
    return $this->hasMany(\Modules\News\Models\NewsDescription::class,'news_id', 'id');
  }

  public function getDescriptions() {
    $cache_key = (new self)->getTable() . '.descriptions.' . $this->id;
    return \Cache::tags([(new self)->getTable()])->remember($cache_key, env('CACHE_LIFETIME', 3600), function()
    {
      return $this->descriptions()->orderBy('sort_order','asc')->get();
    });
  }

  /**
   * Get the banners record associated with the news.
   */
  
  public function banners() {
    return $this->belongsToMany(\App\Models\layout\Banner::class,'news_to_banners','news_id','banner_id');
  }

  public function getBanners($data = array()){
    $cache_key = (new self)->getTable() . '.' . md5('.banners.' . $this->id . json_encode($data));
    return \Cache::tags([(new \App\Models\layout\Banner)->getTable()])->remember($cache_key, env('CACHE_LIFETIME', 3600), function() use($data)
    {
      $query = $this->banners();

      return $query->get();
    });
  }


  public function hashtags() {
    return $this->belongsToMany(\Modules\News\Models\HashTag::class,'news_to_hashtags','news_id','hashtag');
  }

  public function getHashtags($data = array()){
    $cache_key = (new self)->getTable() . '.' . md5('.hashtags.' . $this->id . json_encode($data));
    return \Cache::tags([(new \Modules\News\Models\HashTag)->getTable()])->remember($cache_key, env('CACHE_LIFETIME', 3600), function() use($data)
    {
      $query = $this->hashtags();
      return $query->get();
    });
  }

  public function creator(){
      return $this->belongsTo(\Users::class, 'creator_id');
  }

  public function getCreator() {
    $cache_key = (new self)->getTable() . '.creator.' . $this->id;
    return \Cache::tags([(new \Users)->getTable()])->remember($cache_key, env('CACHE_LIFETIME', 3600), function()
    {
      return $this->creator;
    });
  }

  public function updater(){
      return $this->belongsTo(\Users::class, 'updater_id');
  }

  public function getUpdater() {
    $cache_key = (new self)->getTable() . '.updater.' . $this->id;
    return \Cache::tags([(new \Users)->getTable()])->remember($cache_key, env('CACHE_LIFETIME', 3600), function()
    {
      return $this->updater;
    });
  }

  public function scopeCustomFilter($builder){
    $filtable = $this->filtable ?? array();

    $builder = $builder->filterCurrentStore();

    $filter = request()->get('filter',array());

    $builder = $builder->withTranslation();

    if(!empty($filter['name'])){
      $builder = $builder->where(function($query) use ($filter){
        $query->orwhereTranslationLike('title', '%'.$filter['name'].'%');
      });
    }

    if(isset($filter['type']) && $filter['type']!= ''){
      $builder = $builder->where('type', $filter['type']);
    }
    if (empty($filter['type'])) {
      $builder = $builder->where(function($query) use ($filter){
        $query->orwhereNull('type')
                ->orwhere('type', '');
      });
    }

    if (!empty($filter['category_id'])) {
      $builder = $builder->with('categories')->whereHas('categories', function ($builder) use($filter) {
          $builder->where('nc_id', $filter['category_id']);
      });
    }

    if (!empty($filter['news_category_id'])) {
      $builder = $builder->with('categories')->whereHas('categories', function ($builder) use($filter) {
          $builder->where('nc_id', $filter['news_category_id']);
      });
    }

    if (!empty($filter['categories'])) {

      if (is_array($filter['categories'])) {
        $category_ids = $filter['categories'];
      }else{
        $category_ids = explode(',',$filter['categories']);
      }
      $builder = $builder->whereHas('categories', function ($builder) use($category_ids) {
          $builder->wherein('nc_id', $category_ids);
      });
      // print_r($builder->toSql());
    }

    if(isset($filter['status'])&&$filter['status']!= ''&&in_array('status',$filtable)){
      $builder = $builder->where('status', $filter['status']);
    }

    return $builder;
  }
}
