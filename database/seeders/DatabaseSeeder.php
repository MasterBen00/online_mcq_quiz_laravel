<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\Score;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        DB::table('users')->insert([
            'id' => 1,
            'name'=>'beni',
            'email'=>'itsbaniamin@gmail.com',
            'password'=>Hash::make('12341234'),
            'type' => 1,
            'teachers_code' => 'code10',
            'email_verified_at' => null,
            'remember_token' => null,
        ]);

        //User::factory(10)->create();
        Category::factory(6)->create();
        //Quiz::factory(100)->create();
        //Question::factory(1000)->create();
        //Score::factory(10)->create();
    }
}
