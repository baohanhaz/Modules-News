<?php

namespace Modules\News\View\Components\module;

use App\Contracts\extension\module\ModuleComponent;

use Modules\News\Models\News;

class NewsLastest extends ModuleComponent
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($data=array())
    {
      $limit = $data['limit'] ?? 10;
      $data['newss'] = array();
      $newss = News::list(['limit' => $limit,'order' => 'created_at', 'sort' => 'desc','status' => 1]);
      // foreach ($newss as $key => $news) {
      //   $tmp = new \Modules\News\Http\Resources\News($news);
      //   $tmp['link'] = route('news.info', $news['slug']);
      //   $data['newss'][] = $tmp;
      // }
      $data['newss'] = json_decode(json_encode(\Modules\News\Http\Resources\News::collection($newss)), true);
      // print_r($data['newss']);
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

        return \Theme::view('components.module.news-lastest', $data);
    }
}
