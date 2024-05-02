<?php

namespace Modules\News\View\Components\dashboard;

use App\Contracts\extension\dashboard\DashboardComponent;

use Modules\News\Models\News;

class NewsTotal extends DashboardComponent
{
    protected $data =array();
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($data=array())
    {
      $data['title'] = __('Total News');
      $data['total']  = News::getTotal();
      $data['see_more'] = route('admin.news');

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

        return view('components.dashboard.news.news-total', $data);
    }
}
