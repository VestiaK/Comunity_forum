<x-app-layout>

    <div class="container mx-auto py-8">
    <h1 class="text-2xl font-bold mb-6 text-gray-900 dark:text-white">Manajemen Kategori</h1>
    <div class="relative overflow-x-auto">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 border border-gray-200 dark:border-gray-700">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th class="px-6 py-3">ID</th>
                    <th class="px-6 py-3">Nama</th>
                    <th class="px-6 py-3">Slug</th>
                    <th class="px-6 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
            @foreach($categoriesp as $category)
                <tr class="border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
                    <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ $category->id }}</td>
                    <td class="px-6 py-4">{{ $category->name }}</td>
                    <td class="px-6 py-4">{{ $category->slug }}</td>
                    <td class="px-6 py-4">
                        <form method="POST" action="{{ route('admin.deleteCategory', $category->id) }}">
                            @csrf
                            @method('DELETE')
                            <button class="bg-red-600 text-white px-2 py-1 rounded" onclick="return confirm('Yakin hapus kategori ini?')">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4 flex justify-end items-center">
        <div>
            @if($categoriesp->onFirstPage())
                <span class="px-4 py-2 bg-gray-200 text-gray-500 dark:bg-gray-900 rounded">Previous</span>
            @else
                <a href="{{ $categoriesp->previousPageUrl() }}" class="px-4 py-2 bg-purple-600 text-white rounded hover:bg-purple-700">Previous</a>
            @endif
            <span class="mx-2 text-gray-900 dark:text-gray-200">Page {{ $categoriesp->currentPage() }} of {{ $categoriesp->lastPage() }}</span>
            @if($categoriesp->hasMorePages())
                <a href="{{ $categoriesp->nextPageUrl() }}" class="px-4 py-2 bg-purple-600 text-white rounded hover:bg-purple-700">Next</a>
            @else
                <span class="px-4 py-2 bg-gray-200 text-gray-500 dark:bg-gray-900 rounded">Next</span>
            @endif
        </div>
    </div>
</div>
</x-app-layout>
