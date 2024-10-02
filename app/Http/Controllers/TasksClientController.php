<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;


class TasksClientController extends Controller
{
    public function getTasksFromApi()
    {
        // Inisialisasi client Guzzle
        $client = new Client();

        // Request ke API tasks
        $response = $client->request('GET', 'https://daraya.id/api/tasks-api', [
            'headers' => [
                'Accept' => 'application/json',
            ],
        ]);

        $data = json_decode($response->getBody(), true);

        if ($data['status'] == 'success') {
            // Tampilkan data tasks
            return view('user.tasks-api.index', ['tasks' => $data['data']['tasks']]);
        } else {
            // Jika API gagal, tampilkan pesan error
            return back()->withErrors(['message' => 'Gagal mengambil data dari API']);
        }
    }
}
