@extends('layout')

@section('content')
<h2>Edit Pet</h2>
<form action="{{ route('pets.update', $pet['id']) }}" method="POST">
    @csrf @method('PUT')
    <div class="mb-2">
        <label>Name:</label>
        <input type="text" name="name" value="{{ $pet['name'] ?? '' }}" class="form-control" required>
    </div>
    <div class="mb-2">
        <label>Photo URLs:</label>
        <div id="photo-urls-list">
            @if(!empty($pet['photoUrls']))
            @foreach($pet['photoUrls'] as $url)
            <input type="url" name="photoUrls[]" value="{{ $url }}" class="form-control mb-2">
            @endforeach
            @else
            <input type="url" name="photoUrls[]" class="form-control mb-2">
            @endif
        </div>
        <div class="mb-2">
            <button type="button" onclick="addPhotoUrlInput()">Add another photo</button>
        </div>
        <div>
            @foreach($pet['photoUrls'] as $index => $url)
            <img src="{{ $url }}" alt="pet" width="100" class="border">
            @endforeach
        </div>
    </div>
    <div class="mb-2">
        <label>Category ID:</label>
        <input type="number" name="category_id" value="{{ $pet['category']['id'] ?? '' }}" class="form-control">
    </div>
    <div class="mb-2">
        <label>Category Name:</label>
        <input type="text" name="category_name" value="{{ $pet['category']['name'] ?? '' }}" class="form-control">
    </div>
    <label>Tags:</label>
    <div id="tags-list">
        @if(!empty($pet['tags']))
        @foreach($pet['tags'] as $tag)
        <div class="mb-2">
            <input type="number" name="tags[{{$loop->index}}][id]" value="{{ $tag['id'] ?? '' }}" placeholder="Tag ID"
                class="form-control d-inline w-25">
            <input type="text" name="tags[{{$loop->index}}][name]" value="{{ $tag['name'] ?? '' }}"
                placeholder="Tag Name" class="form-control d-inline w-50">
        </div>
        @endforeach
        @else
        <div class="mb-2">
            <input type="number" name="tags[0][id]" placeholder="Tag ID" class="form-control d-inline w-25">
            <input type="text" name="tags[0][name]" placeholder="Tag Name" class="form-control d-inline w-50">
        </div>
        @endif

    </div>
    <div class="mb-2">
        <button type="button" onclick="addTagInput()">Add another tag</button>
    </div>
    <div class="mb-2">
        <label>Status:</label>
        <select name="status" class="form-control">
            <option value="available" {{ ($pet['status'] ?? '') == 'available' ? 'selected' : '' }}>Available
            </option>
            <option value="pending" {{ ($pet['status'] ?? '') == 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="sold" {{ ($pet['status'] ?? '') == 'sold' ? 'selected' : '' }}>Sold</option>
        </select>
    </div>
    <button class="btn btn-primary">Update</button>
    <a href="{{ route('pets.index') }}" class="btn btn-secondary">Back</a>
</form>
<h4 class="mt-5">Upload new image</h4>
<form class="mt-2" action="{{ route('pets.uploadImage', $pet['id']) }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="file" name="photo" class="form-control mb-2" required>
    <button class="btn btn-primary">Upload Image</button>
</form>
@endsection
<script>
function addPhotoUrlInput() {
    const div = document.getElementById('photo-urls-list');
    const input = document.createElement('input');
    input.type = 'url';
    input.name = 'photoUrls[]';
    input.className = 'form-control mb-2';
    div.appendChild(input);
}
</script>
<script>
function addTagInput() {
    const div = document.getElementById('tags-list');
    const index = div.children.length;
    const wrapper = document.createElement('div');
    wrapper.className = 'mb-2';
    wrapper.innerHTML =
        `<input type="number" name="tags[${index}][id]" placeholder="Tag ID" class="form-control d-inline w-25">
                         <input type="text" name="tags[${index}][name]" placeholder="Tag Name" class="form-control d-inline w-50">`;
    div.appendChild(wrapper);
}
</script>
