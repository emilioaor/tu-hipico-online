<?php

use Illuminate\Database\Seeder;

use App\User;

class userSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Usuario administrador
        $user = new User();

        $user->username = 'admin';
        $user->name = 'Administrador';
        $user->password = bcrypt('123456');
        $user->level = User::LEVEL_ADMIN;
        $user->top_sale = 0;
        $user->print_code = '0000000';
        $user->save();

        //Usuario taquilla de prueba
        $user = new User();

        $user->username = 'taq1';
        $user->name = 'Taquilla 1';
        $user->password = bcrypt('123456');
        $user->level = User::LEVEL_USER;
        $user->top_sale = 0;
        $user->print_code = '1111111';
        $user->save();
    }
}
