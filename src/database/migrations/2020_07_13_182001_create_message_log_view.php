<?php

use Illuminate\Database\Migrations\Migration;

class CreateMessageLogView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement($this->dropView());

        DB::statement($this->createView());
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement($this->dropView());
    }

    private function createView(): string
    {
        return <<<SQL
CREATE VIEW `message_log` AS
(
    SELECT
        'MISMATCH' AS 'type',
        `id`,
        `session_id`,
        `request`,
        `exception`,
        `created_at`,
        NULL AS test_run_id,
        NULL AS test_step_id
    FROM
        `message_mismatches`
)
UNION
    (
    SELECT
        'RESULT' AS 'type',
        `test_results`.`id`,
        `test_runs`.`session_id`,
        `request`,
        `exception`,
        `test_results`.`created_at`,
        `test_run_id`,
        `test_step_id`
    FROM
        `test_results`
    INNER JOIN `test_runs` ON `test_runs`.`id` = `test_results`.`test_run_id`
)
SQL;
    }
    private function dropView(): string
    {
        return <<<SQL
DROP VIEW IF EXISTS `message_log`;
SQL;
    }
}
