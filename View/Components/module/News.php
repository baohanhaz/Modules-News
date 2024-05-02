<?php

namespace Modules\News\View\Components\module;

use App\Contracts\extension\module\ModuleComponent;

use Modules\News\Models\News as NewsModel;

class News extends ModuleComponent
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($data=array())
    {
      if (is_array($data['news'])) {
        $news_ids = $data['news'];
      }else{
        $news_ids = array_map('intval', explode(',', $data['news']));
      }

      $limit = (int)($data['limit'] ?? 10);

      $data['news'] = NewsModel::list(['ids' => $news_ids,'status' => 1,'limit' => $limit]);

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

        return \Theme::view('components.module.news', $data);
    }
}
