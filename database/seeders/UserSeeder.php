<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
            User::create([
                'id' => '1',
                'email' => 'admin@bpi.ui.ac.id',
                'password' => Hash::make('admin123$'),
                'role_id' => 3,
                'status' => 1,
            ]);

            User::create([
                'id' => '2',
                'email' => 'kukuh@bpi.ui.ac.id',
                'password' => Hash::make('kukuh123$'),
                'role_id' => 2,
                'status' => 1,
            ]);

            User::create([
                'id' => '3',
                'email' => 'nirwana@bpi.ui.ac.id',
                'password' => Hash::make('nirwana123$'),
                'role_id' => 1,
                'status' => 1,
            ]);

            User::create([
                'id' => '4',
                'email' => 'adi@bpi.ui.ac.id',
                'password' => Hash::make('adi123$'),
                'role_id' => 2,
                'status' => 0,
            ]);

            User::create([
                'id' => '5',
                'email' => 'ananta@bpi.ui.ac.id',
                'password' => Hash::make('ananta123$'),
                'role_id' => 1,
                'status' => 0,
            ]);

            User::create([
                'id' => '6',
                'email' => 'timotius@bpi.ui.ac.id',
                'password' => Hash::make('timotius123$'),
                'role_id' => 1,
                'status' => 1,
            ]);

            User::create([
                'id' => '7',
                'email' => 'dony@bpi.ui.ac.id',
                'password' => Hash::make('dony123$'),
                'role_id' => 2,
                'status' => 0,
            ]);

            User::create([
                'id' => '8',
                'email' => 'endina@bpi.ui.ac.id',
                'password' => Hash::make('endina123$'),
                'role_id' => 1,
                'status' => 1,
            ]);

            User::create([
                'id' => '9',
                'email' => 'indra@bpi.ui.ac.id',
                'password' => Hash::make('indra123$'),
                'role_id' => 2,
                'status' => 0,
            ]);

            User::create([
                'id' => '10',
                'email' => 'mubarik@bpi.ui.ac.id',
                'password' => Hash::make('mubarik123$'),
                'role_id' => 1,
                'status' => 1,
            ]);

    }
}
