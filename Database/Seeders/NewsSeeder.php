<?php
namespace Modules\News\Database\Seeders;

use Illuminate\Database\Seeder;

class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      if (!\Modules\News\Models\News::count()) {
        $file = base_path('Modules/News/assets/demo/news.json');
        $categoryIds = \Modules\News\Models\NewsCategory::limit(3)->get()->pluck('id')->toArray();
        if (\File::exists($file)) {
          $newss = json_decode(\File::get($file), true);
          foreach ($newss as $news) {
            $new = \Modules\News\Models\News::create($news);
            foreach ($categoryIds as $categoryId) {
              $new->categories()->attach($categoryId);
            }
            
          }
        }
      }
      // \Modules\News\Models\News::factory()
      //       ->count(21)
      //       ->create();
    }
}
