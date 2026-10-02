<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
  public function up()
{
    Schema::table('projects', function (Blueprint $table) {
        $table->string('contact_name')->nullable()->after('description');
        $table->string('contact_email')->nullable()->after('contact_name');
        $table->string('contact_phone')->nullable()->after('contact_email');
    });
}

public function down()
{
    Schema::table('projects', function (Blueprint $table) {
        $table->dropColumn(['contact_name', 'contact_email', 'contact_phone']);
    });
}
};
