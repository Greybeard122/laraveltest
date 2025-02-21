<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\File;

class FilesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        File::create([
            'file_name' => 'COR',
            'is_available' => true,
        ]);

        File::create([
            'file_name' => 'TOR',
            'is_available' => true,
        ]);

        // Add other files
        File::create([
            'file_name' => 'PDF',
            'is_available' => true,
        ]);

        File::create([
            'file_name' => 'DOC',
            'is_available' => true,
        ]);

        File::create([
            'file_name' => 'XLS',
            'is_available' => true,
        ]);

        File::create([
            'file_name' => 'PPT',
            'is_available' => true,
        ]);
    }
}