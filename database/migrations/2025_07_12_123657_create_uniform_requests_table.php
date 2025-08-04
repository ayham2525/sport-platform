<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUniformRequestsTable extends Migration
{
    public function up()
    {
        Schema::create('uniform_requests', function (Blueprint $table) {
            $table->id();

            // Foreign keys
            $table->foreignId('player_id')->constrained('players')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('item_id')->constrained('items')->onDelete('cascade');
            $table->foreignId('system_id')->constrained('systems')->onDelete('cascade');
            $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('set null');
            $table->foreignId('currency_id')->nullable()->constrained('currencies')->onDelete('set null');

            // Uniform details
            $table->string('size', 50)->nullable();
            $table->string('color', 50)->nullable();
            $table->unsignedInteger('quantity')->default(1);
            $table->decimal('amount', 10, 2)->default(0.00);

            // Status tracking
            $table->enum('status', [
                'requested',
                'approved',
                'ordered',
                'delivered',
                'rejected',
                'cancelled',
                'returned'
            ])->default('requested');

            $table->date('request_date');
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('ordered_at')->nullable();
            $table->timestamp('delivered_at')->nullable();

            // Notes
            $table->text('notes')->nullable();
            $table->text('admin_remarks')->nullable();

            $table->timestamps();

            // Optional index
            $table->index(['player_id', 'item_id']);
            $table->index('status');
        });
    }

    public function down()
    {
        Schema::dropIfExists('uniform_requests');
    }
}
