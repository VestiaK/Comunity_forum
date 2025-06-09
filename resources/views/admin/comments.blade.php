<x-app-layout>
<div class="container mx-auto py-8">
    <h1 class="text-2xl font-bold mb-6 text-gray-900 dark:text-white">Manajemen Komentar</h1>
    <div class="relative overflow-x-auto">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 border border-gray-200 dark:border-gray-700">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th class="px-6 py-3">ID</th>
                    <th class="px-6 py-3">Isi Komentar</th>
                    <th class="px-6 py-3">Post</th>
                    <th class="px-6 py-3">User</th>
                    <th class="px-6 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
            @foreach($comments as $comment)
                <tr class="border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
                    <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ $comment->id }}</td>
                    <td class="px-6 py-4">{{ $comment->body }}</td>
                    <td class="px-6 py-4">
                        <a href="{{ route('posts.show', $comment->post) }}" class="text-blue-600 hover:underline">{{ $comment->post->name ?? '-' }}</a>
                    </td>
                    <td class="px-6 py-4">{{ $comment->user->name ?? '-' }}</td>
                    <td class="px-6 py-4 flex gap-2">
                        <a href="{{ route('posts.show', $comment->post) }}#comment-{{ $comment->id }}" class="bg-blue-600 text-white px-2 py-1 rounded">Lihat</a>
                        <a href="{{ route('admin.editComment', $comment->id) }}" class="bg-yellow-500 text-white px-2 py-1 rounded">Edit</a>
                        <form method="POST" action="{{ route('admin.deleteComment', $comment->id) }}">
                            @csrf
                            @method('DELETE')
                            <button class="bg-red-600 text-white px-2 py-1 rounded" onclick="return confirm('Yakin hapus komentar ini?')">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>    <div class="mt-4 flex justify-end items-center">
        <div>
            @if($comments->onFirstPage())
                <span class="px-4 py-2 bg-gray-900 text-gray-500 rounded">Previous</span>
            @else
                <a href="{{ $comments->previousPageUrl() }}" class="px-4 py-2 bg-purple-600 text-white rounded hover:bg-purple-700">Previous</a>
            @endif
            <span class="mx-2 text-gray-900 dark:text-gray-200">Page {{ $comments->currentPage() }} of {{ $comments->lastPage() }}</span>
            @if($comments->hasMorePages())
                <a href="{{ $comments->nextPageUrl() }}" class="px-4 py-2 bg-purple-600 text-white rounded hover:bg-purple-700">Next</a>
            @else
                <span class="px-4 py-2 bg-gray-200 text-gray-500 rounded">Next</span>
            @endif
        </div>
    </div>
    <div id="modal-comment" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                    <h2 id="modal-title-comment" class="text-lg font-semibold text-gray-900 dark:text-white">Edit Komentar</h2>
                    <button id="close-modal-comment" type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <form id="form-comment" class="p-4 md:p-5" method="POST">
                    @csrf
                    <input type="hidden" id="comment_id" name="comment_id" value="">
                    <div class="mb-4">
                        <label for="body" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Isi Komentar</label>
                        <textarea id="body" name="body" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500" placeholder="Tulis komentar di sini" required></textarea>
                    </div>
                    <div class="flex justify-center">
                        <button type="submit" class="text-white inline-flex items-center bg-purple-700 hover:bg-purple-800 focus:ring-4 focus:outline-none focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-purple-600 dark:hover:bg-purple-700 dark:focus:ring-purple-800">
                            <svg class="me-1 -ms-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path></svg>
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Helper: get CSRF token from meta or blade
    function getCsrfToken() {
        return document.querySelector('meta[name="csrf-token"]')?.content || document.querySelector('input[name="_token"]')?.value;
    }

    // Open modal and fetch comment data
    function openEditCommentModal(commentId) {
        fetch(`/admin/comments/${commentId}/edit`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            document.getElementById('comment_id').value = data.id;
            document.getElementById('body').value = data.body;
            document.getElementById('modal-comment').classList.remove('hidden');
        });
    }

    // Close modal
    function closeEditCommentModal() {
        document.getElementById('modal-comment').classList.add('hidden');
    }

    document.querySelectorAll('a.bg-yellow-500').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            // Extract comment id from href (support /admin/comments/{id}/edit)
            const match = this.href.match(/\/comments\/(\d+)\/edit/);
            if (match) openEditCommentModal(match[1]);
        });
    });

    document.getElementById('close-modal-comment').onclick = closeEditCommentModal;

    document.getElementById('form-comment').onsubmit = function(e) {
        e.preventDefault();
        const id = document.getElementById('comment_id').value;
        const body = document.getElementById('body').value;
        fetch(`/admin/comments/${id}/edit`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': getCsrfToken(),
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ body })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                // Update comment in table
                const row = Array.from(document.querySelectorAll('tr')).find(tr => tr.querySelector(`a.bg-yellow-500[href$='/comments/${id}/edit']`));
                if (row) row.querySelectorAll('td')[1].textContent = body;
                closeEditCommentModal();
            } else {
                alert('Gagal update komentar!');
            }
        });
    };
});
</script>
</x-app-layout>
