<div class="relative ml-auto" x-data="{ open: false }">

    <button @click="open = !open; $nextTick(() => { if(open){ $refs.userMenu.focus() } })"
            type="button"
            aria-haspopup="true"
            :aria-expanded="open ? 'true' : 'false'"
            class="flex transition-opacity duration-200 rounded-md">
        <div class="text-right mr-4">
            Sharafat Ibn Mollah Mosharraf
            <div class="text-sm text-gray-400">sharafat_8271@yahoo.co.uk</div>
        </div>
        <img class="w-10 h-10 rounded-full"
             src="https://sharafat.co.uk/assets/img/photo.png"
             alt="Sharafat Ibn Mollah Mosharraf"/>
    </button>

    <div x-show="open"
         x-ref="userMenu"
         x-transition:enter="transition-all transform ease-out"
         x-transition:enter-start="translate-y-1/2 opacity-0"
         x-transition:enter-end="translate-y-0 opacity-100"
         x-transition:leave="transition-all transform ease-in"
         x-transition:leave-start="translate-y-0 opacity-100"
         x-transition:leave-end="translate-y-1/2 opacity-0"
         @click.away="open = false"
         @keydown.escape="open = false"
         class="absolute right-0 w-48 py-1 origin-top-right bg-white rounded-md shadow-lg
                top-12 ring-1 ring-black ring-opacity-5 focus:outline-none z-10"
         tabindex="-1"
         role="menu"
         aria-orientation="vertical"
         aria-label="User menu">

        <div role="menuitem"
             class="block px-4 py-2 text-sm text-gray-400 md:hidden">
            Sharafat Ibn Mollah Mosharraf
        </div>

        <a href="{{ route('home') }}"
           role="menuitem"
           class="block px-4 py-2 text-sm text-gray-700 transition-colors hover:bg-gray-100">
            Home
        </a>

    </div>

</div>
