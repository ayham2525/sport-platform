<?php
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
   public function up(): void
{
    Schema::table('payments', function (Blueprint $table) {
        // Drop existing FKs safely
      //  DB::statement('ALTER TABLE payments DROP FOREIGN KEY payments_player_id_foreign');
    // DB::statement('ALTER TABLE payments DROP FOREIGN KEY payments_program_id_foreign');
      //  DB::statement('ALTER TABLE payments DROP FOREIGN KEY payments_branch_id_foreign');
       // DB::statement('ALTER TABLE payments DROP FOREIGN KEY payments_academy_id_foreign');
    });

    Schema::table('payments', function (Blueprint $table) {
        $table->unsignedBigInteger('player_id')->nullable()->change();
        $table->unsignedBigInteger('program_id')->nullable()->change();
        $table->unsignedBigInteger('branch_id')->nullable()->change();
        $table->unsignedBigInteger('academy_id')->nullable()->change();
        $table->unsignedInteger('class_count')->nullable()->change();
        $table->text('items')->nullable()->change();
    });

    Schema::table('payments', function (Blueprint $table) {
        $table->foreign('player_id')->references('id')->on('players')->onDelete('cascade');
        $table->foreign('program_id')->references('id')->on('programs')->onDelete('set null');
        $table->foreign('branch_id')->references('id')->on('branches')->onDelete('set null');
        $table->foreign('academy_id')->references('id')->on('academies')->onDelete('set null');
    });
}




    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // Drop updated foreign keys
            $table->dropForeign(['player_id']);
            $table->dropForeign(['program_id']);
            $table->dropForeign(['branch_id']);
            $table->dropForeign(['academy_id']);

            // Revert columns to NOT NULL (adjust default values as needed)
            $table->unsignedBigInteger('player_id')->nullable(false)->change();
            $table->unsignedBigInteger('program_id')->nullable(false)->change();
            $table->unsignedBigInteger('branch_id')->nullable(false)->change();
            $table->unsignedBigInteger('academy_id')->nullable(false)->change();
            $table->unsignedInteger('class_count')->nullable(false)->default(0)->change();
            $table->text('items')->nullable(false)->change();

            // Re-add original foreign keys
            $table->foreign('player_id')->references('id')->on('players')->onDelete('cascade');
            $table->foreign('program_id')->references('id')->on('programs')->onDelete('cascade');
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('set null');
            $table->foreign('academy_id')->references('id')->on('academies')->onDelete('set null');
        });
    }
};
