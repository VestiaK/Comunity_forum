<x-app-layout>
    <div class="max-w-2xl mx-auto mt-10 p-6 bg-white rounded-lg shadow-md border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Reputasi Diskusi</h2>
        <div class="mb-6">
            <div class="flex items-center space-x-4">
                <img class="w-14 h-14 rounded-full border-2 border-primary-500" src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=3b82f6&color=fff" alt="Profile Picture">
                <div>
                    <div class="text-lg font-semibold text-gray-900 dark:text-white">{{ auth()->user()->name }}</div>
                    <div class="text-gray-500 dark:text-gray-300 text-sm">{{ auth()->user()->email }}</div>
                </div>
            </div>
        </div>
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <span class="text-lg font-medium text-gray-900 dark:text-white">Total Reputasi</span>
                <span class="px-4 py-2 rounded-lg bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300 font-bold text-xl">{{ auth()->user()->reputation_points }}</span>
            </div>
        </div>
        <div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Riwayat Reputasi</h3>
            <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach(auth()->user()->comments()->with('user')->orderByDesc('updated_at')->get() as $comment)
                    @if($comment->reputation_points != 0)
                    <li class="py-3 flex justify-between items-center">
                        <span class="text-gray-800 dark:text-gray-200">
                            {{ $comment->reputation_points > 0 ? '+' : '' }}{{ $comment->reputation_points }} 
                            pada komentar: "{{ \Illuminate\Support\Str::limit($comment->body, 40) }}"
                        </span>
                        <span class="text-sm text-gray-500 dark:text-gray-400">{{ $comment->updated_at->diffForHumans() }}</span>
                    </li>
                    @endif
                @endforeach
            </ul>
        </div>
    </div>
</x-app-layout>
