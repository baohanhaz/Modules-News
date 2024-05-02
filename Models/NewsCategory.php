<?php

namespace Modules\News\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Laravel\Scout\Searchable;

class NewsCategory extends Model
{
  use HasFactory;
  use \Translatable;
  use \Sluggable;
  use Searchable;
  use \App\Concerns\FiltAndSortManager;
  use \App\Concerns\SeoFriendly;

  protected $table = 'news_categories';

  public $translatedAttributes = ['name','description','meta_title','meta_description','meta_keyword'];
  protected $translationForeignKey = 'nc_id';

  public $seo_prefix = 'nc';

  /**
   * The attributes that are not mass assignable.
   *
   * @var array
   */
  protected $guarded = ['created_at','updated_at'];

  // The attributes can be filted with.
  public $filtable = [
    'name','status'
  ];

  // The attributes can be sorted with.
  public $sortable = [
    'created_at','name','status','sort_order'
  ];

  /**
   * The attributes that should be cast to native types.
   *
   * @var array
   */
  protected $casts = [
    'created_at' => 'datetime',
    'updated_at' => 'datetime',
    'header'     => 'integer',
    'status'     => 'integer',
    'store_id'   => 'integer',
  ];

  /**
   * Return the sluggable configuration array for this model.
   *
   * @return array
   */
  public function sluggable() : array{
      return [
          'slug' => [
              'source' => ['name']
          ]
      ];
  }

  public static function boot(){
      parent::boot();

      self::creating(function($model){
        if (!app('page')->isOriginDomain()) {
          $model->store_id = app('store_id');
        }
        if (auth('admin')->user()) {
          $model->creator_id = auth('admin')->user()->id ?? '';
        }
      });

      self::created(function($model){
        // Category Path.
        if ($model->parent_id) {
          $paths = \Modules\News\Models\NewsCategoryPath::where('category_id', $model->parent_id)->orderBy('level','asc')->get();
          $level = 0;
          foreach ($paths as $path) {
            \Modules\News\Models\NewsCategoryPath::create([
              'category_id'   =>  $model->id,
              'parent_id'     =>  $path->parent_id,
              'level'         =>  $level
            ]);
            $level++;
          }
          \Modules\News\Models\NewsCategoryPath::create([
            'category_id'   =>  $model->id,
            'parent_id'     =>  $model->parent_id,
            'level'         =>  $level
          ]);
        }
        \Cache::tags([(new self)->getTable()])->flush();
      });

      self::updating(function($model){
        if (auth('admin')->user()) {
          $model->updater = auth('admin')->user()->id ?? '';
        }
      });

      self::updated(function($model){
        // Category Path.
        if ($model->parent_id && $model->parent_id != $model->id) {
          // Delete old.
          \Modules\News\Models\NewsCategoryPath::where('category_id', $model->id)->delete();
          $paths = \Modules\News\Models\NewsCategoryPath::where('category_id', $model->parent_id)->orderBy('level','asc')->get();
          $level = 0;
          foreach ($paths as $path) {
            \Modules\News\Models\NewsCategoryPath::create([
              'category_id'   =>  $model->id,
              'parent_id'     =>  $path->parent_id,
              'level'         =>  $level
            ]);
            $level++;
          }
          \Modules\News\Models\NewsCategoryPath::create([
            'category_id'   =>  $model->id,
            'parent_id'     =>  $model->parent_id,
            'level'         =>  $level
          ]);
        }
        \Cache::tags([(new self)->getTable()])->flush();
      });

      self::saving(function($model)
      {
        $model->updater = auth('admin')->user()->id ?? '';
      });

      self::saved(function($model)
      {
        $model->updateSeoUrl();
        \Cache::tags([(new self)->getTable()])->flush();
      });

      self::deleted(function($model){
        $childs = \App\Models\catalog\ProductCategory::where('parent_id', $model->id)->get();
        foreach ($childs as $child) {
          $parent = \Modules\News\Models\NewsCategoryPath::where('category_id', $child->id)->orderBy('level','desc')->first();
          if ($parent) {
            $child->parent_id = $parent->parent_id;
            $child->save();
          }
        }
        $model->deleteSeoUrl();
        \Cache::tags([(new self)->getTable()])->flush();
      });
  }
  public function getImageAttribute($value)
  {
      return $value ? asset($value) : '';
  }
  public static function getListWithAllGetRequest(){

    $cache_key = (new self)->getTable() . md5('ListWithAllGetRequest.' . self::commonCacheKey() . '.'  . json_encode(request()->all()));
    return \Cache::tags([(new self)->getTable()])->remember($cache_key, env('CACHE_LIFETIME', 3600), function()
    {
      $query = new self;

      $query = $query->filter()->filterCurrentStore();
      // Info of pagination.
      $data = $query->getInfoPagination();
      // List
      $query = $query->sorter()->paginated();
      // echo $query->toSql();
      $data['results'] = $query->get()->toArray();

      // Add path name for sub category.
      foreach ($data['results'] as $key => $result) {
        $path_name = '';
        $data['results'][$key]['path_name'] = $result['name'];
        $paths = \Modules\News\Models\NewsCategoryPath::join('news_categories_translation', function ($join){
                                                      $join->on('news_category_path.parent_id', '=', 'news_categories_translation.nc_id')
                                                           ->on('news_categories_translation.locale','=', \DB::raw("'" . app('locale') . "'"));
                                                    })->where('category_id', $result['id'])->orderBy('level','asc')->get();
        if ($paths) {
          foreach ($paths as $path) {
            $path_name .= ($path_name ? ' > ' : '') . $path->name;
          }
        }
        if ($path_name) {
          $data['results'][$key]['path_name'] = $path_name . ' > ' . $result['name'];
        }
      }

      return $data;
    });
  }

  public function news() {
    return $this->belongsToMany(\Modules\News\Models\News::class,'news_to_categories','nc_id','news_id');
  }

  public function getNews($data = array()){
    $cache_key = (new self)->getTable() . '.' . md5('news.' . $this->id . '.' . json_encode($data));
    return \Cache::tags([(new \Modules\News\Models\News)->getTable()])->remember($cache_key, env('CACHE_LIFETIME', 3600), function() use($data)
    {
      $query = $this->news();

      if (isset($data['status'])) {
        $query = $query->where('news.status', (int)$data['status']);
      }

      if (isset($data['limit'])) {
        $query = $query->limit((int)$data['limit']);
      }

      return $query->get();
    });
  }

  public static function getCategoriesOnLevel($level=0){
    $filter = request()->get('filter',[]);
    $cache_key = (new self)->getTable() . '.' . md5('NewsCategoriesOnLevel.' . self::commonCacheKey() . $level . json_encode($filter));
    return \Cache::tags([(new self)->getTable()])->remember($cache_key, env('CACHE_LIFETIME', 3600), function() use($level,$filter)
    {
      $response = array();

      $query = self::filterCurrentStore();

      if ($level) {
        $category_ids = \Modules\News\Models\NewsCategoryPath::where('level', (int)$level - 1)->pluck('category_id')->toArray();
        $response = $query->wherein('id', $category_ids)->get();
      }else{
        $query->where(function ($query) {
          $query->orwhere('parent_id',0)
                ->orwhereNull('parent_id');
        });
        if(!empty($filter['type'])){
          $query = $query->where('type', $filter['type']);
        }else{
          $query = $query->where(function($query) use ($filter){
            $query->orwhereNull('type')
                    ->orwhere('type', '');
          });
        }
        $response = $query->get();
      }
      return $response ?? array();
    });
  }

  public static function getCategoryStructure($category_id = 0){
    $data = [];
    $cat = self::find($category_id);
    if ($cat) {
      $root_categories = $cat->getChilds();
    }else{
      $root_categories = self::getCategoriesOnLevel(0);
    }

    foreach ($root_categories as $category) {
      $tmp = [];
      $tmp['id'] = $category->id;
      $tmp['name'] = $category->name;
      $tmp['children'] = self::getCategoryStructure($category->id);
      $data[] = $tmp;
    }
    return $data;
  }

  /**
   * Get the parent's category record associated with the category.
   */
  public function parent(){
      return $this->belongsTo(self::class, 'parent_id');
  }

  public function getParent(){
    $cache_key = (new self)->getTable() . '.' . md5('parent.' . $this->id);
    return \Cache::tags([(new self)->getTable()])->remember($cache_key, env('CACHE_LIFETIME', 3600), function()
    {
      return $this->parent;
    });
  }

  /**
   * Get the child's categories record associated with the category.
   */
  public function childs(){
    return $this->hasMany(self::class, 'parent_id', 'id');
  }

  public function getChilds($data=[]){
    $cache_key = (new self)->getTable() . '.' . md5('childs.' . $this->id . '.' . json_encode($data));
    return \Cache::tags([(new self)->getTable()])->remember($cache_key, env('CACHE_LIFETIME', 3600), function()
    {
      $query = $this->childs();
      if(!empty($data['sort']) && !empty($data['order'])){
        $query = $query->orderBy($data['sort'],$data['order']);
      }
      return $query->get();
    });
  }

  /**
   * Get the child's categories record associated with the category.
   */
  public function news_category_paths(){
    return $this->hasMany(\Modules\News\Models\NewsCategoryPath::class, 'category_id', 'id');
  }

  public function getNewsCategoryPaths(){
    $cache_key = (new self)->getTable() . '.' . md5('news_category_paths.' . $this->id);
    return \Cache::tags([(new \Modules\News\Models\NewsCategoryPath)->getTable()])->remember($cache_key, env('CACHE_LIFETIME', 3600), function()
    {
      return $this->news_category_paths()->join((new self)->getTable(), (new \Modules\News\Models\NewsCategoryPath)->getTable() . '.parent_id', '=', (new self)->getTable() . '.id')->get();
    });
  }

  public static function menuAtPosition($position = 'header'){
    $cache_key = (new self)->getTable() . '.' . md5('menuAtPosition.' . self::commonCacheKey() . '.' . $position);
    return \Cache::tags([(new self)->getTable()])->remember($cache_key, env('CACHE_LIFETIME', 3600), function() use($position)
    {
      $response = array();

      $query = self::filterCurrentStore();

      if ($position == 'header') {
        $query = $query->where('header', 1);
      }

      $parents = $query->where(function ($query) {
          $query->orwhere('parent_id',0)
                ->orwhere('parent_id','')
                ->orwhereNull('parent_id');
      })->get();

      foreach ($parents as $parent) {
        $childs = array();

        foreach ($parent->getChilds() as $child) {
          $childs[] = array(
            'id'    =>  $child->id,
            'name'  =>  $child->name,
            'image' =>  $child->image ? asset($child->image) : '',
            'link'  =>  !empty($child->slug) ? url($child->slug) : '',
          );
        }

        // Child news
        $newss = array();
        foreach ($parent->getNews(['limit' => 10,'status' => 1]) as $news) {
          $newss[] = array(
            'id'    =>  $news->id,
            'title' =>  $news->title,
            'image' =>  $news->image ? asset($news->image) : '',
            'link'  =>  !empty($news->slug) ? url($news->slug) : '',
          );
        }

        $response[] = array(
          'id'    =>  $parent->id,
          'name'  =>  $parent->name,
          'image' =>  $parent->image ? asset($parent->image) : '',
          'link'  =>  !empty($parent->slug) ? url($parent->slug) : '',
          'childs'=>  $childs,
          'news'  =>  $newss
        );
      }
      // print_r($response);
      return $response;
    });
  }

  /**
   * Custom filter function. Get filter from request.
   *
   * @var array
   */
  public function scopeCustomFilter($builder){
    $filtable = $this->filtable ?? array();

    $builder = $builder->filterCurrentStore();

    $filter = request()->get('filter',array());

    if(!empty($filter['id']) &&is_numeric($filter['id'])){
      $builder = $builder->where('id', $filter['id']);
    }
    if(!empty($filter['ids']) &&is_array($filter['ids'])){
      $builder = $builder->wherein('id', $filter['ids']);
    }
    // print_r($filter);
    $builder = $builder->withTranslation();

    if(!empty($filter['name'])){
      $builder = $builder->where(function($query) use ($filter){
                      $query->orwhereTranslationLike('name', '%'.$filter['name'].'%');
      });
    }
    // print_r($builder->toSql());

    if(!empty($filter['type'])){
      $builder = $builder->where('type', $filter['type']);
    }else{
      $builder = $builder->where(function($query) use ($filter){
        $query->orwhereNull('type')
                ->orwhere('type', '')->orwhere('type', 'vat-pham')->orwhere('type', 'shop');
      });
    }

    if(isset($filter['status'])&&$filter['status']!= ''){
      $builder = $builder->where('status', $filter['status']);
    }

    // echo $builder->toSql();
    return $builder;
  }
}
