<x-app-layout>
    <div class="max-w-2xl mx-auto mt-10 p-6 bg-white rounded-lg shadow-md border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
        <form method="POST" action="{{ route('profile.update') }}" class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6 border border-gray-200 dark:border-gray-700">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <x-input-label for="name" value="Nama" />
                    <x-input-text id="name" name="name" value="{{ old('name', auth()->user()->name) }}" required />
                    <x-input-error :messages="$errors->get('name')" />
                </div>
                <div>
                    <x-input-label for="username" value="Username" />
                    <x-input-text id="username" name="username" value="{{ old('username', auth()->user()->username) }}" required />
                    <x-input-error :messages="$errors->get('username')" />
                </div>
                <div class="md:col-span-2">
                    <x-input-label for="email" value="Email" />
                    <x-input-text id="email" name="email" value="{{ old('email', auth()->user()->email) }}" required />
                    <x-input-error :messages="$errors->get('email')" />
                </div>
            </div>
            <div class="flex justify-end mt-6">
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 transition">Simpan Perubahan</button>
            </div>
        </form>
        <div class="mb-8">
            <form method="POST" action="{{ route('profile.password') }}" class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border border-gray-200 dark:border-gray-700">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-input-label for="current_password" value="Password Lama" />
                        <x-input-text id="current_password" name="current_password" type="password" required />
                        <x-input-error :messages="$errors->get('current_password')" />
                    </div>
                    <div>
                        <x-input-label for="password" value="Password Baru" />
                        <x-input-text id="password" name="password" type="password" required />
                        <x-input-error :messages="$errors->get('password')" />
                    </div>
                    <div class="md:col-span-2">
                        <x-input-label for="password_confirmation" value="Konfirmasi Password Baru" />
                        <x-input-text id="password_confirmation" name="password_confirmation" type="password" required />
                    </div>
                </div>
                <div class="flex justify-end mt-6">
                    <button type="submit" class="px-6 py-2 bg-primary-600 text-white rounded-lg shadow hover:bg-primary-700 transition">Ubah Password</button>
                </div>
            </form>
        </div>
        <div class="mb-4">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Tentang Saya</h3>
            <p class="text-gray-600 dark:text-gray-400">Selamat datang di profil Anda. Anda dapat mengelola data akun dan melihat aktivitas diskusi Anda di sini.</p>
        </div>
        <div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Diskusi Saya</h3>
            <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse(auth()->user()->posts()->latest()->get() as $post)
                <li class="py-3 flex justify-between items-center">
                    <div>
                        <a href="{{ route('posts.show', $post) }}" class="text-primary-700 dark:text-white font-semibold hover:underline">{{ $post->name }}</a>
                        <span class="block text-sm text-gray-500 dark:text-gray-400">{{ $post->created_at->diffForHumans() }}</span>
                    </div>
                    <a href="{{ route('posts.show', $post) }}" class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">Lihat Diskusi</a>
                </li>
                @empty
                <li class="py-3 text-gray-500 dark:text-gray-400">Belum ada diskusi.</li>
                @endforelse
            </ul>
        </div>
    </div>
</x-app-layout>

