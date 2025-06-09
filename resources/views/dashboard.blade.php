<x-app-layout>
    <!-- Hero Section -->
    <section class="bg-center bg-no-repeat bg-[url('https://flowbite.s3.amazonaws.com/docs/jumbotron/conference.jpg')] bg-gray-700 bg-blend-multiply">
        <div class="px-4 mx-auto max-w-screen-xl text-center py-24 lg:py-56">
            <h1 class="mb-4 text-4xl font-extrabold tracking-tight leading-none text-white md:text-5xl lg:text-6xl">Selamat Datang Di forum Komunitas Indonesia</h1>
            <p class="mb-8 text-lg font-normal text-gray-300 lg:text-xl sm:px-16 lg:px-48">Forum diskusi untuk berbagi pengetahuan dan pengalaman dalam dunia programming</p>
            <div class="flex flex-col space-y-4 sm:flex-row sm:justify-center sm:space-y-0 sm:gap-x-6">
                <a href="/posts" class="inline-flex justify-center items-center py-4 px-5 text-base font-medium text-center text-white rounded-lg bg-purple-700 hover:bg-purple-800 focus:ring-4 focus:ring-purple-300 dark:focus:ring-purple-900">
                    Lihat Diskusi
                    <svg class="w-3.5 h-3.5 ms-2 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
                    </svg>
                </a>
            </div>
        </div>
    </section>

    <!-- Latest Posts Section -->
    <section class="bg-white dark:bg-gray-900">
        <div class="py-8 px-4 mx-auto max-w-screen-xl lg:py-16">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-8">Diskusi Terbaru</h2>
            <div class="grid md:grid-cols-2 gap-8">
                @foreach($latestPosts as $post)
                    <div class="bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-6">
                        <a href="/posts?category={{ $post->category->slug }}" 
                           class="bg-purple-100 text-purple-800 text-xs font-medium inline-flex items-center px-2.5 py-0.5 rounded-md dark:bg-gray-700 dark:text-purple-400 mb-2">
                            {{ $post->category->name }}
                        </a>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">{{ $post->name }}</h3>
                        <p class="text-gray-500 dark:text-gray-400 mb-4">{{ Str::limit($post->body, 100) }}</p>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-500 dark:text-gray-400">{{ $post->created_at->diffForHumans() }}</span>
                            <a href="/posts/{{ $post->slug }}" 
                               class="text-purple-600 dark:text-purple-500 hover:underline font-medium text-sm inline-flex items-center">
                                Baca Selengkapnya
                                <svg class="w-3.5 h-3.5 ms-2 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Category Section -->
    <section class="bg-gray-50 dark:bg-gray-800">
        <div class="py-8 px-4 mx-auto max-w-screen-xl lg:py-16">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-8">Kategori Diskusi</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($categories->take(4) as $category)
                    <a href="/posts?category={{ $category->slug }}" 
                       class="block p-6 bg-white border border-gray-200 rounded-lg shadow-sm hover:bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:hover:bg-gray-600">
                        <h5 class="mb-2 text-xl font-bold tracking-tight text-gray-900 dark:text-white">{{ $category->name }}</h5>
                        <p class="font-normal text-gray-700 dark:text-gray-400">{{ Str::limit($category->description, 60) }}</p>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
</x-app-layout>