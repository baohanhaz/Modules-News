<?php

namespace Modules\News\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NewsCategory extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
          'id'          =>  $this->id,
          'name'        =>  $this->name,
          'meta_title'    =>  $this->meta_title,
          'meta_description'  =>  $this->meta_description,
          'meta_keyword'  =>  $this->meta_keyword,
          'image'       =>  $this->image ? asset($this->image) : '',
          'link'        =>  !empty($this->slug) ? url($this->slug) : '',
        ];
    }
}
