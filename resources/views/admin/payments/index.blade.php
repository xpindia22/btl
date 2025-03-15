@extends('layouts.admin')

@section('content')
<div class="container">
    <h3>Pending Payments</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Player</th>
                <th>Tournament</th>
                <th>Category</th>
                <th>Amount</th>
                <th>Transaction ID</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($payments as $payment)
            <tr>
                <td>{{ $payment->user->username }}</td>
                <td>{{ $payment->tournament->name }}</td>
                <td>{{ $payment->category->name }}</td>
                <td>₹{{ $payment->amount }}</td>
                <td>{{ $payment->transaction_id }}</td>
                <td>{{ $payment->status }}</td>
                <td>
                    <form action="{{ route('admin.payments.update', $payment->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <select name="status">
                            <option value="Verified">Approve</option>
                            <option value="Rejected">Reject</option>
                            <option value="Fee Waived">Cancel Fee</option>
                            <option value="Discounted">Apply Discount</option>
                        </select>
                        <input type="number" name="discount_amount" placeholder="Discount (₹)" class="form-control">
                        <button type="submit" class="btn btn-success mt-2">Update</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
