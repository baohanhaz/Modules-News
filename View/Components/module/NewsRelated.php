<?php

namespace Modules\News\View\Components\module;

use App\Contracts\extension\module\ModuleComponent;

use Modules\News\Models\News;

class NewsRelated extends ModuleComponent
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($data=array())
    {
      $data['newss'] = array();
      if(!empty($data['news_id'])){
        $info = \Modules\News\Models\News::find($data['news_id']);
        if($info){
          foreach ($info->getCategories() as $category) {
            foreach ($category->getNews() as $news) {
              if ($news->id != $info->id) {
                $data['newss'][] = json_decode(json_encode(\Modules\News\Http\Resources\News::make($news)),true);
              }
            }
          }
        }
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
        
        return \Theme::view('components.module.news-related', $data);
    }
}
