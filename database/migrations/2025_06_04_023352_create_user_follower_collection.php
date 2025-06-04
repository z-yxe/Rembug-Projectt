<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
// Tidak perlu Blueprint untuk struktur collection MongoDB secara detail di sini
// Namun, kita bisa menggunakan Schema builder dari package mongodb untuk membuat index
use Illuminate\Support\Facades\DB;


return new class extends Migration
{
    /**
     * The name of the database connection to use.
     *
     * @var string
     */
    protected $connection = 'mongodb';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $collectionName = 'user_follower';

        // Membuat collection jika belum ada (opsional, Eloquent bisa membuatnya saat pertama kali digunakan)
        if (!Schema::connection($this->connection)->hasCollection($collectionName)) {
            DB::connection($this->connection)->getMongoDB()->createCollection($collectionName);
        }

        // Membuat index untuk efisiensi query dan memastikan keunikan
        Schema::connection($this->connection)->collection($collectionName, function ($collection) {
            // Index compound unik untuk mencegah duplikasi follow
            $collection->unique(['user_id', 'following_user_id']);
            // Index terpisah untuk query yang lebih cepat berdasarkan salah satu field
            $collection->index('user_id');
            $collection->index('following_user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection($this->connection)->dropIfExists('user_follower');
    }
};
