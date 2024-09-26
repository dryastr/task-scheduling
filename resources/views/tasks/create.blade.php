@extends('layouts.main')

@section('title', 'Tambah Tugas Baru')

@section('content')
    <div class="col-md-12 col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Tambah Tugas Baru</h4>
            </div>
            <div class="card-content">
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{ route('tasks.store') }}" method="POST" class="form form-horizontal">
                        @csrf
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="who">Siapa Tugas Untuk</label>
                                </div>
                                <div class="col-md-8 form-group">
                                    <select id="who" name="who" class="form-control" required>
                                        <option value="" selected disabled>Pilih...</option>
                                        <option value="ann">Ann</option>
                                        <option value="david">David</option>
                                    </select>
                                </div>


                                <div class="col-md-4">
                                    <label for="title">Judul Tugas</label>
                                </div>
                                <div class="col-md-8 form-group">
                                    <input type="text" id="title" class="form-control" name="title"
                                        placeholder="Judul Tugas" required>
                                </div>

                                <div class="col-md-4">
                                    <label for="description">Deskripsi</label>
                                </div>
                                <div class="col-md-8 form-group">
                                    <textarea id="description" name="description" class="form-control" placeholder="Deskripsi"></textarea>
                                </div>

                                <div class="col-md-4">
                                    <label for="time_reminder">Pengingat</label>
                                </div>
                                <div class="col-md-8 form-group">
                                    <input type="datetime-local" id="time_reminder" class="form-control"
                                        name="time_reminder">
                                </div>

                                <div class="col-md-4">
                                    <label for="deadline">Batas Waktu</label>
                                </div>
                                <div class="col-md-8 form-group">
                                    <input type="datetime-local" id="deadline" class="form-control" name="deadline">
                                </div>

                                <div class="col-sm-12 d-flex justify-content-end mt-5">
                                    <a href="{{ route('tasks.index') }}" class="btn btn-secondary me-1 mb-1">Kembali</a>
                                    <button type="submit" class="btn btn-primary me-1 mb-1">Simpan</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
