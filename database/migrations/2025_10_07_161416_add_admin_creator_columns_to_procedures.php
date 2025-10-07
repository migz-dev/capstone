<?php
// database/migrations/2025_10_08_001000_add_admin_creator_columns_to_procedures.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('procedures', function (Blueprint $table) {
            // match admins.id type (INT UNSIGNED)
            $table->unsignedInteger('created_by_admin')->nullable()->after('created_by');
            $table->unsignedInteger('updated_by_admin')->nullable()->after('updated_by');

            // FKs to admins (PLURAL)
            $table->foreign('created_by_admin','fk_procedures_created_by_admin')
                  ->references('id')->on('admins')->nullOnDelete();
            $table->foreign('updated_by_admin','fk_procedures_updated_by_admin')
                  ->references('id')->on('admins')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('procedures', function (Blueprint $table) {
            $table->dropForeign('fk_procedures_created_by_admin');
            $table->dropForeign('fk_procedures_updated_by_admin');
            $table->dropColumn(['created_by_admin','updated_by_admin']);
        });
    }
};

