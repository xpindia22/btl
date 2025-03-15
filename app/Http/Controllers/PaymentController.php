<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Tournament;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\PaymentReceivedMail;

class PaymentController extends Controller
{
    // Show Payment Page
    public function showPaymentPage($tournament_id, $category_id)
    {
        $tournament = Tournament::findOrFail($tournament_id);
        $category = Category::findOrFail($category_id);

        return view('payments.pay', compact('tournament', 'category'));
    }

    // Store Payment Transaction ID
    public function storePayment(Request $request)
    {
        $request->validate([
            'tournament_id' => 'required|integer',
            'category_id' => 'required|integer',
            'amount' => 'required|numeric|min:0',
            'transaction_id' => 'required|string|unique:payments',
        ]);

        $payment = Payment::create([
            'user_id' => Auth::id(),
            'tournament_id' => $request->tournament_id,
            'category_id' => $request->category_id,
            'amount' => $request->amount,
            'payment_method' => 'UPI',
            'transaction_id' => $request->transaction_id,
            'status' => 'Pending',
        ]);

        // Fetch tournament details
        $tournament = Tournament::find($request->tournament_id);
        $creator = User::find($tournament->created_by);
        $adminEmail = 'xpindia@gmail.com';
        $moderator = User::find($tournament->moderated_by);

        // Send email notifications
        if ($creator) {
            Mail::to($creator->email)->send(new PaymentReceivedMail($payment));
        }

        if ($moderator) {
            Mail::to($moderator->email)->send(new PaymentReceivedMail($payment));
        }

        Mail::to($adminEmail)->send(new PaymentReceivedMail($payment));

        return redirect()->route('dashboard')->with('success', 'Payment submitted, pending verification.');
    }

    // Admin View: Verify Payments
    public function adminViewPayments()
    {
        $payments = Payment::where('status', 'Pending')->get();
        return view('admin.payments.index', compact('payments'));
    }

    // Admin Action: Approve/Reject Payment
    public function updatePaymentStatus(Request $request, $id)
    {
        $payment = Payment::findOrFail($id);
        $request->validate([
            'status' => 'required|in:Verified,Rejected,Fee Waived,Discounted',
            'discount_amount' => 'nullable|numeric|min:0'
        ]);

        $payment->update([
            'status' => $request->status,
            'discount_amount' => $request->discount_amount ?? null
        ]);

        return redirect()->route('admin.payments')->with('success', 'Payment status updated.');
    }
}
