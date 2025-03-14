@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Pay for Tournament: {{ $tournament->name }}</h3>
    <p>Category: {{ $category->name }}</p>
    <p>Amount: ₹{{ $tournament->tournament_fee }}</p>
    
    <p>Send your payment to: <strong>7432001215 (GPay/UPI)</strong></p>
    <p>Once done, enter your Transaction ID below:</p>

    <form action="{{ route('payments.store') }}" method="POST">
        @csrf
        <input type="hidden" name="tournament_id" value="{{ $tournament->id }}">
        <input type="hidden" name="category_id" value="{{ $category->id }}">
        <input type="hidden" name="amount" value="{{ $tournament->tournament_fee }}">

        <label for="transaction_id">Transaction ID:</label>
        <input type="text" name="transaction_id" required class="form-control">

        <button type="submit" class="btn btn-primary mt-3">Submit Payment</button>
    </form>
</div>
@endsection
