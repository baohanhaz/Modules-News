<?php
namespace Modules\News\Database\Seeders;

use Illuminate\Database\Seeder;

class NewsCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $categories = array(
        array(
          'name'    =>    'Thời sự',
          'children' =>    array(
            array('name'  =>  'Chính trị'),
            array('name'  =>  'Giao thông'),
          )
        ),
        array(
          'name'    =>    'Góc nhìn'
        ),
        array(
          'name'    =>    'Thế giới',
          'children' =>    array(
            array('name'  =>  'Bầu cử Mỹ'),
            array('name'  =>  'Quân sự'),
          )
        ),
      );
      foreach ($categories as $category) {
        $new_cat = \Modules\News\Models\NewsCategory::create([
          'name'  =>  $category['name'] ?? ''
        ]);
        if (!empty($category['children'])) {
          foreach ($category['children'] as $child) {
            $new_child = \Modules\News\Models\NewsCategory::create([
              'name'          =>  $child['name'] ?? '',
              'parent_id'     =>  $new_cat->id,
            ]);
          }
          \Modules\News\Models\NewsCategoryPath::create([
            'category_id'   =>  $new_child->id,
            'parent_id'     =>  $new_cat->id,
            'level'         =>  0
          ]);
        }
      }
      // \Modules\News\Models\NewsCategory::factory()
      //       ->count(3)
      //       ->create();
    }
}
