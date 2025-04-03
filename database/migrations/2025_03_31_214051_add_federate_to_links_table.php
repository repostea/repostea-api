<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('links', function (Blueprint $table) {
            $table->boolean('federated')->default(false)->after('metadata');
            $table->timestamp('federated_at')->nullable()->after('federated');
        });

    }

    public function down()
    {
        Schema::table('links', function (Blueprint $table) {
            $table->dropColumn(['federated', 'federated_at']);
        });
    }
};
