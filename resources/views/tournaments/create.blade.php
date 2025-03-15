@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Create Tournament</h3>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('tournaments.store') }}">
        @csrf

        <!-- Tournament Name -->
        <div class="form-group">
            <label for="tournament_name">Tournament Name:</label>
            <input type="text" name="tournament_name" id="tournament_name" class="form-control" required>
        </div>

        <!-- Select Categories -->
        <div class="form-group">
            <label>Select Categories:</label>
            @foreach ($categories as $category)
                <div class="form-check">
                    <input type="checkbox" name="categories[{{ $category->id }}][selected]" 
                           id="category_{{ $category->id }}" 
                           class="form-check-input" 
                           onchange="toggleCategoryPayment('{{ $category->id }}')">
                    <label class="form-check-label" for="category_{{ $category->id }}">
                        {{ $category->name }}
                    </label>

                    <!-- Paid/Free Toggle -->
                    <select name="categories[{{ $category->id }}][is_paid]" 
                            id="paid_{{ $category->id }}" 
                            class="form-control form-control-sm d-none mt-2" 
                            onchange="toggleFeeInput('{{ $category->id }}')">
                        <option value="0" selected>Free</option>
                        <option value="1">Paid</option>
                    </select>

                    <!-- Fee Input (Only If Paid) -->
                    <input type="number" name="categories[{{ $category->id }}][fee]" 
                           id="fee_{{ $category->id }}" 
                           class="form-control form-control-sm d-none mt-2" 
                           step="0.01" min="0" 
                           placeholder="Enter fee in ₹">
                </div>
            @endforeach
        </div>

        <!-- Select Moderators -->
        <div class="form-group">
            <label for="moderators">Select Moderators:</label>
            <select name="moderators[]" id="moderators" class="form-control" multiple>
                @foreach ($moderators as $moderator)
                    <option value="{{ $moderator->id }}">{{ $moderator->username }}</option>
                @endforeach
            </select>
            <small>Hold Ctrl (Windows) or Cmd (Mac) to select multiple moderators.</small>
        </div>

        <button type="submit" class="btn btn-success">Create Tournament</button>
        <a href="{{ route('tournaments.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>

<script>
function toggleCategoryPayment(categoryId) {
    let paidSelect = document.getElementById('paid_' + categoryId);
    let categoryCheckbox = document.getElementById('category_' + categoryId);
    
    if (categoryCheckbox.checked) {
        paidSelect.classList.remove('d-none');
    } else {
        paidSelect.classList.add('d-none');
        document.getElementById('fee_' + categoryId).classList.add('d-none');
    }
}

function toggleFeeInput(categoryId) {
    let feeInput = document.getElementById('fee_' + categoryId);
    let paidSelect = document.getElementById('paid_' + categoryId);
    
    if (paidSelect.value == "1") {
        feeInput.classList.remove('d-none');
    } else {
        feeInput.classList.add('d-none');
    }
}
</script>

@endsection
