<?php

namespace App\Listeners;

use App\Events\TaskDeadlineEvent;
use App\Notifications\TaskDeadlineNotification;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendDeadlineNotification implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\TaskDeadlineEvent  $event
     * @return void
     */
    public function handle(TaskDeadlineEvent $event)
    {
        $task = $event->task;

        // Check if the task's deadline is tomorrow
        $tomorrow = now()->addDay();
        if ($task->deadline->isTomorrow()) {
            // Send notification
            $task->user->notify(new TaskDeadlineNotification($task));
        }
    }
}
