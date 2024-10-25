<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Task;

use Faker\Factory as Faker;


class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $faker = Faker::create();
        
        // Get all users
        User::factory()->create();
        $users = User::all();

        foreach ($users as $user) {
         
            for ($i = 0; $i < rand(1, 5); $i++) {
                $fakesStatus = $faker->randomElement(['to-do', 'in-progress', 'done']);
                $task = Task::create([
                    'title' => $faker->sentence,
                    'content' => $faker->paragraph,
                    'status' => $fakesStatus,
                    'user_id' => $user->id,
                ]);

                for ($j = 0; $j < rand(1, 5); $j++) {
                    Task::create([
                        'title' => $faker->sentence,
                        'content' => $faker->paragraph,
                        'status' => $fakesStatus,
                        'parent_id' => $task->id,
                        'user_id' => $user->id
                    ]);
                }
            }
        }
    }
}
