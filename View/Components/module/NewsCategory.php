<?php

namespace Modules\News\View\Components\module;

use App\Contracts\extension\module\ModuleComponent;

use Modules\News\Models\NewsCategory as NewsCategoryModel;

class NewsCategory extends ModuleComponent
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($data=array())
    {
      if (!empty($data['category_id'])) {
        $data['category'] = NewsCategoryModel::find($data['category_id']);
        $newss = $data['category']->getNews([
          'status' => 1,
          'limit' => !empty($data['limit']) ? (int)$data['limit'] : env('DB_ROW_LIMIT', 20),
        ]);
        $data['newss'] = json_decode(json_encode(\Modules\News\Http\Resources\News::collection($newss)),true);
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

        return \Theme::view('components.module.news-category', $data);
    }
}
