<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HostsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('hosts')->insert([
            ['host_name' => '米田陸人', 'slack_id' => 'U07CZAQLV25'],
            ['host_name' => '魚住紘平', 'slack_id' => 'U07D8K67E8Y'],
            ['host_name' => '土居健太郎', 'slack_id' => 'U07D0F7A797'],
        ]);
    }
}
