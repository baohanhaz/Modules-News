<?php

namespace App\Http\Requests\api\news;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Factory as ValidationFactory;

class NewsCategory extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $news_category_id = $this->route('news_category_id');
        $this->merge(['id'   =>  $news_category_id]);
        if (!empty(request()->get('slug'))) {
          $this->merge(['slug'   =>  \Str::slug(request()->get('slug'))]);
        }else{
          $slug = \Str::slug(request()->get('translation')[config('app.fallback_locale')]['name'] ?? '') . '-' . \Str::uuid();
          $this->merge(['slug'   =>  $slug]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $news_category_id = $this->route('news_category_id');
        $rules = [];
        $rules = [];
        switch ($this->route()->getName()) {
          case 'api.user.news.category.add':
            $rules['slug'] = ['string', 'max:255', new \Modules\News\Rules\NewsCategorySeoUrl($news_category_id)];
            $rules['translation.' . config('app.fallback_locale') . '.name'] = ['required', 'min:3', 'max:255'];
            break;
          case 'api.user.news.category.update':
            $rules['id'] = ['required', 'exists:' . (new \Modules\News\Models\NewsCategory)->getTable() . ',id'];
            if (request()->has('slug')) {
              $rules['slug'] = ['string', 'max:255', new \Modules\News\Rules\NewsCategorySeoUrl($news_category_id)];
            }
            if (request()->has('translation')) {
              $rules['translation.' . config('app.fallback_locale') . '.name'] = ['required', 'min:3', 'max:255'];
            }
            break;
        }
        return $rules;
    }

    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(response()->json(['errors' => $validator->errors()], 200));
    }
}
