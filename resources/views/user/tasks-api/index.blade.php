@extends('layouts.main')

@section('title', 'Daftar Tugas')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Daftar Tugas</h4>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-xl" style="margin-top: 2.5rem!important;">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode Tugas</th>
                                        <th>Deskripsi</th>
                                        <th style="min-width: 5rem;">Deadline</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($tasks && count($tasks) > 0)
                                        @foreach ($tasks as $index => $task)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $task['code_task'] }}</td>
                                                <td>{!! Str::limit($task['description'], 100, '...') !!}</td>
                                                <td>{{ \Carbon\Carbon::parse($task['deadline'])->format('d M Y') }}</td>
                                                <td>
                                                    <span
                                                        class="badge {{ $task['task_status'] == 'Completed' ? 'bg-success' : ($task['task_status'] == 'In Progress' ? 'bg-warning' : 'bg-danger') }}">
                                                        {{ $task['task_status'] }}
                                                    </span>
                                                </td>
                                                <td class="text-nowrap">
                                                    <div class="dropdown dropup">
                                                        <button class="btn btn-sm btn-secondary dropdown-toggle"
                                                            type="button" id="dropdownMenuButton-{{ $task['code_task'] }}"
                                                            data-bs-toggle="dropdown" aria-expanded="false">
                                                            <i class="bi bi-three-dots-vertical"></i>
                                                        </button>
                                                        <ul class="dropdown-menu"
                                                            aria-labelledby="dropdownMenuButton-{{ $task['code_task'] }}">
                                                            <li><a class="dropdown-item"
                                                                    href="{{ route('tasks.edit', $task['id']) }}">Ubah</a>
                                                            </li>
                                                            <li>
                                                                <form action="{{ route('tasks.destroy', $task['id']) }}"
                                                                    method="POST"
                                                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus tugas ini?')">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit"
                                                                        class="dropdown-item">Hapus</button>
                                                                </form>
                                                            </li>
                                                            <li>
                                                                <button type="button" class="dropdown-item"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#taskDetailModal-{{ $task['id'] }}">
                                                                    Lihat Detail
                                                                </button>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>

                                            <!-- Modal -->
                                            <div class="modal fade" id="taskDetailModal-{{ $task['id'] }}" tabindex="-1"
                                                aria-labelledby="taskDetailModalLabel-{{ $task['id'] }}"
                                                aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title"
                                                                id="taskDetailModalLabel-{{ $task['id'] }}">
                                                                Detail Tugas
                                                            </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p><strong>Kode Tugas:</strong> {{ $task['code_task'] }}</p>
                                                            <p><strong>Deskripsi:</strong> {{ $task['description'] }}</p>
                                                            <p><strong>Status:</strong>
                                                                <span
                                                                    class="badge {{ $task['task_status'] == 'Selesai' ? 'bg-success' : ($task['task_status'] == 'Proses' ? 'bg-warning' : 'bg-danger') }}">
                                                                    {{ $task['task_status'] }}
                                                                </span>
                                                            </p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Tutup</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="5" class="text-center">Data task tidak ditemukan.</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
