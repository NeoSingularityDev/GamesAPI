<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Developer;
use App\Models\Game;

class DeveloperGameSeeder extends Seeder
{
    public function run()
    {
        $developers = [
            ['name' => 'Nintendo', 'country' => 'Japan'],
            ['name' => 'Valve', 'country' => 'USA'],
            ['name' => 'Rockstar Games', 'country' => 'USA'],
            ['name' => 'CD Projekt Red', 'country' => 'Poland'],
            ['name' => 'Square Enix', 'country' => 'Japan'],
        ];

        foreach ($developers as $dev) {
            Developer::create($dev);
        }

        $games = [
            ['title' => 'The Legend of Zelda', 'developer' => 'Nintendo'],
            ['title' => 'Super Mario Odyssey', 'developer' => 'Nintendo'],
            ['title' => 'Half-Life 2', 'developer' => 'Valve'],
            ['title' => 'Portal 2', 'developer' => 'Valve'],
            ['title' => 'Counter-Strike 1.6', 'developer' => 'Valve'],
            ['title' => 'Red Dead Redemption 2', 'developer' => 'Rockstar Games'],
            ['title' => 'GTA V', 'developer' => 'Rockstar Games'],
            ['title' => 'Bully', 'developer' => 'Rockstar Games'],
            ['title' => 'The Witcher 3', 'developer' => 'CD Projekt Red'],
            ['title' => 'Cyberpunk 2077', 'developer' => 'CD Projekt Red'],
            ['title' => 'Final Fantasy VII', 'developer' => 'Square Enix'],
            ['title' => 'Final Fantasy XIV', 'developer' => 'Square Enix'],
            ['title' => 'Kingdom Hearts', 'developer' => 'Square Enix'],
            ['title' => 'Metroid Prime', 'developer' => 'Nintendo'],
            ['title' => 'Team Fortress 2', 'developer' => 'Valve'],
        ];

        foreach ($games as $g) {
            $dev = Developer::where('name', $g['developer'])->first();

            Game::create([
                'title' => $g['title'],
                'description' => $g['title'] . ' description...',
                'year' => rand(1990, 2023),
                'developer_id' => $dev->id
            ]);
        }
    }
}