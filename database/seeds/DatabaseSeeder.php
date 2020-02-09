<?php

use Illuminate\Database\Seeder;
use Faker\Generator as Faker;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(Faker $faker, App\Topic $topic, App\Post $post)
    {
        //$this->call(UsersTableSeeder::class);
        /*
        DB::table('users')->insert([
            'name' => str_random(10),
            'email' => str_random(10).'@gmail.com',
            'password' => bcrypt('secret'),
        ]);
        */
        /*
        foreach(range(1, 10) as $i) {
            $topic->create([
                'title' => $faker->sentence(),
                'user_id' => 1
            ]);
        }
        foreach(range(1, 10) as $i) {
            $topic->create([
                'title' => $faker->sentence(),
                'user_id' => 2
            ]);
        }
        */

        foreach(range(1, 10) as $i) {
            $post->create([
                'topic_id' => 1,
                'body' => $faker->sentence(),
                'user_id' => 1
            ]);
        }
    }
}
