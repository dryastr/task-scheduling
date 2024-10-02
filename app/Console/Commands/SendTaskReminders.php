<?php

namespace App\Console\Commands;

use App\Models\Task;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendTaskReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-task-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send task reminders to users at the specified time';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Log::info('Task reminder command started.');

        // Ambil tugas yang perlu diingat
        $tasks = Task::whereNotNull('time_reminder')
            ->where('time_reminder', '<=', now())
            ->where('deadline', '>=', now()) 
            ->get();

        foreach ($tasks as $task) {
            // Tentukan alamat email berdasarkan pemilik tugas
            $email = $this->getEmailByOwner($task->who);

            // Kirim email pengingat
            Mail::raw("Pengingat: {$task->title}\nDeskripsi: {$task->description}", function ($message) use ($email, $task) {
                $message->to($email)
                    ->subject('Pengingat Tugas: ' . $task->title);
            });

            Log::info('Reminder sent for task: ' . $task->title);
            $this->info('Pengingat tugas untuk "' . $task->title . '" telah dikirim ke ' . $email . '.');
        }
    }

    /**
     * Mendapatkan alamat email berdasarkan pemilik tugas.
     *
     * @param string $owner
     * @return string
     */
    protected function getEmailByOwner($owner)
    {
        switch ($owner) {
            case 'david':
                return 'david@harakirimail.com';
            case 'ann':
                return 'ann@harakirimail.com';
            default:
                return 'default@harakirimail.com';
        }
    }
}
