<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class NewsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\News\Models\News::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
      return [
        'title'             => $this->faker->text(),
        'short_description' => $this->faker->text(),
        'description' => $this->faker->randomHtml(2,3),
        'meta_title' => $this->faker->realText(),
        'meta_description' => $this->faker->realText(),
        'meta_keyword' => $this->faker->realText(),
        'image'             => $this->faker->imageUrl(500,500),
        'status'            => $this->faker->boolean,
      ];
    }
}
