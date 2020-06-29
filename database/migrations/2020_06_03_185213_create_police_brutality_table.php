<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePoliceBrutalityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('incidents', function (Blueprint $table) {
            $table->string('id', 36)->default('');
            $table->string('pb_id', 191)->nullable();
            $table->string('state', 191)->default('');
            $table->string('city', 191);
            $table->date('date')->nullable();
            $table->string('title', 191);
            $table->text('description')->nullable();
            $table->text('links')->nullable();
            $table->string('link_1', 191)->nullable();
            $table->string('link_2', 191)->nullable();
            $table->string('link_3', 191)->nullable();
            $table->string('link_4', 191)->nullable();
            $table->string('link_5', 191)->nullable();
            $table->string('link_6', 191)->nullable();
            $table->string('link_7', 191)->nullable();
            $table->text('data')->nullable();
            $table->decimal('lat', 10, 7)->default(0.0000000);
            $table->decimal('long', 10, 7)->default(0.0000000);
            $table->nullableTimestamps();
            $table->softDeletes();

            $table->primary('id');
        });


        Schema::create('evidence', function (Blueprint $table) {
            $table->string('id', 36)->default('');
            $table->string('incident_id', 36)->nullable();
            $table->text('url');
            $table->string('video_status', 191)->nullable();
            $table->nullableTimestamps();
            $table->softDeletes();

            $table->primary('id');
        });


        Schema::create('legislators', function (Blueprint $table) {
            $table->string('id', 255)->nullable();
            $table->string('chamber', 255)->nullable();
            $table->string('title', 255)->nullable();
            $table->string('short_title', 255)->nullable();
            $table->string('first_name', 255)->nullable();
            $table->string('middle_name', 255)->nullable();
            $table->string('last_name', 255)->nullable();
            $table->string('suffix', 255)->nullable();
            $table->string('date_of_birth', 255)->nullable();
            $table->string('gender', 255)->nullable();
            $table->string('party', 255)->nullable();
            $table->string('leadership_role', 255)->nullable();
            $table->string('twitter_account', 255)->nullable();
            $table->string('facebook_account', 255)->nullable();
            $table->string('youtube_account', 255)->nullable();
            $table->string('url', 255)->nullable();
            $table->string('contact_form', 255)->nullable();
            $table->string('last_updated', 255)->nullable();
            $table->string('office', 255)->nullable();
            $table->string('phone', 255)->nullable();
            $table->string('fax', 255)->nullable();
            $table->string('state', 255)->nullable();
            $table->string('rank', 255)->nullable();
            $table->string('district', 255)->nullable();
            $table->string('votes_with_party_pct', 255)->nullable();
            $table->string('votes_against_party_pct', 255)->nullable();

        });


        Schema::create('link_submission_approvals', function (Blueprint $table) {
            $table->string('id', 36)->default('');
            $table->string('link_submission_id', 36)->nullable();
            $table->string('status', 40)->nullable();
            $table->mediumText('reason')->nullable();
            $table->string('user_id', 36)->nullable();
            $table->nullableTimestamps();
            $table->softDeletes();

            $table->primary('id');
        });


        Schema::create('link_submissions', function (Blueprint $table) {
            $table->string('id', 36)->default('');
            $table->dateTime('submission_datetime_utc')->nullable();
            $table->text('submission_title')->nullable();
            $table->text('submission_media_url')->nullable();
            $table->text('submission_url')->nullable();
            $table->text('data')->nullable();
            $table->string('video_status', 191)->nullable();
            $table->string('user_id', 36)->nullable();
            $table->string('link_status', 40)->nullable();
            $table->string('link_status_ref', 40)->nullable();
            $table->tinyInteger('is_api_submission')->nullable()->default(0);
            $table->tinyInteger('dont_touch')->nullable()->default(0);
            $table->nullableTimestamps();
            $table->softDeletes();

            $table->primary('id');

        });


        Schema::create('reviewed_links', function (Blueprint $table) {
            $table->increments('id');
            $table->string('source', 40)->nullable();
            $table->text('url')->nullable();
            $table->timestamp('timestamp')->nullable()->default(\DB::raw('CURRENT_TIMESTAMP'));

        });


        Schema::create('videos', function (Blueprint $table) {
            $table->string('id', 36)->default('');
            $table->text('evidence_url')->nullable();
            $table->text('title')->nullable();
            $table->text('description')->nullable();
            $table->string('site', 191)->nullable();
            $table->string('uploader', 191)->nullable()->default('');
            $table->text('url')->nullable();
            $table->decimal('duration', 10, 2)->nullable();
            $table->text('tags')->nullable();
            $table->text('thumbnail')->nullable();
            $table->text('streams')->nullable();
            $table->text('meta')->nullable();
            $table->nullableTimestamps();
            $table->softDeletes();

            $table->primary('id');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('incidents');
        Schema::dropIfExists('evidence');
        Schema::dropIfExists('legislators');
        Schema::dropIfExists('link_submission_approvals');
        Schema::dropIfExists('link_submissions');
        Schema::dropIfExists('reviewed_links');
        Schema::dropIfExists('videos');
    }
}
