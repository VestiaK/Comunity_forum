<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
<!-- Head: Flowbite CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.5/flowbite.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.5/flowbite.min.js"></script>
<script>
if (
    localStorage.getItem('color-theme') === 'dark' ||
    (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)
) {
    document.documentElement.classList.add('dark');
} else {
    document.documentElement.classList.remove('dark');
}

</script>
<section class="bg-white dark:bg-gray-900">
    <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0">  
        <a href="#" class="flex items-center mb-6 text-2xl font-semibold text-gray-900 dark:text-white">
        <svg class="w-[48px] h-[48px] text-purple-700 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="50" height="60" fill="currentColor" viewBox="0 0 24 24">
            <path fill-rule="evenodd" d="M12 4a4 4 0 1 0 0 8 4 4 0 0 0 0-8Zm-2 9a4 4 0 0 0-4 4v1a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2v-1a4 4 0 0 0-4-4h-4Z" clip-rule="evenodd"/>
          </svg>
            Register
        </a>
        @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show bg-green-100 dark:bg-green-800 text-green-700 dark:text-green-200 border border-green-200 dark:border-green-700" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close text-green-700 dark:text-green-200" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
        <div class="w-full rounded-lg shadow border md:mt-0 sm:max-w-md xl:p-0 bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700">
            <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                <h1 class="text-xl font-bold leading-tight tracking-tight md:text-2xl text-gray-900 dark:text-white">
                    Create an account
                </h1>
                
                <form class="space-y-4 md:space-y-6" action="/register" method="POST">
                    {{-- CSRF Token for security --}}
                    @csrf
                    <div>
                        <x-input-label for="name" :value="__('Name')"/>
                        <x-input-text id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="John Doe"/>
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="username" :value="__('Username')"/>
                        <x-input-text id="username" class="block mt-1 w-full" type="text" name="username" :value="old('username')" required placeholder="johndoe"/>
                        <x-input-error :messages="$errors->get('username')" class="mt-2" />
                        </div>
                            <div>
                        <x-input-label for="email" :value="__('Email')"/>
                        <x-input-text id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required placeholder="Example@company.com"/>
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="password" :value="__('Password')"/>
                        <x-input-text id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" placeholder="••••••••"/>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="password_confirmation" :value="__('Confirm Password')"/>
                        <x-input-text id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••"/>
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                          <input id="terms" aria-describedby="terms" type="checkbox" class="w-4 h-4 border bg-gray-700 ]border-gray-600 ]focus:ring-primary-600 ]ring-offset-gray-800" required="">
                        </div>
                        <div class="ml-3 text-sm">
                          <label for="terms" class="font-light text-gray-900 dark:text-gray-400">I accept the <a class="font-medium  hover:underline text-gray-900 dark:text-gray-400" href="#">Terms and Conditions</a></label>
                        </div>
                    </div>
                    <button type="submit" class="w-full text-white bg-purple-600 hover:bg-purple-700 focus:ring-4 focus:outline-none focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center transition-colors">Create an account</button>
                    <p class="text-sm font-light text-gray-500 dark:text-gray-400">
                        Already have an account? <a href="/login" class="font-medium text-purple-600 dark:text-primary-400 hover:underline">Login here</a>
                    </p>
                </form>
            </div>
        </div>
    </div>

  </section>