@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Create New User</h2>
    <div class="form-container">
        <form action="{{ route('users.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" name="username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username') }}" required>
                @error('username')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">Primary Email:</label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="secondary_email">Secondary Email:</label>
                <input type="email" name="secondary_email" class="form-control @error('secondary_email') is-invalid @enderror" value="{{ old('secondary_email') }}">
                @error('secondary_email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- ✅ Password and Confirm Password Fields -->
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirm Password:</label>
                <input type="password" name="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" required>
                @error('password_confirmation')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="mobile_no">Mobile No:</label>
                <input type="text" name="mobile_no" class="form-control @error('mobile_no') is-invalid @enderror" value="{{ old('mobile_no') }}">
                @error('mobile_no')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="dob">Date of Birth:</label>
                <input type="date" name="dob" class="form-control @error('dob') is-invalid @enderror" value="{{ old('dob') }}" required>
                @error('dob')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="sex">Gender:</label>
                <select name="sex" class="form-control @error('sex') is-invalid @enderror" required>
                    <option value="Male" {{ old('sex') == 'Male' ? 'selected' : '' }}>Male</option>
                    <option value="Female" {{ old('sex') == 'Female' ? 'selected' : '' }}>Female</option>
                    <option value="Other" {{ old('sex') == 'Other' ? 'selected' : '' }}>Other</option>
                </select>
                @error('sex')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="role">Role:</label>
                <select name="role" class="form-control @error('role') is-invalid @enderror" required>
                    @if(auth()->user() && auth()->user()->role === 'admin')
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    @endif
                    <option value="user" {{ old('role', 'user') == 'user' ? 'selected' : '' }}>User</option>
                    <option value="visitor" {{ old('role') == 'visitor' ? 'selected' : '' }}>Visitor</option>
                </select>
                @error('role')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- ✅ Secret Questions for Password/User ID Recovery -->
            <h4 class="mt-4">Security Questions</h4>

            <div class="form-group">
                <label for="secret_question1">Your Pet's Name:</label>
                <input type="text" name="secret_question1" class="form-control @error('secret_question1') is-invalid @enderror" value="{{ old('secret_question1') }}" required>
                @error('secret_question1')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="secret_question2">Your Favorite Color:</label>
                <input type="text" name="secret_question2" class="form-control @error('secret_question2') is-invalid @enderror" value="{{ old('secret_question2') }}" required>
                @error('secret_question2')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="secret_question3">Your Favorite Food:</label>
                <input type="text" name="secret_question3" class="form-control @error('secret_question3') is-invalid @enderror" value="{{ old('secret_question3') }}" required>
                @error('secret_question3')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- ✅ Refreshable CAPTCHA for Anti-Bot Protection -->
            <div class="form-group">
                <label for="captcha">Anti-Bot Verification:</label>
                <div class="d-flex align-items-center">
                    <input type="text" name="captcha" id="captcha" class="form-control @error('captcha') is-invalid @enderror" required>
                    <button type="button" class="btn btn-secondary ms-2" onclick="refreshCaptcha()">🔄</button>
                </div>
                <small id="captcha-question" class="form-text text-muted">What is 7 + 3?</small>
                @error('captcha')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <input type="hidden" name="created_by" value="{{ auth()->id() }}">

            <button type="submit" class="btn btn-primary mt-3">Create User</button>
        </form>

        <a href="{{ route('users.index') }}" class="btn btn-secondary mt-3">Back to Users</a>
    </div>
</div>

<script>
    function refreshCaptcha() {
        const captchaQuestions = [
            { question: "What is 7 + 3?", answer: "10" },
            { question: "What is 8 - 2?", answer: "6" },
            { question: "What is 5 + 4?", answer: "9" },
            { question: "What is 6 + 2?", answer: "8" },
            { question: "What is 10 - 3?", answer: "7" }
        ];

        let randomIndex = Math.floor(Math.random() * captchaQuestions.length);
        document.getElementById("captcha-question").innerText = captchaQuestions[randomIndex].question;
        document.getElementById("captcha").setAttribute("data-answer", captchaQuestions[randomIndex].answer);
    }
</script>
@endsection
