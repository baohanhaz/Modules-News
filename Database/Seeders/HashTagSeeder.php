<?php
namespace Modules\News\Database\Seeders;

use Illuminate\Database\Seeder;

class HashTagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $hashtags = array(
        array('title' => 'Mẹo không phải ai cũng biết'),
        array('title' => 'Thế giới phụ kiện'),
        array('title' => 'Thế giới đồng hồ'),
        array('title' => 'Mang cả thế giới về nhà'),
      );
      foreach ($hashtags as $hashtag) {
        \Modules\News\Models\HashTag::create($hashtag);
      }
    }
}
