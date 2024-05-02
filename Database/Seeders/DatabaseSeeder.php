<?php

namespace Modules\News\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \File::copyDirectory(base_path('Modules/News/assets/demo/image'), \Storage::disk(config('lfm.disk'))->path(config('lfm.folder_categories')['image']['folder_name'] . '/shares/demo'));
        $this->call(NewsCategorySeeder::class);
        $this->call(NewsSeeder::class);
        // $this->call(HashTagSeeder::class);
    }
}
