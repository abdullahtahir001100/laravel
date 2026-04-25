<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('display_name')->nullable()->after('last_name');
            $table->string('username')->nullable()->unique()->after('display_name');
            $table->string('pronouns')->nullable()->after('username');
            $table->string('phone')->nullable()->after('email');
            $table->string('country')->nullable()->after('phone');
            $table->string('city')->nullable()->after('country');
            $table->string('headline')->nullable()->after('city');
            $table->text('about')->nullable()->after('headline');
            $table->text('bio')->nullable()->after('about');
            $table->string('website')->nullable()->after('bio');
            $table->string('avatar_path')->nullable()->after('website');
            $table->string('cover_photo_path')->nullable()->after('avatar_path');
            $table->json('settings')->nullable()->after('cover_photo_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['username']);
            $table->dropColumn([
                'display_name',
                'username',
                'pronouns',
                'phone',
                'country',
                'city',
                'headline',
                'about',
                'bio',
                'website',
                'avatar_path',
                'cover_photo_path',
                'settings',
            ]);
        });
    }
};
