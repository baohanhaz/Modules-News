<?php
namespace Modules\News\Database\Seeders;

use Illuminate\Database\Seeder;

class NewsToCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      // $faker = \Faker\Factory::create();
      // $news_ids = \Modules\News\Models\News::all()->pluck('id')->toArray();
      // $news_category_ids = \Modules\News\Models\NewsCategory::all()->pluck('id')->toArray();
      //
      //
      // for ($i=0; $i < 20; $i++) {
      //   $news_id = $faker->randomElement($news_ids);
      //   $news_category_id = $faker->randomElement($news_category_ids);
      //   $query = \Modules\News\Models\NewsToCategory::where('news_id',$news_id)
      //                                                       ->where('nc_id',$news_category_id)->first();
      //   if (!$query) {
      //     $model = new \Modules\News\Models\NewsToCategory;
      //     $model->news_id = $news_id;
      //     $model->nc_id = $news_category_id;
      //     $model->save();
      //   }
      // }
    }
}
