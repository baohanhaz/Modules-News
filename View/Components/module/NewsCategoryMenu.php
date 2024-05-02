<?php

namespace Modules\News\View\Components\module;

use App\Contracts\extension\module\ModuleComponent;

use Modules\News\Http\Resources\NewsCategory as NewsCategoryResource;

class NewsCategoryMenu extends ModuleComponent
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($data=array())
    {
      $data['categories'] = \Modules\News\Models\NewsCategory::getCategoriesOnLevel(0);

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
        
        return \Theme::view('components.module.news-category-menu', $data);
    }

    // Return data
    public function data()
    {
        $this->data['categories'] = json_decode(json_encode(NewsCategoryResource::collection($this->data['categories'] ?? array())), true);
        return $this->data;
    }
}
