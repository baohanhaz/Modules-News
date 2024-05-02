<?php

namespace Modules\News\View\Components\module;

use App\Contracts\extension\module\ModuleComponent;

use Modules\News\Models\News;

class NewsTopViewed extends ModuleComponent
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($data=array())
    {
      $limit = $data['limit'] ?? 3;
      $data['newss'] = News::list(['limit' => $limit, 'sort' => 'viewed', 'order' => 'desc']);
      foreach ($data['newss'] as $key => $news) {
        $data['newss'][$key]['link'] = route('news.info', $news['slug']);
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
        
        return \Theme::view('components.module.news-top-viewed', $data);
    }
}
