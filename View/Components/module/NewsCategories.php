<?php

namespace Modules\News\View\Components\module;

use App\Contracts\extension\module\ModuleComponent;

use Modules\News\Models\News;
use Modules\News\Models\NewsCategory;

class NewsCategories extends ModuleComponent
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($data = array())
    {
      if (!empty($data['categories'])) {
        if (is_array($data['categories'])) {
          $categoryIds = $data['categories'];
        }else{
          $categoryIds = array_map('intval', explode(',', $data['categories']));
        }
        $categories = NewsCategory::list(['ids' => $categoryIds,'status' => 1]);

        $data['categories'] = array();
        foreach ($categories as $category) {
          $news = $category->getNews(['status' => 1, 'limit' => 5]);
          if ($news) {
            $prs = array();
            foreach ($news as $new) {
              $prs[] = $new->toJson();
            }
            $data['categories'][] = array_merge(
              $category->toArray(),
              array('news' => $prs)
            );
          }
        }
      }

      if (!empty($data['news'])) {
        if (is_array($data['news'])) {
          $newIds = $data['news'];
        }else{
          $newIds = array_map('intval', explode(',', $data['news']));
        }
        $newss = News::list(['ids' => $newIds,'status' => 1]);
        $data['news'] = json_decode(json_encode(\Modules\News\Http\Resources\News::collection($newss)),true);
      }

      $this->data = $data;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        $data = $this->data;

        return \Theme::view('components.module.news-categories', $data);
    }
}
