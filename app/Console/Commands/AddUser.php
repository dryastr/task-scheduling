<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class AddUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'add:user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add a user and send an email notification';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Data user yang akan ditambahkan
        $data = [
            'name' => 'Irfan Yasin',
            'email' => 'admin' . time() . '@gmail.com',
            'password' => bcrypt('pass' . time()), // Jangan lupa mengenkripsi password
            'created_at' => now()->format('Y-m-d H:i:s'),
            'updated_at' => now()->format('Y-m-d H:i:s'),
        ];

        // Menambahkan user ke database
        DB::table('users')->insert($data);

        // Mengirim email
        Mail::raw("User dengan nama {$data['name']} telah berhasil ditambahkan.", function ($message) {
            $message->to('davids@harakirimail.com')
                ->subject('Notifikasi Penambahan Pengguna');
        });

        $this->info('User berhasil ditambahkan dan email telah dikirim.');
    }
}
