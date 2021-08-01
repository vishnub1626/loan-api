<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class);
            $table->unsignedBigInteger('amount');
            $table->enum('status', ['pending', 'approved', 'rejected', 'closed'])->default('pending');
            $table->unsignedMediumInteger('loan_term_in_weeks');
            $table->float('interest_rate', 4, 2)->nullable();
            $table->unsignedBigInteger('weekly_installment_amount')->nullable();
            $table->unsignedMediumInteger('installments_remaining')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->unsignedBigInteger('rejected_by')->nullable();
            $table->text('reason_for_rejection')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('loans');
    }
}
