<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // 追加
use Carbon\Carbon; //追加

class SubjectsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('subjects')->insert([
            [
                'subject'    => '国語',
                'created_at' => Carbon::now(),
            ],
            [
                'subject'    => '数学',
                'created_at' => Carbon::now(),
            ],
            [
                'subject'    => '英語',
                'created_at' => Carbon::now(),
            ],
        ]);
        // 国語、数学、英語を追加
    }
}
