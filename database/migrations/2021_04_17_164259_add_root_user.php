<?php

use Illuminate\Database\Migrations\Migration;

class AddRootUser extends Migration
{
    public const NAME_OF_USER_TABLE = 'users';
    public const ROOT_USER_EMAIL = 'imes.pro@ukrlogika.com';

    public function up(): void
    {
        DB::table(self::NAME_OF_USER_TABLE)
        ->insert([
            'email' => self::ROOT_USER_EMAIL,
            'name' => 'ROOT',
            'password' => bcrypt('secret00'),
        ]);
    }

    public function down(): void
    {
        DB::table(self::NAME_OF_USER_TABLE)
            ->where('email', self::ROOT_USER_EMAIL)
            ->delete();
    }
}
