<x-app-layout>
    <div class="mx-auto max-w-screen-sm text-center lg:mb-8 mb-6 pt-10">
        <form method="GET" action="/posts" class="mb-6 flex justify-center">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari diskusi..." class="w-1/2 px-4 py-2 rounded-l border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white" />
            @if(request('category'))
                <input type="hidden" name="category" value="{{ request('category') }}">
            @endif
            @if(request('asker'))
                <input type="hidden" name="asker" value="{{ request('asker') }}">
            @endif
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-r hover:bg-blue-700">Cari</button>
        </form>
        <h2 class="mb-4 text-3xl lg:text-4xl tracking-tight font-extrabold text-gray-900 dark:text-white">
            @if(request('category'))
                Diskusi di Kategori: <span class="text-blue-600">{{ $posts->first()?->category->name ?? '' }}</span>
            @elseif(request('asker'))
                Diskusi oleh: <span class="text-blue-600">{{ $posts->first()?->asker->name ?? '' }}</span>
            @else
                Semua Diskusi
            @endif
        </h2>
        @auth
        <button id="btn-create" class="mb-4 inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">+ Buat Post Baru</button>
        @endauth
    </div>
    @if(session('success'))
        <div class="max-w-xl mx-auto mb-4 p-4 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
    @endif
    @forelse ($posts as $item)
    <section  class="bg-white dark:bg-gray-900 mb-4">
        <div class="px-4 mx-auto max-w-screen-xl lg:px-6">
            <div class="grid gap-8 lg:grid-cols-1">
                <article class="p-6 bg-white rounded-lg border border-gray-200 shadow-md dark:bg-gray-800 dark:border-gray-700">
                    <a href="/posts?category={{$item->category->slug}}{{ request('asker') ? '&asker='.request('asker') : '' }}">
                    <div  class="flex justify-between items-center mb-5 text-gray-500">
                        <span class="bg-{{$item->category->color}}-100 text-primary-800 text-xs font-medium inline-flex items-center px-2.5 py-0.5 rounded dark:bg-primary-200 dark:text-primary-800">
                            {{ $item->category->name }}
                        </span>
                        <span class="text-sm">{{$item->created_at->diffForHumans()}}</span>
                    </div>
                    </a>
                    <a href="/posts/{{$item['id']}}">
                    <h2  class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">{{$item->name}}</h2>
                    </a>
                    <p class="mb-5 font-light text-gray-500 dark:text-gray-400">{{$item->body}}</p>
                    <div class="flex justify-between items-center">
                        <a href="/posts?asker={{$item->asker->username}}{{ request('category') ? '&category='.request('category') : '' }}">
                        <div class="flex items-center space-x-4">
                            <span class="font-medium dark:text-white">
                                {{ $item->asker->name }}
                            </span>
                        </div>
                        </a>
                        <div class="flex items-center space-x-2">
                            @auth
                            @if($item->user_id === auth()->id())
                                <button class="px-3 py-1 bg-yellow-400 text-white rounded hover:bg-yellow-500 btn-edit" data-id="{{ $item->id }}">Edit</button>
                                <button class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 btn-delete" data-id="{{ $item->id }}">Hapus</button>
                            @endif
                            @endauth
                            <a href="/posts/{{$item['id']}}" class="inline-flex items-center font-medium text-blue-600 hover:underline dark:text-blue-400 ml-2">
                                Read more
                                <svg class="ml-2 w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                            </a>
                        </div>
                    </div>
                </article>
            </div>
        </div>
    </section>
    @empty
  <div class="text-center py-8">
    <p class="text-lg font-medium text-gray-500 dark:text-gray-400">No Blogs Found</p>
    <a href="/posts" class="mt-4 inline-block rounded-lg bg-primary-700 px-5 py-2.5 text-sm font-medium text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">Go Back to Home</a>
  </div>
@endforelse
    <div class="mt-8 flex justify-center">
        {{ $posts->links() }}
    </div>

    <!-- Modal Create/Edit Post -->
    <div id="modal-post" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 w-full max-w-lg relative">
            <button id="close-modal" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700">&times;</button>
            <h2 id="modal-title" class="text-xl font-bold mb-4 text-gray-900 dark:text-white">Buat Post Baru</h2>
            <form id="form-post">
                @csrf
                <input type="hidden" id="post_id" name="post_id">
                <div class="mb-4">
                    <x-input-label for="name" value="Judul" />
                    <x-input-text id="name" name="name" required autofocus />
                </div>
                <div class="mb-4">
                    <x-input-label for="slug" value="Slug" />
                    <x-input-text id="slug" name="slug" required />
                </div>
                <div class="mb-4">
                    <x-input-label for="category_id" value="Kategori" />
                    <select id="category_id" name="category_id" class="block w-full p-2 border rounded">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4">
                    <x-input-label for="body" value="Isi" />
                    <textarea id="body" name="body" rows="5" class="block w-full p-2 border rounded" required></textarea>
                </div>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Simpan</button>
            </form>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Modal logic
        const modal = document.getElementById('modal-post');
        const btnCreate = document.getElementById('btn-create');
        const closeModal = document.getElementById('close-modal');
        const form = document.getElementById('form-post');
        const modalTitle = document.getElementById('modal-title');
        const postIdInput = document.getElementById('post_id');
        const nameInput = document.getElementById('name');
        const slugInput = document.getElementById('slug');
        const categoryInput = document.getElementById('category_id');
        const bodyInput = document.getElementById('body');

        if(btnCreate) btnCreate.onclick = function() {
            modal.classList.remove('hidden');
            modalTitle.textContent = 'Buat Post Baru';
            form.reset();
            postIdInput.value = '';
        };
        if(closeModal) closeModal.onclick = function() {
            modal.classList.add('hidden');
        };
        document.querySelectorAll('.btn-edit').forEach(btn => {
            btn.onclick = function() {
                fetch(`/posts/${btn.dataset.id}/edit`)
                    .then(res => res.json())
                    .then(data => {
                        modal.classList.remove('hidden');
                        modalTitle.textContent = 'Edit Post';
                        postIdInput.value = data.id;
                        nameInput.value = data.name;
                        slugInput.value = data.slug;
                        categoryInput.value = data.category_id;
                        bodyInput.value = data.body;
                    });
            };
        });
        form.onsubmit = function(e) {
            e.preventDefault();
            const id = postIdInput.value;
            const url = id ? `/posts/${id}` : '/posts';
            const method = id ? 'PUT' : 'POST';
            fetch(url, {
                method: method,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name=_token]').value,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    name: nameInput.value,
                    slug: slugInput.value,
                    category_id: categoryInput.value,
                    body: bodyInput.value
                })
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    window.location.reload();
                }
            });
        };
        document.querySelectorAll('.btn-delete').forEach(btn => {
            btn.onclick = function() {
                if(confirm('Yakin hapus post ini?')) {
                    fetch(`/posts/${btn.dataset.id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('input[name=_token]').value,
                            'Accept': 'application/json',
                        }
                    }).then(res => res.json()).then(data => {
                        if(data.success) window.location.reload();
                    });
                }
            };
        });
    });
    </script>
</x-app-layout>