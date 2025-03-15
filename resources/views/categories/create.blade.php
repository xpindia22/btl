@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Insert Category</h1>

    @if($errors->any())
       <div class="alert alert-danger">  
         <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
         </ul>
       </div>
    @endif

    <form method="POST" action="{{ route('categories.store') }}" class="form-styled">
         @csrf
         <div class="form-group">
              <label for="name">Category Name:</label>
              <input type="text" name="name" id="name" required value="{{ old('name') }}">
         </div>
         <div class="form-group">
              <label for="age_condition">Age Group Condition:</label>
              <select name="age_condition" id="age_condition" required onchange="toggleAgeInputs()">
                   <option value="Under" {{ old('age_condition') == 'Under' ? 'selected' : '' }}>Under</option>
                   <option value="Over" {{ old('age_condition') == 'Over' ? 'selected' : '' }}>Over</option>
                   <option value="Between" {{ old('age_condition') == 'Between' ? 'selected' : '' }}>Between</option>
                   <option value="Open" {{ old('age_condition') == 'Open' ? 'selected' : '' }}>Open</option>
              </select>
         </div>
         <div class="form-group" id="age_limit1_group">
              <label for="age_limit1">Age Limit 1:</label>
              <input type="number" name="age_limit1" id="age_limit1" value="{{ old('age_limit1') }}">
         </div>
         <div class="form-group" id="age_limit2_group" style="display: none;">
              <label for="age_limit2">Age Limit 2:</label>
              <input type="number" name="age_limit2" id="age_limit2" value="{{ old('age_limit2') }}">
         </div>
         <div class="form-group">
              <label for="sex">Sex:</label>
              <select name="sex" id="sex" required>
                   <option value="M" {{ old('sex') == 'M' ? 'selected' : '' }}>Male</option>
                   <option value="F" {{ old('sex') == 'F' ? 'selected' : '' }}>Female</option>
                   <option value="Mixed" {{ old('sex') == 'Mixed' ? 'selected' : '' }}>Mixed Doubles</option>
              </select>
         </div>
         
         <!-- Paid Category Option -->
         <div class="form-group">
              <label for="is_paid">Is this category paid?</label>
              <select name="is_paid" id="is_paid" required onchange="toggleAmountInput()">
                   <option value="0" {{ old('is_paid') == '0' ? 'selected' : '' }}>No</option>
                   <option value="1" {{ old('is_paid') == '1' ? 'selected' : '' }}>Yes</option>
              </select>
         </div>
         
         <!-- Amount Input (Only for Paid Categories) -->
         <div class="form-group" id="amount_group" style="display: none;">
              <label for="amount">Category Fee:</label>
              <input type="number" name="amount" id="amount" step="0.01" value="{{ old('amount') }}">
         </div>
         
         <button type="submit" class="btn btn-primary">Add Category</button>
    </form>
</div>

<script>
    function toggleAgeInputs() {
         const ageCondition = document.getElementById('age_condition').value;
         const ageLimit1Group = document.getElementById('age_limit1_group');
         const ageLimit2Group = document.getElementById('age_limit2_group');
         if (ageCondition === 'Between') {
             ageLimit1Group.style.display = 'block';
             ageLimit2Group.style.display = 'block';
         } else if (ageCondition === 'Open') {
             ageLimit1Group.style.display = 'none';
             ageLimit2Group.style.display = 'none';
         } else {
             ageLimit1Group.style.display = 'block';
             ageLimit2Group.style.display = 'none';
         }
    }
    
    function toggleAmountInput() {
        const isPaid = document.getElementById('is_paid').value;
        const amountGroup = document.getElementById('amount_group');
        if (isPaid === '1') {
            amountGroup.style.display = 'block';
        } else {
            amountGroup.style.display = 'none';
        }
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        toggleAgeInputs();
        toggleAmountInput();
    });
</script>
@endsection
