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
        $tables = [
            'users', 'links', 'comments', 'tags', 'votes',
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->string('tenant_id')->nullable()->after('id');
                $table->index('tenant_id');
            });
        }

        Schema::table('link_tag', function (Blueprint $table) {
            $table->string('tenant_id')->nullable();
            $table->index('tenant_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'users', 'links', 'comments', 'tags', 'votes', 'link_tag',
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->dropIndex(['tenant_id']);
                $table->dropColumn('tenant_id');
            });
        }
    }
};
