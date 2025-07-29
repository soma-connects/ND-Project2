<!-- resources/views/admin/layouts/x-app-layout.blade.php -->
<x-base-layout :title="$title" :bodyclass="$bodyclass">
    <div class="admin-container flex min-h-screen bg-gradient-to-br from-green-100 to-brown-100">
        <!-- Sidebar -->
        <aside class="w-64 bg-green-800 text-white p-6">
            <h1 class="text-2xl font-bold mb-6">Shroom Admin</h1>
            <nav>
                <ul>
                    <li><a href="{{ route('admin.products.index') }}" class="block py-2 px-4 hover:bg-green-600 rounded">Products</a></li>
                    <li><a href="{{ route('admin.images.index') }}" class="block py-2 px-4 hover:bg-green-600 rounded">Images</a></li>
                </ul>
            </nav>
        </aside>
        <!-- Main Content -->
        <main class="flex-1 p-8">
            <div class="bg-white rounded-lg shadow-lg p-6">
                @yield('admin-content')
            </div>
        </main>
    </div>
</x-base-layout>