<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

class CreateTriggerAfterAmountUpdate extends Migration
{
    public function up()
    {
        DB::unprepared('
            CREATE TRIGGER trigger_after_amount_update
            AFTER UPDATE ON submitted_surveys
            FOR EACH ROW
            BEGIN
                DECLARE current_user_id INT;
                SET current_user_id = @user_id;

                IF (
                    current_user_id IS NOT NULL AND
                    (
                        OLD.amount != NEW.amount OR
                        OLD.amount_1 != NEW.amount_1
                    )
                ) THEN
                    INSERT INTO amount_update_logs (
                        submitted_survey_id,
                        survey_id,
                        old_amount,
                        new_amount,
                        old_amount_1,
                        new_amount_1,
                        updated_by,
                        updated_at
                    ) VALUES (
                        OLD.id,
                        OLD.survey_id,
                        OLD.amount,
                        NEW.amount,
                        OLD.amount_1,
                        NEW.amount_1,
                        current_user_id,
                        NOW()
                    );
                END IF;
            END
        ');
    }

    public function down()
    {
        DB::unprepared('DROP TRIGGER IF EXISTS trigger_after_amount_update');
    }
}
