<x-app-layout title="post">
    @push('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @endpush
    <main class="pt-8 pb-16 lg:pt-16 lg:pb-24 bg-white dark:bg-gray-900 antialiased">
        <div class="flex justify-between px-4 mx-auto max-w-screen-xl ">
            <article class="mx-auto w-full max-w-2xl format format-sm sm:format-base lg:format-lg format-blue dark:format-invert">
                <header class="mb-4 lg:mb-6 not-format">
                    <address class="flex items-center mb-6 not-italic">
                        <div class="inline-flex items-center mr-3 text-sm text-gray-900 dark:text-white">
                            <img class="mr-4 w-16 h-16 rounded-full" src="https://flowbite.com/docs/images/people/profile-picture-2.jpg" alt="Jese Leos">
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
                <a href="/posts" class="mt-4 font-medium text-blue-600 hover:underline">&laquo; Back To posts </a>
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
                class="inline-flex items-center py-2.5 px-4 text-xs font-medium text-center text-white bg-blue-700 rounded-lg focus:ring-4 focus:ring-blue-200 dark:focus:ring-blue-900 hover:bg-blue-800">
                Kirim Komentar
                </button>
            </form>
            @endauth
            @foreach($post->comments()->latest()->get() as $comment)
            <article class="p-6 mb-6 text-base bg-white rounded-lg dark:bg-gray-900">
                <footer class="flex justify-between items-center mb-2">
                    <div class="flex items-center">
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
                            class="vote-button inline-flex items-center p-2 text-sm font-medium text-gray-500 bg-white rounded-lg hover:bg-gray-100 hover:text-blue-500 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:bg-gray-900 dark:hover:bg-gray-700 {{ auth()->user()->hasVotedOnComment($comment->id) === 'upvote' ? 'cursor-not-allowed opacity-50' : '' }}"
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
            </article>
            @endforeach

            <!-- Comment Edit Modal -->
            <div id="modal-comment" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
                <div class="bg-white dark:bg-gray-800 rounded-lg p-6 w-full max-w-lg relative">
                    <button id="close-comment-modal" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700">&times;</button>
                    <h2 id="modal-title" class="text-xl font-bold mb-4 text-gray-900 dark:text-white">Edit Comment</h2>
                    <form id="form-comment">
                        @csrf
                        <input type="hidden" id="comment_id" name="comment_id">
                        <div class="mb-4">
                            <label for="comment_body" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Comment</label>
                            <textarea id="comment_body" name="body" rows="4"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                required></textarea>
                        </div>
                        <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            Update Comment
                        </button>
                    </form>
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
        document.querySelectorAll('.btn-delete-comment').forEach(button => {
            button.addEventListener('click', function() {
                if (!confirm('Are you sure you want to delete this comment?')) return;
                
                const id = this.dataset.id;
                const formData = new FormData();
                formData.append('_method', 'DELETE');
                formData.append('_token', csrfToken);

                fetch(`/comments/${id}`, {
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
            });
        });

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
    });
    </script>
</x-app-layout>