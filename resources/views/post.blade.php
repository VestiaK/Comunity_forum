<x-app-layout title="post">
    @push('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @endpush
    <main class="pt-8 pb-16 lg:pt-16 lg:pb-24 bg-white dark:bg-gray-900 antialiased">
        <div class="flex justify-between px-4 mx-auto max-w-screen-xl ">
            <article class="mx-auto w-full max-w-2xl format format-sm sm:format-base lg:format-lg format-purple dark:format-invert">
                <header class="mb-4 lg:mb-6 not-format">
                    <address class="flex items-center mb-6 not-italic">
                        <div class="inline-flex items-center mr-3 text-sm text-gray-900 dark:text-white">
                            <img class="mr-4 w-16 h-16 rounded-full" src="{{ asset('images/soyu-bg.jpg') }}" >
                            <div>
                                <a href="/posts?asker{{$post->asker->username}}" rel="author" class="text-xl font-bold text-gray-900 dark:text-white">{{$post->asker->name}}</a>
                                
                                <a href="/posts?category={{$post->category->slug}}">
                                    <p class="inline-block rounded bg-{{$post->category->color}}-100 text-base text-gray-500 dark:text-gray-400">{{$post->category->name}}</p>
                                </a>
                                <p class="text-base text-gray-500 dark:text-gray-400"><time pubdate datetime="2022-02-08" title="February 8th, 2022">{{$post->created_at->diffForHumans()}}</time></p>
                            </div>
                        </div>
                        @if(auth()->check() && auth()->id() === $post->user_id)
                        <div class="flex gap-2">
                            <button type="button" class="px-3 py-1 bg-yellow-400 text-white rounded hover:bg-yellow-500 btn-edit" data-id="{{ $post->id }}">
                                Edit
                            </button>
                            <button type="button" class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 btn-delete" data-id="{{ $post->id }}">
                                Delete
                            </button>
                        </div>
                        @endif
                    </address>
                    <h1 class="mb-4 text-3xl font-extrabold leading-tight text-gray-900 lg:mb-6 lg:text-4xl dark:text-white">{{$post->name}}</h1>
                </header>
                <p class="lead text-gray-800 dark:text-gray-200">{{$post->body}}</p>
                <div class="flex justify-between items-center">
                    <a href="/posts" class="mt-4 font-medium text-purple-600 hover:underline">&laquo; Back To posts </a>
                    @auth
                    @if(auth()->id() !== $post->user_id)
                    <button class="px-3 py-1 bg-orange-500 text-red-600 font-bold rounded hover:bg-orange-600 btn-report ml-4" data-type="post" data-id="{{ $post->id }}">Laporkan Post</button>
                    @endif
                    @endauth
                </div>
            </article>
        </div>
    </main>
    
    <!-- Comments Section -->
    <div class="flex justify-center">
        <section class="not-format w-full max-w-2xl">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-lg lg:text-2xl font-bold text-gray-900 dark:text-white">Discussion ({{ $post->comments()->count() }})</h2>
            </div>
            @auth
            <form class="mb-6" method="POST" action="{{ route('comments.store', $post) }}">
                @csrf
                <div class="py-2 px-4 mb-4 bg-white rounded-lg rounded-t-lg border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
                <label for="comment" class="sr-only">Your comment</label>
                <textarea id="comment" name="body" rows="4"
                    class="px-0 w-full text-sm text-gray-900 border-0 focus:ring-0 dark:text-white dark:placeholder-gray-400 dark:bg-gray-800"
                    placeholder="Tulis komentar..." required>{{ old('body') }}</textarea>
                @error('body')
                    <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                @enderror
                </div>
                <button type="submit"
                class="inline-flex items-center py-2.5 px-4 text-xs font-medium text-center text-white bg-purple-700 rounded-lg focus:ring-4 focus:ring-purple-200 dark:focus:ring-purple-900 hover:bg-purple-800">
                Kirim Komentar
                </button>
            </form>
            @endauth
            @foreach($post->comments()->latest()->get() as $comment)
            <article class="p-6 mb-6 text-base bg-white rounded-lg dark:bg-gray-900">
                <footer class="flex justify-between items-center mb-2">
                    <div class="flex items-center">
                        <img class="mr-4 w-10 h-10 rounded-full" src="{{ asset('images/soyu-bg.jpg') }}">
                        <p class="inline-flex items-center mr-3 text-sm text-gray-900 dark:text-white font-semibold">
                            {{ $comment->user->name }}
                        </p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            <time>{{ $comment->created_at->diffForHumans() }}</time>
                        </p>
                    </div>
                    @if(auth()->check() && auth()->id() === $comment->user_id)
                    <div class="relative">
                        <button id="dropdownComment{{ $comment->id }}Button" data-dropdown-toggle="dropdownComment{{ $comment->id }}"
                            class="inline-flex items-center p-2 text-sm font-medium text-center text-gray-500 dark:text-gray-400 bg-white rounded-lg hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-50 dark:bg-gray-900 dark:hover:bg-gray-700 dark:focus:ring-gray-600"
                            type="button">
                            <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 3">
                                <path d="M2 0a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3Zm6.041 0a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM14 0a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3Z"/>
                            </svg>
                            <span class="sr-only">Comment settings</span>
                        </button>
                        <div id="dropdownComment{{ $comment->id }}"
                            class="hidden z-10 w-36 bg-white rounded divide-y divide-gray-100 shadow dark:bg-gray-700 dark:divide-gray-600">
                            <ul class="py-1 text-sm text-gray-700 dark:text-gray-200">
                                <li>
                                    <button type="button" data-id="{{ $comment->id }}"
                                        class="btn-edit-comment w-full text-left block py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                        Edit
                                    </button>
                                </li>
                                <li>
                                    <button type="button" data-id="{{ $comment->id }}"
                                        class="btn-delete-comment w-full text-left block py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                        Delete
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div>
                    @endif
                </footer>
                <p class="text-gray-800 dark:text-white mb-2">{{ $comment->body }}</p>
                @auth
                <div class="flex items-center mt-4 space-x-4">
                    <div class="flex items-center">
                        <button type="button"
                            class="vote-button inline-flex items-center p-2 text-sm font-medium text-gray-500 bg-white rounded-lg hover:bg-gray-100 hover:text-purple-500 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:bg-gray-900 dark:hover:bg-gray-700 {{ auth()->user()->hasVotedOnComment($comment->id) === 'upvote' ? 'cursor-not-allowed opacity-50' : '' }}"
                            data-comment-id="{{ $comment->id }}"
                            data-vote-type="upvote"
                            {{ auth()->user()->hasVotedOnComment($comment->id) === 'upvote' ? 'disabled' : '' }}>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                            </svg>
                            <span class="ml-1">+10</span>
                        </button>
                        <button type="button"
                            class="vote-button inline-flex items-center p-2 text-sm font-medium text-gray-500 bg-white rounded-lg hover:bg-gray-100 hover:text-red-500 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:bg-gray-900 dark:hover:bg-gray-700 {{ auth()->user()->hasVotedOnComment($comment->id) === 'downvote' ? 'cursor-not-allowed opacity-50' : '' }}"
                            data-comment-id="{{ $comment->id }}"
                            data-vote-type="downvote"
                            {{ auth()->user()->hasVotedOnComment($comment->id) === 'downvote' ? 'disabled' : '' }}>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                            <span class="ml-1">-2</span>
                        </button>
                    </div>
                    <div class="reputation-points text-sm text-gray-500 dark:text-gray-400">
                        Reputation: <span class="font-medium">{{ $comment->reputation_points }}</span>
                    </div>
                </div>
                @endauth

                @auth
                @if($comment->user_id !== auth()->id())
                <button class="px-2 py-1 bg-orange-500 text-white rounded hover:bg-orange-600 btn-report" data-type="comment" data-id="{{ $comment->id }}">Laporkan Komentar</button>
                @endif
                @endauth
            </article>
            @endforeach

            <!-- Comment Edit Modal -->
            <div id="modal-comment" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
                <div class="relative p-4 w-full max-w-md max-h-full">
                    <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
                        <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                            <h2 id="modal-title" class="text-lg font-semibold text-gray-900 dark:text-white">Edit Comment</h2>
                            <button id="close-comment-modal" type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>
                        <form id="form-comment" class="p-4 md:p-5">
                            @csrf
                            <input type="hidden" id="comment_id" name="comment_id">
                            <div class="mb-4">
                                <label for="comment_body" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Comment</label>
                                <textarea id="comment_body" name="body" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500" placeholder="Edit komentar di sini" required></textarea>
                            </div>
                            <button type="submit" class="text-white inline-flex items-center bg-purple-700 hover:bg-purple-800 focus:ring-4 focus:outline-none focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-purple-600 dark:hover:bg-purple-700 dark:focus:ring-purple-800">
                                <svg class="me-1 -ms-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path></svg>
                                Update Comment
                            </button>
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
            
            <!-- Comment Delete Confirmation Modal -->
            <div id="popup-modal" tabindex="-1" class="hidden fixed inset-0 z-50 flex justify-center items-center bg-black bg-opacity-50">
                <div class="relative p-4 w-full max-w-md max-h-full flex items-center justify-center">
                    <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
                        <button id="close-delete-modal" type="button" class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                        <div class="p-4 md:p-5 text-center">
                            <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                            </svg>
                            <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Are you sure you want to delete this comment?</h3>
                            <button id="confirm-delete-comment" type="button" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
                                Yes, I'm sure
                            </button>
                            <button id="cancel-delete-comment" type="button" class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">No, cancel</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Post Edit Modal -->
            <div id="modal-post" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
                <div class="relative p-4 w-full max-w-md max-h-full">
                    <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
                        <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                            <h2 id="modal-title" class="text-lg font-semibold text-gray-900 dark:text-white">Edit Post</h2>
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
            </section>
        </div>    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const commentModal = document.getElementById('modal-comment');
        const commentForm = document.getElementById('form-comment');
        const closeCommentModal = document.getElementById('close-comment-modal');
        const commentBody = document.getElementById('comment_body');
        const commentId = document.getElementById('comment_id');
        const csrfToken = document.querySelector('[name=_token]').value;

        // Edit Comment Button Click
        document.querySelectorAll('.btn-edit-comment').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.dataset.id;
                fetch(`/comments/${id}/edit`)
                    .then(response => response.json())
                    .then(data => {
                        commentId.value = id;
                        commentBody.value = data.body;
                        commentModal.classList.remove('hidden');
                    })
                    .catch(error => {
                        alert('Error fetching comment data');
                        console.error('Error:', error);
                    });
            });
        });

        // Close Modal
        closeCommentModal.addEventListener('click', function() {
            commentModal.classList.add('hidden');
        });

        // Submit Comment Edit
        commentForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const id = commentId.value;
            const formData = new FormData(this);
            formData.append('_method', 'PUT');

            fetch(`/comments/${id}`, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert(data.message || 'Error updating comment');
                }
            })
            .catch(error => {
                alert('Error updating comment');
                console.error('Error:', error);
            });
        });

        // Delete Comment
        let commentToDelete = null;
        document.querySelectorAll('.btn-delete-comment').forEach(button => {
            button.addEventListener('click', function() {
                commentToDelete = this.dataset.id;
                document.getElementById('popup-modal').classList.remove('hidden');
            });
        });
        document.getElementById('close-delete-modal').onclick = function() {
            document.getElementById('popup-modal').classList.add('hidden');
            commentToDelete = null;
        };
        document.getElementById('cancel-delete-comment').onclick = function() {
            document.getElementById('popup-modal').classList.add('hidden');
            commentToDelete = null;
        };
        document.getElementById('confirm-delete-comment').onclick = function() {
            if (!commentToDelete) return;
            const csrfToken = document.querySelector('[name=_token]').value;
            const formData = new FormData();
            formData.append('_method', 'DELETE');
            formData.append('_token', csrfToken);
            fetch(`/comments/${commentToDelete}`, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert(data.message || 'Error deleting comment');
                }
            })
            .catch(error => {
                alert('Error deleting comment');
                console.error('Error:', error);
            });
            document.getElementById('popup-modal').classList.add('hidden');
            commentToDelete = null;
        };

        // Voting functionality
        document.querySelectorAll('.vote-button').forEach(button => {
            button.addEventListener('click', function() {
                const commentId = this.dataset.commentId;
                const voteType = this.dataset.voteType;
                const token = document.querySelector('meta[name="csrf-token"]').content;

                fetch(`/comments/${commentId}/vote`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ type: voteType })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update reputation points display
                        const pointsDisplay = this.closest('article').querySelector('.reputation-points span');
                        pointsDisplay.textContent = data.reputation_points;
                        alert(data.message);
                    } else {
                        alert(data.message || 'Error voting');
                    }
                })
                .catch(error => {
                    alert('Error voting on comment');
                });
            });
        });

        // Report functionality
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
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
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

        // Post Edit & Delete
        const postModal = document.getElementById('modal-post');
        const postForm = document.getElementById('form-post');
        const closePostModal = document.getElementById('close-modal');
        const postIdInput = document.getElementById('post_id');
        const postNameInput = document.getElementById('name');
        const postBodyInput = document.getElementById('body');
        const postCategoryInput = document.getElementById('category_id');
        const postSlugInput = document.getElementById('slug');
        let currentPostId = null;
        // Edit Post Button
        document.querySelectorAll('.btn-edit').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.dataset.id;
                fetch(`/posts/${id}/edit`)
                    .then(response => response.json())
                    .then(data => {
                        postIdInput.value = data.id;
                        postNameInput.value = data.name;
                        postBodyInput.value = data.body;
                        postCategoryInput.value = data.category_id;
                        postSlugInput.value = data.slug;
                        postModal.classList.remove('hidden');
                        currentPostId = id;
                    })
                    .catch(error => {
                        alert('Error fetching post data');
                    });
            });
        });
        // Close Post Modal
        closePostModal.addEventListener('click', function() {
            postModal.classList.add('hidden');
        });
        // Submit Post Edit
        postForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const id = postIdInput.value;
            const formData = new FormData(this);
            formData.append('_method', 'PUT');
            fetch(`/posts/${id}`, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert(data.message || 'Error updating post');
                }
            })
            .catch(error => {
                alert('Error updating post');
            });
        });
        // Delete Post
        let postToDelete = null;
        document.querySelectorAll('.btn-delete').forEach(button => {
            button.addEventListener('click', function() {
                postToDelete = this.dataset.id;
                document.getElementById('popup-modal-post').classList.remove('hidden');
            });
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
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            const formData = new FormData();
            formData.append('_method', 'DELETE');
            formData.append('_token', csrfToken);
            fetch(`/posts/${postToDelete}`, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = '/posts';
                } else {
                    alert(data.message || 'Error deleting post');
                }
            })
            .catch(error => {
                alert('Error deleting post');
            });
            document.getElementById('popup-modal-post').classList.add('hidden');
            postToDelete = null;
        };
    });
    </script>
</x-app-layout>