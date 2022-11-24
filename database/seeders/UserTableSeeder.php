<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        self::checkIssetBeforeCreate([
            'user_name' => 'admin',
            'full_name' => 'Super Admin',
            'email' => 'superadmin@st.vn',
            'is_super_admin' => true,
            'password' => Hash::make('123456aA@'),
        ]);
    }

    private function checkIssetBeforeCreate($data) {
        $admin = User::where('email', $data['email'])->first();
        if (empty($admin)) {
            User::create($data);
        } else {
            $admin->update($data);
        }
    }
}
