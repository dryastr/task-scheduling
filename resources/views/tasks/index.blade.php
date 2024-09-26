@extends('layouts.main')

@section('title', 'Daftar Tugas')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <h4 class="card-title">Daftar Tugas</h4>
                        <a href="{{ route('tasks.create') }}" class="btn btn-success">Buat Tugas Baru</a>
                    </div>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-xl" style="margin-top: 2.5rem!important;">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Pengguna</th>
                                        <th>Judul</th>
                                        <th>Deskripsi</th>
                                        <th>Pengingat</th>
                                        <th>Batas Waktu</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($tasks as $task)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $task->who }}</td>
                                            <td>{{ $task->title }}</td>
                                            <td>{!! Str::limit($task->description, 100, '...') !!}</td>
                                            <td>{{ \Carbon\Carbon::parse($task->time_reminder)->format('d M Y, H:i') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($task->deadline)->format('d M Y, H:i') }}</td>
                                            <td>
                                                @if ($task->status == null)
                                                    <span class="badge bg-danger">Pending</span>
                                                @elseif ($task->status == 'Proses')
                                                    <span class="badge bg-warning">Proses</span>
                                                @elseif ($task->status == 'Selesai')
                                                    <span class="badge bg-success">Selesai</span>
                                                @endif
                                            </td>

                                            <td class="text-nowrap">
                                                <div class="dropdown dropup">
                                                    <button class="btn btn-sm btn-secondary dropdown-toggle" type="button"
                                                        id="dropdownMenuButton-{{ $task->id }}"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="bi bi-three-dots-vertical"></i>
                                                    </button>
                                                    <ul class="dropdown-menu"
                                                        aria-labelledby="dropdownMenuButton-{{ $task->id }}">
                                                        <li><a class="dropdown-item"
                                                                href="{{ route('tasks.edit', $task->id) }}">Ubah</a></li>
                                                        <li>
                                                            <form action="{{ route('tasks.destroy', $task->id) }}"
                                                                method="POST"
                                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus tugas ini?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="dropdown-item">Hapus</button>
                                                            </form>
                                                        </li>
                                                        {{-- Button Proses --}}
                                                        @if (is_null($task->status))
                                                            <li>
                                                                <form action="{{ route('tasks.markAsProses', $task->id) }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    @method('PATCH')
                                                                    <button type="submit"
                                                                        class="dropdown-item">Proses</button>
                                                                </form>
                                                            </li>
                                                        @endif

                                                        {{-- Button Selesai --}}
                                                        @if ($task->status === 'Proses')
                                                            <li>
                                                                <form
                                                                    action="{{ route('tasks.markAsSelesai', $task->id) }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    @method('PATCH')
                                                                    <button type="submit"
                                                                        class="dropdown-item">Selesai</button>
                                                                </form>
                                                            </li>
                                                        @endif
                                                        <li>
                                                            <!-- Button trigger modal -->
                                                            <button type="button" class="dropdown-item"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#taskDetailModal-{{ $task->id }}">
                                                                Lihat Detail
                                                            </button>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>

                                        <!-- Modal -->
                                        <div class="modal fade" id="taskDetailModal-{{ $task->id }}" tabindex="-1"
                                            aria-labelledby="taskDetailModalLabel-{{ $task->id }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title"
                                                            id="taskDetailModalLabel-{{ $task->id }}">
                                                            Detail Tugas
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p><strong>Pengguna:</strong> {{ $task->who }}</p>
                                                        <p><strong>Judul:</strong> {{ $task->title }}</p>
                                                        <p><strong>Deskripsi:</strong> {{ $task->description }}</p>
                                                        <p><strong>Pengingat:</strong>
                                                            {{ \Carbon\Carbon::parse($task->time_reminder)->format('d M Y, H:i') }}
                                                        </p>
                                                        <p><strong>Batas Waktu:</strong>
                                                            {{ \Carbon\Carbon::parse($task->deadline)->format('d M Y, H:i') }}
                                                        </p>
                                                        <p><strong>Status:</strong>
                                                            @if ($task->status == null)
                                                                <span class="badge bg-danger">Belum Ada Status</span>
                                                            @elseif ($task->status == 'Proses')
                                                                <span class="badge bg-warning">Proses</span>
                                                            @elseif ($task->status == 'Selesai')
                                                                <span class="badge bg-success">Selesai</span>
                                                            @endif
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
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
