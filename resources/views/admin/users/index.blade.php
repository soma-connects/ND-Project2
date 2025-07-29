<x-base-layout title="Manage Users" bodyclass="admin-users-index">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl md:text-4xl font-bold mb-6 text-accent font-playfair tracking-tight">Manage Users</h1>

        <!-- Notifications -->
        @if (session('success'))
            <div class="mb-6 p-4 bg-green-100 text-green-800 rounded-lg shadow-md flex justify-between items-center">
                {{ session('success') }}
                <button class="text-green-800 hover:text-green-900 font-bold" onclick="this.parentElement.remove()">×</button>
            </div>
        @endif
        @if (session('error'))
            <div class="mb-6 p-4 bg-red-100 text-red-800 rounded-lg shadow-md flex justify-between items-center">
                {{ session('error') }}
                <button class="text-red-800 hover:text-red-900 font-bold" onclick="this.parentElement.remove()">×</button>
            </div>
        @endif

        <!-- Users Table -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 md:p-8 overflow-x-auto">
            <table class="w-full table-auto text-left">
                <thead>
                    <tr class="border-b dark:border-gray-600">
                        <th class="py-3 px-4 text-gray-700 dark:text-gray-300">Name</th>
                        <th class="py-3 px-4 text-gray-700 dark:text-gray-300">Email</th>
                        <th class="py-3 px-4 text-gray-700 dark:text-gray-300">Cart Items</th>
                        <th class="py-3 px-4 text-gray-700 dark:text-gray-300">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr class="border-b dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="py-3 px-4">{{ $user->name ?? 'Unknown' }}</td>
                            <td class="py-3 px-4">{{ $user->email }}</td>
                            <td class="py-3 px-4">{{ $user->carts_count }}</td>
                            <td class="py-3 px-4">
                                <a href="{{ route('admin.user-carts.show', $user->id) }}" class="inline-block px-4 py-2 bg-accent text-white rounded-md hover:bg-secondary transition-colors duration-200">View Cart</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-3 px-4 text-gray-600 dark:text-gray-400 text-center">No users found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-6">
                {{ $users->links('pagination::tailwind') }}
            </div>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="mt-6 inline-block px-6 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors duration-200">Back to Dashboard</a>
    </div>
</x-base-layout>