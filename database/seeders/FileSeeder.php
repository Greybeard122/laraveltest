<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\File;

class FileSeeder extends Seeder
{
    public function run()
    {
        $files = [
            ['file_name' => 'COR', 'is_available' => true],
            ['file_name' => 'COG', 'is_available' => true],
        ];

        foreach ($files as $file) {
            File::updateOrCreate(['file_name' => $file['file_name']], $file);
        }
    }
}

