<x-app-layout>
    <div class="mx-auto max-w-screen-sm text-center lg:mb-8 mb-6 pt-10">
        <form method="GET" action="/posts" class="mb-6 flex justify-center">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari diskusi..." class="w-1/2 px-4 py-2 rounded-l border border-gray-300 focus:outline-none focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-white" />
            @if(request('category'))
                <input type="hidden" name="category" value="{{ request('category') }}">
            @endif
            @if(request('asker'))
                <input type="hidden" name="asker" value="{{ request('asker') }}">
            @endif
            <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-r hover:bg-purple-700">Cari</button>
        </form>
        <h2 class="mb-4 text-3xl lg:text-4xl tracking-tight font-extrabold text-gray-900 dark:text-white">
            @if(request('category'))
                Diskusi di Kategori: <span class="text-purple-600">{{ $posts->first()?->category->name ?? '' }}</span>
            @elseif(request('asker'))
                Diskusi oleh: <span class="text-purple-600">{{ $posts->first()?->asker->name ?? '' }}</span>
            @else
                Semua Diskusi
            @endif
        </h2>
        @auth
        <button id="btn-create" class="mb-4 inline-block px-4 py-2 bg-purple-600 text-white rounded hover:bg-purple-700">+ Buat Post Baru</button>
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
                    <a href="/posts/{{$item['slug']}}">
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
                            @auth
                            @if($item->user_id !== auth()->id())
                            <button class="px-3 py-1 bg-orange-500 text-red-600 font-bold rounded hover:bg-orange-600 btn-report" data-type="post" data-id="{{ $item->id }}">Laporkan</button>
                            @endif
                            @endauth
                            <a href="/posts/{{$item['slug']}}" class="inline-flex items-center font-medium text-purple-600 hover:underline dark:text-purple-400 ml-2">
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
        <div class="relative p-4 w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                    <h2 id="modal-title" class="text-lg font-semibold text-gray-900 dark:text-white">Buat Post Baru</h2>
                    <button id="close-modal" type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <form id="form-post" class="p-4 md:p-5">
                    @csrf
                    <input type="hidden" id="post_id" name="post_id">
                    <input type="hidden" id="slug" name="slug">
                    <div class="grid gap-4 mb-4 grid-cols-2">
                        <div class="col-span-2">
                            <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Judul</label>
                            <input type="text" id="name" name="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" required autofocus />
                        </div>
                        <div class="col-span-2 ">
                            <label for="category_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kategori</label>
                            <select id="category_id" name="category_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-span-2">
                            <label for="body" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Isi</label>
                            <textarea id="body" name="body" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500" placeholder="Tulis isi post di sini" required></textarea>
                        </div>
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

    <!-- Modal Laporkan -->
            <div id="modal-report" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
                <div class="relative p-4 w-full max-w-md max-h-full">
                    <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
                        <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Laporkan</h2>
                            <button id="close-report-modal" type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>
                        <form id="form-report" class="p-4 md:p-5">
                            @csrf
                            <input type="hidden" id="report_type">
                            <input type="hidden" id="report_id">
                            <div class="mb-4">
                                <label for="report_reason" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Alasan Laporan</label>
                                <textarea id="report_reason" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500" placeholder="Alasan laporan" required></textarea>
                            </div>
                            <button type="submit" class="text-white inline-flex items-center bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-red-700 dark:hover:bg-red-800 dark:focus:ring-red-800">
                                <svg class="me-1 -ms-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path></svg>
                                Kirim
                            </button>
                        </form>
                    </div>
                </div>
            </div>

    <!-- Post Delete Confirmation Modal -->
    <div id="popup-modal-post" tabindex="-1" class="hidden fixed inset-0 z-50 flex justify-center items-center bg-black bg-opacity-50">
        <div class="relative p-4 w-full max-w-md max-h-full flex items-center justify-center">
            <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
                <button id="close-delete-modal-post" type="button" class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
                <div class="p-4 md:p-5 text-center">
                    <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                    </svg>
                    <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Are you sure you want to delete this post?</h3>
                    <button id="confirm-delete-post" type="button" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
                        Yes, I'm sure
                    </button>
                    <button id="cancel-delete-post" type="button" class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">No, cancel</button>
                </div>
            </div>
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
    const categoryInput = document.getElementById('category_id');
    const bodyInput = document.getElementById('body');
    const slugInput = document.getElementById('slug');

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
                    categoryInput.value = data.category_id;
                    bodyInput.value = data.body;
                    slugInput.value = data.slug;
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
                category_id: categoryInput.value,
                body: bodyInput.value,
                slug: slugInput.value
            })
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                window.location.reload();
            }
        });
    };
    let postToDelete = null;
    document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.onclick = function() {
            postToDelete = this.dataset.id;
            document.getElementById('popup-modal-post').classList.remove('hidden');
        };
    });
    document.getElementById('close-delete-modal-post').onclick = function() {
        document.getElementById('popup-modal-post').classList.add('hidden');
        postToDelete = null;
    };
    document.getElementById('cancel-delete-post').onclick = function() {
        document.getElementById('popup-modal-post').classList.add('hidden');
        postToDelete = null;
    };
    document.getElementById('confirm-delete-post').onclick = function() {
        if (!postToDelete) return;
        fetch(`/posts/${postToDelete}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name=_token]').value,
                'Accept': 'application/json',
            }
        }).then(res => res.json()).then(data => {
            if(data.success) window.location.reload();
            else alert(data.message || 'Error deleting post');
        });
        document.getElementById('popup-modal-post').classList.add('hidden');
        postToDelete = null;
    };
    document.querySelectorAll('.btn-report').forEach(btn => {
        btn.onclick = function() {
            document.getElementById('modal-report').classList.remove('hidden');
            document.getElementById('report_type').value = btn.dataset.type;
            document.getElementById('report_id').value = btn.dataset.id;
        };
    });
    document.getElementById('close-report-modal').onclick = function() {
        document.getElementById('modal-report').classList.add('hidden');
    };
    document.getElementById('form-report').onsubmit = function(e) {
        e.preventDefault();
        fetch(`/report/${report_type.value}/${report_id.value}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name=_token]').value,
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify({ reason: report_reason.value })
        }).then(res => res.json()).then(data => {
            if(data.success) {
                alert('Laporan terkirim!');
                document.getElementById('modal-report').classList.add('hidden');
            }
        });
    };
});
</script>
</x-app-layout>