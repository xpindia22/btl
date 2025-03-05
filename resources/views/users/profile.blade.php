@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Update Profile</h2>

    <!-- ✅ Success Message -->
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- ❌ Failure Message -->
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('users.updateProfile') }}">
        @csrf
        @method('PUT')

        <!-- Username -->
        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" class="form-control" value="{{ old('username', $user->username) }}" required>
            @error('username') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <!-- Email -->
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
            @error('email') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <!-- Date of Birth -->
        <div class="form-group">
            <label>Date of Birth</label>
            <input type="date" name="dob" id="dob" class="form-control" value="{{ old('dob', $user->dob) }}" required>
            @error('dob') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <!-- Age (Auto-Calculated) -->
        <div class="form-group">
            <label>Age (Auto-Calculated)</label>
            <input type="text" id="age" class="form-control" value="{{ $user->dob ? \Carbon\Carbon::parse($user->dob)->age : '' }}" disabled>
        </div>

        <!-- Sex -->
        <div class="form-group">
            <label>Sex</label>
            <select name="sex" class="form-control">
                <option value="Male" {{ old('sex', $user->sex) == 'Male' ? 'selected' : '' }}>Male</option>
                <option value="Female" {{ old('sex', $user->sex) == 'Female' ? 'selected' : '' }}>Female</option>
                <option value="Other" {{ old('sex', $user->sex) == 'Other' ? 'selected' : '' }}>Other</option>
            </select>
            @error('sex') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <!-- New Password -->
        <div class="form-group">
            <label>New Password (Leave empty if not changing)</label>
            <input type="password" name="password" class="form-control">
            @error('password') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <!-- Confirm Password -->
        <div class="form-group">
            <label>Confirm Password</label>
            <input type="password" name="password_confirmation" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Update Profile</button>
    </form>
</div>

<script>
    function calculateAge() {
        const dobInput = document.getElementById("dob");
        const ageInput = document.getElementById("age");

        if (dobInput && ageInput) {
            const dob = new Date(dobInput.value);
            if (!isNaN(dob)) {
                const today = new Date();
                let age = today.getFullYear() - dob.getFullYear();
                const monthDiff = today.getMonth() - dob.getMonth();
                if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < dob.getDate())) {
                    age--;
                }
                ageInput.value = age;
            } else {
                ageInput.value = "";
            }
        }
    }

    document.getElementById("dob").addEventListener("change", calculateAge);
    window.addEventListener("load", calculateAge);
</script>

@endsection
