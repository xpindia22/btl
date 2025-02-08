<?php 


@extends('layouts.app')

@section('content')
<div class="container text-center">
    <h1 class="text-danger">Unauthorized Access</h1>
    <p>You do not have permission to view this page.</p>
    <a href="{{ route('dashboard') }}" class="btn btn-primary">Go to Dashboard</a>
</div>
@endsection
