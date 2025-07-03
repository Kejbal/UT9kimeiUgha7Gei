@extends('layout')

@section('content')
<div class="mb-3">
    <a href="{{ route('pets.create') }}" class="btn btn-primary">Add Pet</a>
    <a href="{{ route('pets.index', ['status' => 'available']) }}"
        class="btn btn-outline-secondary {{ ($validatedStatus ?? 'available') == 'available' ? 'active' : '' }}">Available</a>
    <a href="{{ route('pets.index', ['status' => 'pending']) }}"
        class="btn btn-outline-secondary {{ ($validatedStatus ?? 'available') == 'pending' ? 'active' : '' }}">Pending</a>
    <a href="{{ route('pets.index', ['status' => 'sold']) }}"
        class="btn btn-outline-secondary {{ ($validatedStatus ?? 'available') == 'sold' ? 'active' : '' }}">Sold</a>
</div>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Image</th>
            <th>Category</th>
            <th>Tags</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($pets as $pet)
        <tr>
            <td>{{ $pet['id'] }}</td>
            <td>{{ $pet['name'] ?? '-' }}</td>
            <td>
                @if(!empty($pet['photoUrls']))
                <img src="{{ $pet['photoUrls'][0] }}" alt="{{ $pet['name'] ?? 'pet' }}" width="50">
                @endif
            </td>
            <td>{{ $pet['category']['name'] ?? '-' }}</td>
            <td>
                @if(isset($pet['tags']) && is_array($pet['tags']))
                @foreach($pet['tags'] as $tag)
                <span>{{ $tag['name'] ?? '-' }}</span><br>
                @endforeach
                @else
                <span>-</span>
                @endif

            </td>
            <td>{{ $pet['status'] ?? '-' }}</td>
            <td>
                <a href="{{ route('pets.edit', $pet['id']) }}" class="btn btn-warning btn-sm">Edit</a>
                <form action="{{ route('pets.destroy', $pet['id']) }}" method="POST" class="d-inline">
                    @csrf @method('DELETE')
                    <button onclick="return confirm('Delete this pet?')" class="btn btn-danger btn-sm">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
