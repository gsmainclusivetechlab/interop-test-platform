<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateComponentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('test_case_components', function (Blueprint $table) {
            $table->string('component_name');
            $table->json('component_versions')->nullable();
        });

        $components = DB::table('components')
            ->get()
            ->keyBy('id');

        DB::table('test_case_components')
            ->orderBy('test_case_id')
            ->each(function ($testCaseComponent) use ($components) {
                $component = $components->get($testCaseComponent->component_id);

                DB::table('test_case_components')
                    ->where([
                        'test_case_id' => $testCaseComponent->test_case_id,
                        'component_id' => $testCaseComponent->component_id,
                    ])
                    ->update([
                        'component_name' => $component->name,
                    ]);
            });

        Schema::table('components', function (Blueprint $table) {
            $table->dropColumn([
                'uuid',
                'name',
                'base_url',
                'description',
                'position',
                'sutable',
                'created_at',
                'updated_at',
            ]);
        });

        Schema::table('test_steps', function (Blueprint $table) {
            $table->dropForeign(['source_id', 'target_id']);
        });

        Schema::dropIfExists('component_connections');

        Schema::table('session_components', function (Blueprint $table) {
            $table->string('version')->nullable();
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
            $table->dropColumn('version');
        });

        Schema::create('component_connections', function (Blueprint $table) {
            $table->unsignedBigInteger('source_id');
            $table
                ->foreign('source_id')
                ->references('id')
                ->on('components')
                ->onDelete('cascade');
            $table->unsignedBigInteger('target_id');
            $table
                ->foreign('target_id')
                ->references('id')
                ->on('components')
                ->onDelete('cascade');
            $table->primary(['source_id', 'target_id']);
        });

        DB::table('test_steps')
            ->orderBy('id')
            ->each(function ($testStep) {
                if (
                    !DB::table('component_connections')
                        ->where([
                            'source_id' => $testStep->source_id,
                            'target_id' => $testStep->target_id,
                        ])
                        ->exists()
                ) {
                    DB::table('component_connections')->insert([
                        'source_id' => $testStep->source_id,
                        'target_id' => $testStep->target_id,
                    ]);
                }
            });

        Schema::table('test_steps', function (Blueprint $table) {
            $table
                ->foreign(['source_id', 'target_id'])
                ->references(['source_id', 'target_id'])
                ->on('component_connections')
                ->onDelete('cascade');
        });

        Schema::table('components', function (Blueprint $table) {
            $table->uuid('uuid')->after('id');
            $table->string('name')->after('uuid');
            $table->string('base_url')->after('name');
            $table
                ->text('description')
                ->nullable()
                ->after('base_url');
            $table->unsignedInteger('position')->after('description');
            $table
                ->boolean('sutable')
                ->default(true)
                ->after('position');
            $table->timestamps();
        });

        $testCaseComponents = DB::table('test_case_components')->pluck(
            'component_name',
            'component_id'
        );

        $i = 1;
        DB::table('components')
            ->orderBy('id')
            ->each(function ($component) use (&$i, $testCaseComponents) {
                $componentName = $testCaseComponents->get($component->id);

                DB::table('components')
                    ->where('id', $component->id)
                    ->update([
                        'uuid' => Str::uuid(),
                        'name' => $componentName,
                        'base_url' => '',
                        'position' => $i++,
                    ]);
            });

        Schema::table('test_case_components', function (Blueprint $table) {
            $table->dropColumn(['component_name', 'component_versions']);
        });
    }
}
