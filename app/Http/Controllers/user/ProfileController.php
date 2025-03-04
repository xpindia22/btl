public function changePassword(Request $request)
{
    $request->validate([
        'current_password' => 'required',
        'new_password' => 'required|min:6|confirmed',
    ]);

    $user = auth()->user();

    // Verify the current password
    if (!\Hash::check($request->current_password, $user->password)) {
        return back()->withErrors(['current_password' => 'Current password does not match.']);
    }

    // Update the password
    $user->password = \Hash::make($request->new_password);
    $user->save();

    return back()->with('success', 'Password changed successfully.');
}
