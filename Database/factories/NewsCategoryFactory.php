<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class NewsCategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\News\Models\NewsCategory::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
      return [
        'name'        => $this->faker->text(),
        'image'       => $this->faker->imageUrl(1500,500),
        'description' => $this->faker->randomHtml(2,3),
        'meta_title' => $this->faker->realText(),
        'meta_description' => $this->faker->realText(),
        'meta_keyword' => $this->faker->realText(),
        'status'      => $this->faker->boolean,
      ];
    }
}
