<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
// 下記追加
use Carbon\Carbon;
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
            [
                'over_name'       => '山田',
                'under_name'      => '太郎',
                'over_name_kana'  => 'ヤマダ',
                'under_name_kana' => 'タロウ',
                'mail_address'    => 'yamada@example.com',
                'sex'             => 1, // 1: 男性
                'birth_day'       => '1990-05-15',
                'role'            => 1, // 1: 教師（国語）
                'password'        => Hash::make('password123'),
                'remember_token'  => null,
                'created_at'      => Carbon::now(),
                'updated_at'      => Carbon::now(),
            ],
            [
                'over_name'       => '佐藤',
                'under_name'      => '花子',
                'over_name_kana'  => 'サトウ',
                'under_name_kana' => 'ハナコ',
                'mail_address'    => 'satou@example.com',
                'sex'             => 2, // 2: 女性
                'birth_day'       => '1995-10-20',
                'role'            => 4, // 4: 生徒
                'password'        => Hash::make('hanako2024'),
                'remember_token'  => null,
                'created_at'      => Carbon::now(),
                'updated_at'      => Carbon::now(),
            ],
        ]);
    }
}
