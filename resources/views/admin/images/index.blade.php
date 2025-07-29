<x-app-layout>

@section('admin-content')
    <h2 class="text-3xl font-bold mb-6 text-green-800">Manage Images</h2>

    <!-- Upload Image Form -->
    <form action="{{ route('images.store') }}" method="POST" enctype="multipart/form-data" class="mb-6">
        @csrf
        <div class="mb-4">
            <label for="image" class="block text-gray-700">Upload Image</label>
            <input type="file" name="image" id="image" accept="image/*" required>
            @error('image') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>
        <button type="submit" class="button">Upload Image</button>
    </form>

    <!-- Image List -->
    <div class="grid grid-cols-4 gap-4">
        @foreach ($images as $image)
            <div class="relative">
                <img src="{{ asset('assets/img/' . $image) }}" alt="Image" class="w-full h-32 object-cover rounded">
                <form action="{{ route('images.destroy', $image) }}" method="POST" class="absolute top-0 right-0">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-500 p-1" onclick="return confirm('Are you sure?')">&times;</button>
                </form>
            </div>
        @endforeach
    </div>
@endsection