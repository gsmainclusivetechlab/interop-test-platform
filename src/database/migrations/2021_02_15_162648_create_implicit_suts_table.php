<?php

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImplicitSutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('implicit_suts', function (Blueprint $table) {
            $table->id();
            $table->string('slug');
            $table->string('version', 50);
            $table->string('url');
            $table->boolean('use_encryption');
            $table->timestamps();
        });

        Schema::table('certificates', function (Blueprint $table) {
            $table->nullableMorphs('certificable');
        });

        DB::table('certificates')
            ->orderBy('id')
            ->whereNotNull('group_id')
            ->each(function ($certificate) {
                DB::table('certificates')
                    ->where('id', $certificate->id)
                    ->update([
                        'certificable_id' => $certificate->group_id,
                        'certificable_type' => $this->getMorphType(),
                    ]);
            });

        Schema::table('certificates', function (Blueprint $table) {
            $table->dropColumn('group_id');
        });

        Schema::table('session_components', function (Blueprint $table) {
            $table->unsignedBigInteger('implicit_sut_id')->nullable();

            $table
                ->foreign('implicit_sut_id')
                ->references('id')
                ->on('implicit_suts')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('session_components', function (Blueprint $table) {
            $table->dropForeign(['implicit_sut_id']);
            $table->dropColumn('implicit_sut_id');
        });

        Schema::table('certificates', function (Blueprint $table) {
            $table->unsignedBigInteger('group_id')->nullable();
        });

        DB::table('certificates')
            ->orderBy('id')
            ->where('certificable_type', $this->getMorphType())
            ->each(function ($certificate) {
                DB::table('certificates')
                    ->where('id', $certificate->id)
                    ->update([
                        'group_id' => $certificate->certificable_id,
                    ]);
            });

        DB::table('certificates')
            ->orderBy('id')
            ->where('certificable_type', '!=', $this->getMorphType())
            ->delete();

        Schema::table('certificates', function (Blueprint $table) {
            $table->dropMorphs('certificable');
        });

        Schema::dropIfExists('implicit_suts');
    }

    protected function getMorphType(): string
    {
        return array_search('App\Models\Group', Relation::morphMap()) ?:
            'App\Models\Group';
    }
}
