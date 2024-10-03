<?php

namespace App\Console\Commands;

use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class TaskDaraya extends Command
{
    protected $signature = 'app:tasks-daraya';
    protected $description = 'Fetch tasks from daraya.id and store them in task-management.daraya.id';

    public function handle()
    {
        $client = new Client();

        try {
            $response = $client->request('GET', 'https://daraya.id/api/tasks-api', [
                'headers' => ['Accept' => 'application/json'],
            ]);

            $data = json_decode($response->getBody(), true);

            if ($data['status'] == 'success') {
                $tasks = $data['data']['tasks'];
                $hasActiveTasks = false;

                foreach ($tasks as $task) {
                    $createdAt = \Carbon\Carbon::parse($task['created_at']);
                    $deadline = \Carbon\Carbon::parse($task['deadline']);

                    // Periksa apakah tugas sudah 'Completed' atau melewati deadline
                    if ($task['task_status'] === 'Completed') {
                        Log::info("Task {$task['code_task']} is completed and will not be processed.");
                        continue;
                    }

                    // Simpan tugas dalam database jika belum melewati deadline
                    if (now()->greaterThanOrEqualTo($createdAt) && now()->lessThanOrEqualTo($deadline)) {
                        Log::info("Task fetched: {$task['code_task']} created at {$createdAt}");
                        $this->sendEmailNotification($task); // Kirim notifikasi email
                        $hasActiveTasks = true; // Menandakan ada tugas aktif
                    } else {
                        Log::info("Task {$task['code_task']} is past the deadline and will not be processed.");
                    }
                }

                // Jika tidak ada tugas aktif, kirimkan notifikasi
                if (!$hasActiveTasks) {
                    $this->info("No active tasks found.");
                    // Anda bisa menambahkan logika untuk mengirim email atau tindakan lainnya jika perlu
                }
            } else {
                Log::error('Failed to fetch tasks: ' . $data['message']);
            }
        } catch (\Exception $e) {
            Log::error('Error fetching tasks: ' . $e->getMessage());
        }
    }

    protected function sendEmailNotification($task)
    {
        Mail::raw("Pengingat: TUGAS DARAYA.ID\nDeskripsi: {$task['description']}\nDeadline: {$task['deadline']}", function ($message) use ($task) {
            $message->to('daraya@harakirimail.com')
                ->subject('Notifikasi Tugas: ' . $task['description']);
        });

        Log::info('Email notification sent for task: ' . $task['description']);
    }
}
