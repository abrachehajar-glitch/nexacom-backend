<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            
            // Changement ici : 'set null' au lieu de 'cascade'
            $table->foreignId('user_id')
                  ->nullable() 
                  ->constrained()
                  ->onDelete('set null'); 

            $table->string('title');
            $table->text('description');

            // Informations de contact (essentielles pour identifier le projet si user_id est null)
            $table->string('contact_name')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();

            $table->string('audio_path')->nullable(); 
            $table->string('file_path')->nullable();  
            $table->string('work_file_path')->nullable(); 
            $table->decimal('price', 10, 2)->default(0);
            $table->string('status')->default('pending'); 
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};