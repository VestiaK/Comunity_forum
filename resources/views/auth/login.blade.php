<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
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
<section class="bg-white dark:bg-gray-900 min-h-screen">
  <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0">
      <a href="#" class="flex items-center mb-6 text-2xl font-semibold text-gray-900 dark:text-white">
        <svg class="w-[48px] h-[48px] text-purple-700 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="50" height="60" fill="currentColor" viewBox="0 0 24 24">
            <path fill-rule="evenodd" d="M12 4a4 4 0 1 0 0 8 4 4 0 0 0 0-8Zm-2 9a4 4 0 0 0-4 4v1a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2v-1a4 4 0 0 0-4-4h-4Z" clip-rule="evenodd"/>
          </svg>
          Login
      </a>
      
      @if (session()->has('success'))
      <div id="alert-success" class="flex p-4 mb-4 bg-green-100 dark:bg-green-800 border-t-4 border-green-500 rounded-lg" role="alert">
          <svg class="flex-shrink-0 w-5 h-5 text-green-600 dark:text-green-200" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M18 10c0 4.418-3.582 8-8 8s-8-3.582-8-8 3.582-8 8-8 8 3.582 8 8zm-8-3a1 1 0 00-1 1v2a1 1 0 002 0V8a1 1 0 00-1-1zm0 6a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
          </svg>
          <div class="ml-3 text-sm font-medium text-green-700 dark:text-green-200">
              {{ session('success') }}
          </div>
          <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-green-100 dark:bg-green-800 text-green-700 dark:text-green-200 rounded-lg focus:ring-2 focus:ring-green-400 p-1.5 hover:bg-green-200 dark:hover:bg-green-700 inline-flex h-8 w-8" data-dismiss-target="#alert-success" aria-label="Close">
              <span class="sr-only">Close</span>
              <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
              </svg>
          </button>
      </div>
      @endif  

      <div class="w-full bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700 md:mt-0 sm:max-w-md xl:p-0">
          <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
              <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 dark:text-white md:text-2xl">
                  Sign in to your account
              </h1>
              <form class="space-y-4 md:space-y-6" action="/login" method="POST">
                @csrf
                <div>
                    <x-input-label for="email" :value="__('Email')" />
                    <x-input-text id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="email" placeholder="name@company.com" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="password" :value="__('Password')" />
                    <x-input-text id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                          <input id="remember" aria-describedby="remember" type="checkbox" class="w-4 h-4 border border-gray-600 rounded bg-gray-700 focus:ring-3 focus:ring-primary-600 ring-offset-gray-800">
                        </div>
                        <div class="ml-3 text-sm">
                          <label for="remember" class="text-primary-400 dark:text-gray-300">Remember me</label>
                        </div>
                    </div>
                    <a href="#" class="text-sm font-medium text-primary-400 hover:underline dark:text-gray-300">Forgot password?</a>
                </div>
                <button type="submit" class="w-full text-white bg-purple-600 hover:bg-purple-700 focus:ring-4 focus:outline-none focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center transition-colors">
                  Sign in
              </button>
              <p class="text-sm font-light text-gray-500 dark:text-gray-400">
                  Don’t have an account yet? <a href="/register" class="font-medium text-purple-600 dark:text-primary-400 hover:underline">Sign up</a>
              </p>
              </form>
          </div>
      </div>
  </div>
</section>