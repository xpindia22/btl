<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;

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
            'payment_method' => 'UPI', // Default UPI
            'transaction_id' => $request->transaction_id,
            'status' => 'Pending',
        ]);

        return redirect()->route('payments.status')->with('success', 'Payment submitted, pending verification.');
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
