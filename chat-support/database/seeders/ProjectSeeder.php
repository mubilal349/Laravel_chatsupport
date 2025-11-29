<?php

use Illuminate\Database\Seeder;
use App\Models\Project;

class ProjectSeeder extends Seeder
{
    public function run()
    {
        Project::create([
            'name' => 'Kitchen Renovation',
            'description' => 'Quartz countertop installed in a modern kitchen.',
            'image_url' => '/projects/alba2.webp'
        ]);

        Project::create([
            'name' => 'Office Lobby',
            'description' => 'Quartz flooring installed in a corporate office lobby.',
            'image_url' => '/projects/adira-bronze-1.webp'
        ]);
    }
}

