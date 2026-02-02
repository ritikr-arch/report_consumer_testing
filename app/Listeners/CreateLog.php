<?php

namespace App\Listeners;

use App\Events\PublishEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\PublishLog;
use Illuminate\Support\Facades\Log;

class CreateLog
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */

    public function handle(PublishEvent $event): void
    {
        $type = $event->type;
        $userId = $event->userId;
        $surveyId = $event->surveyId;
        if($type == 'publish'){
            foreach ($surveyId as $key => $value) {
            PublishLog::create(['survey_id'=>$value, 'updated_by'=>$userId, 'type'=>$type]);
            }
        }else{
            PublishLog::create(['survey_id'=>$surveyId, 'updated_by'=>$userId, 'type'=>$type]);
        }
    }
}
