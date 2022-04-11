<nav aria-label="Main" class="flex-1 px-2 py-4 space-y-2 overflow-y-hidden hover:overflow-y-auto bg-primary">

    <div role="menu" class="mt-2 space-y-2 hover:bg-primary-light rounded-md" aria-label="Subscribers">
        <a href="{{ route('subscribers.index') }}"
           role="menuitem"
           class="block mx-7 p-2 text-sm text-white transition-colors duration-200 hover:text-white">
            <i class="fa fa-envelope-circle-check mr-4"></i>
            Subscribers
        </a>
    </div>

    <div role="menu" class="mt-2 space-y-2 hover:bg-primary-light rounded-md" aria-label="Fields">
        <a href="#"
           role="menuitem"
           class="block mx-7 p-2 text-sm text-white transition-colors duration-200 hover:text-white">
            <i class="fa fa-list mr-4"></i>
            Fields
        </a>
    </div>

</nav>
