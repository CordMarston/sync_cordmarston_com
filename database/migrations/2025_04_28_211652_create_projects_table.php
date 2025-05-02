<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Create the 'projects' table
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->longText('notes')->nullable();
            $table->timestamps();
        });

        // Create the 'project_connections' table
        Schema::create('project_connections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('host');
            $table->string('port')->nullable();
            $table->string('username')->nullable();
            $table->string('password')->nullable();
            $table->timestamps();
        });

        // Create the 'databases' table
        Schema::create('databases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_connection_id')->constrained('project_connections')->onDelete('cascade');
            $table->string('name');
            $table->string('collation')->nullable();
            $table->string('character_set')->nullable();
            $table->text('comment')->nullable();
            $table->timestamps();
        });

        // Create the 'database_tables' table
        Schema::create('database_tables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('connection_database_id')->constrained('databases')->onDelete('cascade');
            $table->string('name');
            $table->string('engine')->nullable();
            $table->string('table_type')->nullable(); // BASE TABLE or VIEW
            $table->bigInteger('table_rows')->nullable();
            $table->bigInteger('data_length')->nullable();
            $table->bigInteger('index_length')->nullable();
            $table->dateTime('create_time')->nullable();
            $table->dateTime('update_time')->nullable();
            $table->string('collation')->nullable();
            $table->text('comment')->nullable();
            $table->timestamps();
        });

        // Create the 'table_columns' table
        Schema::create('table_columns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('database_table_id')->constrained('database_tables')->onDelete('cascade');
            $table->string('name');
            $table->string('data_type')->nullable();
            $table->string('column_type')->nullable(); // e.g., int(11), varchar(255)
            $table->boolean('is_nullable')->default(false);
            $table->text('default')->nullable();
            $table->boolean('is_primary')->default(false);
            $table->boolean('is_unique')->default(false);
            $table->boolean('is_auto_increment')->default(false);
            $table->string('collation')->nullable();
            $table->text('comment')->nullable();
            $table->string('extra')->nullable(); // e.g., 'on update CURRENT_TIMESTAMP'
            $table->timestamps();
        });
    }

    public function down(): void
    {
        // Dropping tables in reverse order of creation to respect foreign key constraints
        Schema::dropIfExists('table_columns');
        Schema::dropIfExists('database_tables');
        Schema::dropIfExists('databases');
        Schema::dropIfExists('project_connections');
        Schema::dropIfExists('projects');
    }
};
