<?php

namespace App\Console\Commands;

use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class NotifyTeleDaraya extends Command
{
    protected $signature = 'app:notify-teledaraya';
    protected $description = 'Mengirim notifikasi melalui bot Telegram untuk tugas di Daraya.id';

    public function handle()
    {
        $client = new Client();
        $botToken = '6951044097:AAG8a88A_amGaoHsAXAq0FEFg7hKr4aX5SQ';
        $chatId = '2039828849';

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

                    if ($task['task_status'] === 'Completed') {
                        Log::info("Task {$task['code_task']} sudah selesai dan tidak akan diproses.");
                        continue;
                    }

                    if (now()->greaterThanOrEqualTo($createdAt) && now()->lessThanOrEqualTo($deadline)) {
                        $this->sendTelegramNotification($task, $botToken, $chatId);
                        $hasActiveTasks = true;
                    } else {
                        Log::info("Task {$task['code_task']} melewati deadline dan tidak akan diproses.");
                    }
                }

                if (!$hasActiveTasks) {
                    $this->info("Tidak ada tugas aktif.");
                }
            } else {
                Log::error('Gagal mengambil data tugas: ' . $data['message']);
            }
        } catch (\Exception $e) {
            Log::error('Error fetching tasks: ' . $e->getMessage());
        }
    }

    protected function sendTelegramNotification($task, $botToken, $chatId)
    {
        $client = new Client();
        $message = "Pengingat Tugas dari @daraya_official\n\n";
        $message .= "Kode Tugas: {$task['code_task']}\n";
        $message .= "Deskripsi: {$task['description']}\n";
        $message .= "Deadline: {$task['deadline']}\n";
        $message .= "Status: {$task['task_status']}";

        try {
            $client->post("https://api.telegram.org/bot{$botToken}/sendMessage", [
                'json' => [
                    'chat_id' => $chatId,
                    'text' => $message,
                ],
            ]);

            Log::info('Notifikasi Telegram dikirim untuk task: ' . $task['code_task']);
        } catch (\Exception $e) {
            Log::error('Gagal mengirim notifikasi Telegram: ' . $e->getMessage());
        }
    }
}
