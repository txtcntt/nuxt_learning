<?php

namespace Database\Seeders;

use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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
        $faker = Factory::create('vi_VN');
        $groupRoles = ['Admin', 'Editor', 'Reviewer'];
        $charName = chr(rand(97, 122));
        $index = 1;
        $products = [];
        $customers = [];


        //Generator users
        $userLastNames = ['trinh' => 'Trịnh Quang', 'nguyen' => 'Nguyễn Văn', 'tran' => 'Trần Quốc'];
        foreach ($userLastNames as $key => $lastName) {
            for ($i = 97; $i <= 122; $i++) {
                $charName = chr($i);
                //Generator users
                DB::table('mst_users')->insert(
                    [
                        'name' => $lastName . ' ' . strtoupper($charName),
                        'email' => $charName . '.' . $key . '@gmail.com',
                        'password' => Hash::make('Tt123456@'),
                        'remember_token' => Str::random(10),
                        'is_active' => rand(0, 1),
                        'group_role' => $groupRoles[array_rand($groupRoles, 1)],
                        'last_login_at' => now(),
                        'last_login_ip' => $faker->ipv4,
                        'created_at' => now()->addHour(rand(1,23))->addMinute(rand(1,59))->addSecond(rand(1,59)),
                    ]
                );
            }
        }       

        //Generator products
        for ($i = 97; $i <= 122; $i++) {
            $charName = chr($i);
            $productInfo = [
                'product_id' => 'S' . str_pad($index, 10, '0', STR_PAD_LEFT),
                'product_name' => 'Sản phẩm ' . strtoupper($charName),
                'description' => $faker->text(255), // 'Chức năng sản phẩm ' . strtoupper($charName),
                'product_price' => rand(10, 1000),
                'product_image' => '',
                'is_sales' => rand(0, 2), //0: top sales, 1: sales, 2: quantity = 0
                'created_at' => now()->addHour(rand(1,23))->addMinute(rand(1,59))->addSecond(rand(1,59)),
            ];
            DB::table('mst_products')->insert($productInfo);
            $products[] = $productInfo;
            $index++;
        }
    }
}
