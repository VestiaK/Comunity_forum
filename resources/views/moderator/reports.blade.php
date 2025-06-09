<x-app-layout>
<div class="container mx-auto py-8">
    <h1 class="text-2xl font-bold mb-6 text-gray-900 dark:text-white">Laporan Konten</h1>
    @if(session('success'))
        <div class="mb-4 p-2 bg-green-200 text-green-800 rounded">{{ session('success') }}</div>
    @endif
    <div class="relative overflow-x-auto">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 border border-gray-200 dark:border-gray-700">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th class="px-6 py-3">ID</th>
                    <th class="px-6 py-3">Dilaporkan Oleh</th>
                    <th class="px-6 py-3">Tipe</th>
                    <th class="px-6 py-3">Alasan</th>
                    <th class="px-6 py-3">Isi Konten</th>
                    <th class="px-6 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
            @foreach($reports as $report)
                <tr class="border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
                    <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ $report->id }}</td>
                    <td class="px-6 py-4">{{ $report->user->name ?? '-' }}</td>
                    <td class="px-6 py-4">{{ class_basename($report->reportable_type) }}</td>
                    <td class="px-6 py-4">{{ $report->reason }}</td>
                    <td class="px-6 py-4">{{ $report->reportable ? ($report->reportable->body ?? $report->reportable->name ?? '-') : '-' }}</td>
                    <td class="px-6 py-4 flex gap-2">
                        @if($report->reportable)
                            @php
                                $type = strtolower(class_basename($report->reportable_type));
                                $id = $report->reportable_id;
                            @endphp
                            <form method="POST" action="{{ route('moderator.deleteContent', ['type'=>$type, 'id'=>$id]) }}">
                                @csrf
                                @method('DELETE')
                                <button class="bg-red-600 text-white px-2 py-1 rounded" onclick="return confirm('Yakin hapus konten?')">Hapus Konten</button>
                            </form>
                            @if(auth()->check() && auth()->user()->isAdmin())
                                @if($type === 'user')
                                    <form method="POST" action="{{ route('admin.deleteUser', $id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button class="bg-red-700 text-white px-2 py-1 rounded" onclick="return confirm('Yakin hapus user ini?')">Hapus User</button>
                                    </form>
                                @elseif($type === 'category')
                                    <form method="POST" action="{{ route('admin.deleteCategory', $id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button class="bg-red-700 text-white px-2 py-1 rounded" onclick="return confirm('Yakin hapus kategori ini?')">Hapus Kategori</button>
                                    </form>
                                @endif
                            @endif
                        @endif
                        <form method="POST" action="{{ route('moderator.deleteReport', $report->id) }}">
                            @csrf
                            @method('DELETE')
                            <button class="bg-gray-400 text-white px-2 py-1 rounded" onclick="return confirm('Hapus laporan ini?')">Hapus Laporan</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $reports->links() }}</div>
</div>
</x-app-layout>
