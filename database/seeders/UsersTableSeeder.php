<?php
namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
        'over_name' =>'鈴木',
        'under_name' =>'りお',
        'over_name_kana' =>'スズキ',
        'under_name_kana' =>'リオ',
        'mail_address' => 'rio@dog.com',
        'sex' => '1',
        'birth_day' => '2011-9-21',
        'role' => '2',
        'password' => Hash::make('rio0921'),
      ]);

    }
}
