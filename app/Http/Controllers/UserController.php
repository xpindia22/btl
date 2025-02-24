<?php 

namespace App\Http\Controllers;

use App\Models\Tournament;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    // ✅ Show Users List Based on Role
    public function index()
    {
        $authUser = Auth::user();

        if ($authUser->isAdmin()) {
            $users = User::with(['moderatedTournaments', 'createdTournaments'])
                ->orderBy('id', 'asc')
                ->paginate(10);
        } else {
            $users = User::with(['moderatedTournaments', 'createdTournaments'])
                ->where('created_by', $authUser->id)
                ->orderBy('id', 'asc')
                ->paginate(10);
        }

        return view('users.index', compact('users'));
    }

    // ✅ Show User Creation Form (Restricted)
    public function create()
    {
        $authUser = Auth::user();
        $roles = $authUser->isAdmin() 
            ? ['admin', 'user', 'player', 'visitor']
            : ['user', 'player', 'visitor'];

        return view('users.create', compact('roles'));
    }

    // ✅ Store New User in Database
    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'mobile_no' => 'nullable|digits:10',
            'role' => 'required|in:admin,user,player,visitor',
        ]);

        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to create a user.');
        }

        $loggedInUserId = Auth::id();
        
        Log::info('Creating User', [
            'creator_id' => $loggedInUserId,
            'new_user' => $request->username
        ]);

        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'mobile_no' => $request->mobile_no,
            'role' => $request->role,
            'created_by' => $loggedInUserId, // ✅ Assign creator
        ]);

        Log::info('User Created Successfully:', $user->toArray());

        return redirect()->route('users.index')->with('success', 'User registered successfully!');
    }

    // ✅ Show Edit User Form (Restricted)
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $authUser = Auth::user();

        // ✅ Only Admin or Creator Can Edit
        if (!$authUser->isAdmin() && $authUser->id !== $user->created_by) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized');
        }

        $roles = $authUser->isAdmin() 
            ? ['admin', 'user', 'player', 'visitor']
            : ['user', 'player', 'visitor'];

        $tournaments = Tournament::orderBy('year', 'desc')->get(); // ✅ Fetch tournaments for moderator selection

        return view('users.edit', compact('user', 'roles', 'tournaments'));
    }

    // ✅ Update User (Restricted)
   // ✅ Update User (Restricted)
   public function update(Request $request, $id)
   {
       $user = User::findOrFail($id);
   
       $user->update([
           'username' => $request->username,
           'email' => $request->email,
           'mobile_no' => $request->mobile_no,
           'role' => $request->role,
       ]);
   
       // ✅ Sync Moderated Tournaments (BelongsToMany)
       if ($request->has('moderated_tournaments')) {
           $user->moderatedTournaments()->sync($request->moderated_tournaments);
       } else {
           $user->moderatedTournaments()->detach();
       }
   
       // ✅ Get Default Admin ID
       $defaultAdminId = User::where('username', 'xxx')->where('role', 'admin')->value('id');
   
       // ✅ Update Creator Field using the checkboxes submitted from the form
       $selected = $request->input('created_tournaments', []);

       // For tournaments that previously had this user as creator but are now unchecked,
       // reassign them to the default admin.
       Tournament::where('created_by', $user->id)
           ->whereNotIn('id', $selected)
           ->update(['created_by' => $defaultAdminId]);

       // For tournaments that are checked, assign this user as the creator.
       if (!empty($selected)) {
           Tournament::whereIn('id', $selected)
               ->update(['created_by' => $user->id]);
       }
   
       // ✅ Force refresh the relationship so UI updates instantly
       $user->load('createdTournaments');
   
       return redirect()->route('users.index')->with('success', 'User updated successfully.');
   }
    

    // ✅ Delete User (Restricted)
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $authUser = Auth::user();

        // ✅ Only Admin or Creator Can Delete
        if (!$authUser->isAdmin() && $authUser->id !== $user->created_by) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized');
        }

        // ✅ Prevent Self-Deletion
        if ($user->id === $authUser->id) {
            return redirect()->route('users.index')->with('error', 'You cannot delete yourself.');
        }

        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }

    // ✅ Admin View for Editing Users
    public function editUsers() 
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('users.index')->with('error', 'Unauthorized access!');
        }

        $users = User::with(['moderatedTournaments', 'createdTournaments'])->orderBy('id', 'asc')->paginate(10);
        $tournaments = Tournament::orderBy('year', 'desc')->get(); // ✅ Get all tournaments for dropdown

        return view('users.edit', compact('users', 'tournaments'));
    }
}
