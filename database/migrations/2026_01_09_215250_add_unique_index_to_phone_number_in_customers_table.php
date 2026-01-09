<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, clean up duplicate phone numbers by keeping only the oldest customer (lowest id)
        // and deleting newer duplicates that have balance = 0 and cashback = 0
        DB::statement("
            DELETE c1 FROM customers c1
            INNER JOIN customers c2
            WHERE c1.phone_number = c2.phone_number
              AND c1.id > c2.id
              AND c1.balance = 0
              AND c1.cashback = 0
        ");

        // For duplicates with balance > 0, we need to handle them differently
        // Get all duplicate phone numbers that still exist after cleaning zero-balance accounts
        $duplicates = DB::select("
            SELECT phone_number, COUNT(*) as count, GROUP_CONCAT(id ORDER BY id) as ids
            FROM customers
            GROUP BY phone_number
            HAVING count > 1
        ");

        // For each duplicate group, keep the one with the highest balance or oldest account
        foreach ($duplicates as $duplicate) {
            $ids = explode(',', $duplicate->ids);

            // Get all customers with this phone number ordered by balance desc, then by id asc
            $customers = DB::table('customers')
                ->whereIn('id', $ids)
                ->orderByDesc('balance')
                ->orderByDesc('cashback')
                ->orderBy('id')
                ->get();

            // Keep the first one (highest balance/cashback or oldest), delete the rest
            $keepId = $customers->first()->id;
            $deleteIds = array_filter($ids, fn($id) => $id != $keepId);

            if (!empty($deleteIds)) {
                // Log what we're deleting for safety
                \Log::warning('Deleting duplicate customers with phone: ' . $duplicate->phone_number . ', keeping ID: ' . $keepId . ', deleting IDs: ' . implode(',', $deleteIds));

                DB::table('customers')->whereIn('id', $deleteIds)->delete();
            }
        }

        // Now add the unique constraint
        Schema::table('customers', function (Blueprint $table) {
            $table->unique('phone_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropUnique(['phone_number']);
        });
    }
};
