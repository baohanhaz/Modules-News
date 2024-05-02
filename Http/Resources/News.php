<?php

namespace Modules\News\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class News extends JsonResource
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
          'id'                              =>  $this->id,
          'title'                           =>  $this->title,
          'short_description'               =>  $this->short_description,
          'meta_title'    =>  $this->meta_title,
          'meta_description'  =>  $this->meta_description,
          'meta_keyword'  =>  $this->meta_keyword,
          'slug'                            =>  $this->slug,
          'link'                            =>  !empty($this->slug) ? url($this->slug) : '',
          // 'description'                     =>  $this->description,
          'image'                           =>  $this->image ? asset($this->image) : '',
          'thumbnail'                       =>  $this->image ? asset(\App\system\Image::resize($this->image, 400)) : '',
          'creator'                         =>  $this->getCreator()->name ?? '',
          'created_at'                      =>  (new \DateTime($this->created_at))->format('Y-m-d'),
        ];
    }
}
