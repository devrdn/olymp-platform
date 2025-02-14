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
        Schema::table('model_has_roles', function (Blueprint $table) {
            $table->foreignId('contest_id')
                ->nullable()
                ->constrained()
                ->cascadeOnDelete()
                ->after('role_id');

            $table->primary(['role_id', 'model_id', 'model_type', 'contest_id'], 'model_has_roles_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('model_has_roles', function (Blueprint $table) {
            $table->dropColumn('contest_id');
        });
    }
};
