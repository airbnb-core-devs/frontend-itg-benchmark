<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Doomus\Cart;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cart1 = new Cart();
        $cart2 = new Cart();
        $cart1->save();
        $cart2->save();

        DB::table('roles')->insert([
            'name' => 'client',
        ]);
        DB::table('roles')->insert([
            'name' => 'employee',
        ]);

        DB::table('users')->insert([
            'image' => 'user-placeholder.jpg',
            'name' => 'Gabriel',
            'email' => 'gabriel'.'@doomus.com',
            'password' => bcrypt('secret'),
            'cart_id' => 1,
            'role_id' => 2,
        ]);
        
        DB::table('users')->insert([
            'image' => 'user-placeholder.jpg',
            'name' => 'Geovanne',
            'email' => 'geovanne'.'@doomus.com',
            'password' => bcrypt('secret'),
            'cart_id' => 2,
            'role_id' => 1,
        ]);
        
        DB::table('categories')->insert([
            'name' => 'Cama',
        ]);
        
        DB::table('categories')->insert([
            'name' => 'Mesa',
        ]);
        
        DB::table('categories')->insert([
            'name' => 'Banho',
        ]);
        
        DB::table('payment_methods')->insert([
            'name' => 'paypal',
        ]);

        DB::table('products')->insert([
            'name' => 'Toalha de rosto',
            'details' => 'Pano macio para secar o rosto',
            'description' => 'Produto made in Taiwan, de ótima qualidade e resistência. É excelente por sua longevidade',
            'qtd_last' => 4,
            'weight' => 20.3,
            'width' => 50.0,
            'height' => 20.0,
            'category_id' => 3,
        ]);
        
        DB::table('products')->insert([
            'name' => 'Endredom',
            'details' => 'Macio e quente',
            'description' => 'Com ótimo material, é excelente para esquentar sua noite',
            'qtd_last' => 33,
            'weight' => 466.2,
            'width' => 200.0,
            'height' => 150.0,
            'category_id' => 1,
        ]);
        
        DB::table('products')->insert([
            'name' => 'Travesseiro',
            'details' => Str::random(10),
            'description' => Str::random(10),
            'qtd_last' => 1,
            'weight' => 250.0,
            'width' => 45.0,
            'height' => 25.0,
            'category_id' => 1,
        ]);
        
        DB::table('products')->insert([
            'name' => 'Colher de silicone',
            'details' => Str::random(10),
            'description' => Str::random(10),
            'qtd_last' => 37,
            'weight' => 125.0,
            'width' => 20.0,
            'height' => 2.0,
            'category_id' => 2,
        ]);
        
        DB::table('products')->insert([
            'name' => Str::random(10),
            'details' => Str::random(10),
            'description' => Str::random(10),
            'qtd_last' => 3,
            'weight' => 2000.0,
            'width' => 100.0,
            'height' => 200.0,
            'category_id' => 2,
        ]);

        DB::table('historic_statuses')->insert([
            'name' => 'deny',
        ]);

        DB::table('historic_statuses')->insert([
            'name' => 'ok',
        ]);

        DB::table('historic_statuses')->insert([
            'name' => 'cancelled',
        ]);

        DB::table('historics')->insert([
            'user_id' => 1,
            'product_id' => 2,
            'status_id' => 2,
        ]);

        DB::table('historics')->insert([
            'user_id' => 2,
            'product_id' => 3,
            'status_id' => 1,
        ]);

        DB::table('historics')->insert([
            'user_id' => 1,
            'product_id' => 4,
            'status_id' => 3,
        ]);
    }
}
