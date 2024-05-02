<?php

namespace Modules\News\View\Components\module;

use App\Contracts\extension\module\ModuleComponent;

class NewsHashTags extends ModuleComponent
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($data=array())
    {

      $data['hashtags'] = \Modules\News\Models\HashTag::list(['sort' => 'viewed', 'order' => 'desc','limit' => 5]);
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

        return \Theme::view('components.module.news-hashtags', $data);
    }
}
