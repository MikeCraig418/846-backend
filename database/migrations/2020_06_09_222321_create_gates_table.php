<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateGatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableNames = config('nova-permissions.table_names');

        Schema::create($tableNames['roles'], function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('slug', 255);
            $table->string('name', 255)->nullable();
            $table->nullableTimestamps();
        });

        Schema::create($tableNames['role_permission'], function (Blueprint $table) use ($tableNames) {
            $table->unsignedBigInteger('role_id');
            $table->string('permission_slug', 255);
            $table->nullableTimestamps();

            $table->primary(['role_id', 'permission_slug']);

            $table->foreign(['role_id', 'role_id', 'role_id', 'role_id', 'role_id'], 'role_permission_role_id_foreign')->references(['id', 'id', 'id', 'id', 'id'])->on('roles')->onDelete('CASCADE
')->onUpdate('RESTRICT');

        });

        Schema::create($tableNames['role_user'], function (Blueprint $table) use ($tableNames) {
            $table->unsignedBigInteger('role_id');
            $table->string('user_id', 36)->default('');
            $table->nullableTimestamps();



            $table->foreign(['role_id', 'role_id', 'role_id', 'role_id', 'role_id'], 'role_user_role_id_foreign')->references(['id', 'id', 'id', 'id', 'id'])->on('roles')->onDelete('CASCADE
')->onUpdate('RESTRICT');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $tableNames = config('nova-permissions.table_names');

        Schema::dropIfExists($tableNames['role_permission']);
        Schema::dropIfExists($tableNames['role_user']);
        Schema::dropIfExists($tableNames['roles']);
    }
}
