<?php

namespace Modules\News\Rules;

use Illuminate\Contracts\Validation\Rule;

class NewsSeoUrl implements Rule
{
  protected $news_id;
  /**
   * Create a new rule instance.
   *
   * @return void
   */
  public function __construct($news_id)
  {
    $this->news_id = $news_id;
  }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
      $query = \App\Models\SeoUrl::where('slug', $value)->where('query','<>',(new \Modules\News\Models\News)->seo_prefix . '/' . $this->news_id);
      if (app('store_id')) {
        $query = $query->where('store_id', app('store_id'));
      }else{
        $query = $query->where(function ($q) {
            $q->orWhereNull('store_id')->orWhere('store_id', '')->orWhere('store_id', 0);
        });
      }
      
      $seo_url = $query->first();

      if ($seo_url) {
        return false;
      }

      return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'SeoUrl not valid or had existed!';
    }
}
