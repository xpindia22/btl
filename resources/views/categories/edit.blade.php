@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Category</h1>

    @if($errors->any())
       <div class="alert alert-danger">
         <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
         </ul>
       </div>
    @endif

    <form method="POST" action="{{ route('categories.update', $category->id) }}" class="form-styled">
         @csrf
         @method('PUT')
         <div class="form-group">
              <label for="name">Category Name:</label>
              <input type="text" name="name" id="name" required value="{{ old('name', $category->name) }}">
         </div>
         <div class="form-group">
              <label for="age_condition">Age Group Condition:</label>
              <select name="age_condition" id="age_condition" required onchange="toggleAgeInputs()">
                   <option value="Under" {{ (old('age_condition', $age_condition) == 'Under') ? 'selected' : '' }}>Under</option>
                   <option value="Over" {{ (old('age_condition', $age_condition) == 'Over') ? 'selected' : '' }}>Over</option>
                   <option value="Between" {{ (old('age_condition', $age_condition) == 'Between') ? 'selected' : '' }}>Between</option>
                   <option value="Open" {{ (old('age_condition', $age_condition) == 'Open') ? 'selected' : '' }}>Open</option>
              </select>
         </div>
         <div class="form-group" id="age_limit1_group">
              <label for="age_limit1">Age Limit 1:</label>
              <input type="number" name="age_limit1" id="age_limit1" required value="{{ old('age_limit1', $age_limit1) }}">
         </div>
         <div class="form-group" id="age_limit2_group" style="display: {{ (old('age_condition', $age_condition) == 'Between') ? 'block' : 'none' }};">
              <label for="age_limit2">Age Limit 2:</label>
              <input type="number" name="age_limit2" id="age_limit2" value="{{ old('age_limit2', $age_limit2) }}">
         </div>
         <div class="form-group">
              <label for="sex">Sex:</label>
              <select name="sex" id="sex" required>
                   <option value="M" {{ (old('sex', $category->sex) == 'M') ? 'selected' : '' }}>Male</option>
                   <option value="F" {{ (old('sex', $category->sex) == 'F') ? 'selected' : '' }}>Female</option>
                   <option value="Mixed" {{ (old('sex', $category->sex) == 'Mixed') ? 'selected' : '' }}>Mixed Doubles</option>
              </select>
         </div>
         <button type="submit" class="btn btn-primary">Update Category</button>
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
    document.addEventListener('DOMContentLoaded', toggleAgeInputs);
</script>
@endsection
