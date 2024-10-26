<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Task;
use Carbon\Carbon;
class RemoveTrashedTasks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tasks:remove-trashed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove all trashed task for over 30 days and up';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        try {
            Task::onlyTrashed()
            ->where('deleted_at', '<=', Carbon::now()->subDays(30))
            ->forceDelete();
        } catch (\Throwable $th) {
            $this->error($th->getMessage());
        }
       
        $this->info('All trashed task over 30 days have been deleted.');
        return 0;
    }
}
