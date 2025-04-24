<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Task;
use App\Models\TaskDeletion;
use Illuminate\Support\Facades\Schema;

class CleanUpOldTasks extends Command
{
    protected $signature = 'tasks:cleanup';
    protected $description = 'Delete tasks older than 30 days and log the process into the database';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        if (!Schema::hasTable('task_deletions')) {
            $this->error('The "task_deletions" table does not exist.');
            return Command::FAILURE;
        }

        $tasks = Task::where('created_at', '<', now()->subDays(30))->get();

        if ($tasks->isEmpty()) {
            $this->info('No tasks older than 30 days found.');
            return Command::SUCCESS;
        }

        $tasks->each(function ($task) {
            TaskDeletion::create([
                'task_id' => $task->id,
                'task_title' => $task->title,
                'deleted_at' => now(),
            ]);

            $task->delete();
        });

        $this->info('Old tasks cleanup completed and logged.');
        return Command::SUCCESS;
    }
}