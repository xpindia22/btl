@extends('layouts.app')

@section('content')
<div class="container">
    <h1>All Categories</h1>

    @if(session('message'))
         <div class="alert alert-success">{{ session('message') }}</div>
    @endif

    <table class="table table-bordered">
         <thead>
              <tr>
                   <th><a href="?order_by=id&order_dir={{ $next_order_dir }}">ID</a></th>
                   <th><a href="?order_by=name&order_dir={{ $next_order_dir }}">Name</a></th>
                   <th><a href="?order_by=age_group&order_dir={{ $next_order_dir }}">Age Group</a></th>
                   <th><a href="?order_by=sex&order_dir={{ $next_order_dir }}">Sex</a></th>
                   <th><a href="?order_by=creator_name&order_dir={{ $next_order_dir }}">Created By</a></th>
                   <th>Actions</th>
              </tr>
         </thead>
         <tbody>
              @foreach($categories as $category)
              <tr>
                   <td>{{ $category->id }}</td>
                   <td>{{ $category->name }}</td>
                   <td>{{ $category->age_group }}</td>
                   <td>{{ $category->sex }}</td>
                   <td>{{ $category->creator_name ?? '' }}</td>
                   <td>
                        <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('categories.destroy', $category->id) }}" method="POST" style="display:inline-block;">
                             @csrf
                             @method('DELETE')
                             <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this category?')">Delete</button>
                        </form>
                   </td>
              </tr>
              @endforeach
         </tbody>
    </table>
</div>
@endsection
