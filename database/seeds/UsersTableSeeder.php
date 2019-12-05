<?php

use Illuminate\Database\Seeder;
use App\User; //import model app/user.php

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'admin mag',
            'email' => 'admin@mag.com',
            'password' => bcrypt('secret'),
        ]);
    }
}
