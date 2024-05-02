<?php

namespace App\Http\Requests\api\news;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Factory as ValidationFactory;

class News extends FormRequest
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
        $news_id = $this->route('news_id');
        $this->merge(['id'   =>  $news_id]);

        if (!empty(request()->get('slug'))) {
          $this->merge(['slug'   =>  \Str::slug(request()->get('slug'))]);
        }else{
          $slug = \Str::slug(request()->get('translation')[config('app.fallback_locale')]['title'] ?? '') . '-' . \Str::uuid();
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
        $news_id = $this->route('news_id');
        $rules = [];
        switch ($this->route()->getName()) {
          case 'api.user.news.add':
            $rules['slug'] = ['string', 'max:255', new \Modules\News\Rules\NewsSeoUrl($news_id)];
            $rules['translation.' . config('app.fallback_locale') . '.title'] = ['required', 'min:3', 'max:255'];
            break;
          case 'api.user.news.update':
            $rules['id'] = ['required', 'exists:' . (new \Modules\News\Models\News)->getTable() . ',id'];
            if (request()->has('slug')) {
              $rules['slug'] = ['string', 'max:255', new \Modules\News\Rules\NewsSeoUrl($news_id)];
            }
            if (request()->has('translation')) {
              $rules['translation.' . config('app.fallback_locale') . '.title'] = ['required', 'min:3', 'max:255'];
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
