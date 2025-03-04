@extends('layouts.app')

@section('content')
<div class="container my-5 p-4 shadow-sm bg-white rounded">
    <h1 class="mb-3">Registered Players</h1>

    <div class="table-responsive">
        <!-- <table class="table table-striped align-middle"> -->
        <table class="registration-form-1400 mb-3 table table-bordered">

            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>UID</th>
                    <th>Name</th>
                    <th>Date of Birth</th>
                    <th>Age</th>
                    <th>Gender</th>
                    <th>Registered At</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($players as $player)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $player->uid }}</td>
                        <td>{{ $player->name }}</td>
                        <td>{{ $player->dob }}</td>
                        <td>{{ $player->age }}</td>
                        <td>{{ $player->sex }}</td>
                        <td>{{ date("d-m-Y h:i A", strtotime($player->created_at)) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="d-flex justify-content-center mt-3">
        {{ $players->links() }}
    </div>
    
</div>
@endsection
