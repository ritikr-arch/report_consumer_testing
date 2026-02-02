<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

class UpdateAmountUpdateTrigger extends Migration
{
    public function up(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS before_amount_update');

        DB::unprepared('
            CREATE TRIGGER before_amount_update
            BEFORE UPDATE ON submitted_surveys
            FOR EACH ROW
            BEGIN
                DECLARE current_user_id INT;
                SET current_user_id = @user_id;

                IF (
                    current_user_id IS NOT NULL AND
                    (
                        NOT (OLD.amount <=> NEW.amount) OR
                        NOT (OLD.amount_1 <=> NEW.amount_1)
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

    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS before_amount_update');
    }
}
