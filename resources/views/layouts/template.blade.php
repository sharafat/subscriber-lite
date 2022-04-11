<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Title -->
    <title>
        @yield('title') — {{ config('app.name') }}
    </title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset(mix("images/favicon/favicon.ico")) }}"/>
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset(mix('images/favicon/favicon-32x32.png')) }}"/>
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset(mix('images/favicon/favicon-16x16.png')) }}"/>
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset(mix('images/favicon/apple-touch-icon.png')) }}"/>
    <link rel="manifest" href="{{ asset(mix('images/favicon/site.webmanifest')) }}"/>

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset(mix('/css/fontawesome.css')) }}"/>
    <link rel="stylesheet" href="{{ asset(mix('/css/app.css')) }}"/>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200;300;400;600;700;900&display=swap"
          rel="stylesheet"/>

    <style>
        [x-cloak] {
            display: none;
        }
    </style>

    @yield('css')
</head>
<body>

<div x-data="setup()">
    <div class="flex h-screen antialiased text-gray-900 bg-gray-100">

        <!-- Loading screen -->
        <div x-ref="loading"
             class="fixed inset-0 z-50 flex items-center justify-center text-2xl font-semibold text-white bg-primary-darker">
            Loading...
        </div>

        <!-- Navigation (Desktop) -->
        <aside class="flex-shrink-0 hidden w-64 bg-primary-dark border-r md:block">
            <div class="flex flex-col h-full">
                @include('layouts.navigation-items')
            </div>
        </aside>

        <div class="flex-1 h-full overflow-x-hidden overflow-y-auto">

            <header class="relative bg-white">
                <div class="flex items-center justify-between py-2 px-4 border-b">

                    <!-- Mobile menu button -->
                    <button
                        @click="isMobileMainMenuOpen = !isMobileMainMenuOpen"
                        class="px-3 py-2 transition-colors duration-200 rounded-md text-primary-lighter bg-primary-50
                                hover:text-primary hover:bg-primary-100 md:hidden focus:outline-none focus:ring">
                        <span class="sr-only">Open main menu</span>
                        <span aria-hidden="true">
                            <i class="text-xl fa fa-bars"></i>
                        </span>
                    </button>

                    <!-- Organization -->
                    <a href="#"
                       class="inline-flex items-center text-2xl font-bold tracking-wider text-gray-600">
                        <img src="{{ asset(mix("images/logo.png")) }}"
                             alt="logo" style="max-height: 30px" class="mr-3"/>
                        Subscriber Lite
                    </a>

                    <!-- Mobile user menu button -->
                    <button @click="isMobileSubMenuOpen = !isMobileSubMenuOpen"
                            class="px-4 py-2 transition-colors duration-200 rounded-md text-primary-lighter bg-primary-50
                                    hover:text-primary hover:bg-primary-100 md:hidden focus:outline-none focus:ring">
                        <span class="sr-only">Open sub menu</span>
                        <span aria-hidden="true">
                            <i class="text-xl fa fa-ellipsis-v"></i>
                        </span>
                    </button>

                    <!-- User Menu -->
                    <nav aria-label="Secondary" class="hidden space-x-2 md:flex md:items-center">
                        @include('layouts.user-menu')
                    </nav>

                    <!-- Mobile User Menu -->
                    <nav x-transition:enter="transition duration-200 ease-in-out transform sm:duration-500"
                         x-transition:enter-start="-translate-y-full opacity-0"
                         x-transition:enter-end="translate-y-0 opacity-100"
                         x-transition:leave="transition duration-300 ease-in-out transform sm:duration-500"
                         x-transition:leave-start="translate-y-0 opacity-100"
                         x-transition:leave-end="-translate-y-full opacity-0"
                         x-show="isMobileSubMenuOpen"
                         @click.away="isMobileSubMenuOpen = false"
                         class="absolute flex items-center p-4 bg-white rounded-md shadow-lg top-16 inset-x-4 z-10 md:hidden"
                         aria-label="Secondary">
                        @include('layouts.user-menu')
                    </nav>
                </div>

                <!-- Navigation (Mobile) -->
                <div class="border-b md:hidden"
                     x-show="isMobileMainMenuOpen"
                     @click.away="isMobileMainMenuOpen = false">
                    @include('layouts.navigation-items')
                </div>
            </header>

            <!-- Main content -->
            <main style="min-height: calc(100% - 118px)">

                <!-- Content header -->
                <div class="flex items-center justify-between px-4 py-4 lg:py-6">
                    <!-- Page Heading -->
                    <h1 class="text-2xl font-semibold text-primary-dark">@yield('title')</h1>
                    <!-- Action Buttons -->
                    <div>@yield('buttons')</div>
                </div>

                <!-- Content -->
                <div class="px-4 pb-4">
                    @yield('content')
                </div>
            </main>

            <!-- Footer -->
            <footer class="p-2.5 md:p-4 bg-white border-t text-center text-sm md:text-base">
                <div class="md:float-left">
                    &copy; 2022{{ date('Y') > 2022 ? '–' . date('Y') : '' }} <b>·</b>
                    Crafted with <i class="text-red-600 fa fa-heart"></i> by
                    <a href="{{ config('app.url') }}" class="text-primary">{{ config('app.name') }}</a>
                </div>
                <div class="md:float-right">
                    {{ __('For support') }}:
                    <a class="text-primary" href="mailto:support@subscriber-lite.com">support@subscriber-lite.com</a>
                </div>
                <div class="clear-both"></div>
            </footer>
        </div>

    </div>
</div>

<!-- Scripts -->
<script defer src="{{ asset(mix('/js/app.js')) }}"></script>
<script>
    function setup() {
        return {
            loading: true,
            toggleSidbarMenu() {
                this.isSidebarOpen = !this.isSidebarOpen
            },
            isMobileSubMenuOpen: false,
            openMobileSubMenu() {
                this.isMobileSubMenuOpen = true
                this.$nextTick(() => {
                    this.$refs.mobileSubMenu.focus()
                })
            },
            isMobileMainMenuOpen: false,
            openMobileMainMenu() {
                this.isMobileMainMenuOpen = true
                this.$nextTick(() => {
                    this.$refs.mobileMainMenu.focus()
                })
            },
            init() {
                this.$refs.loading.classList.add('hidden')
            },
        }
    }
</script>
@yield('js')

</body>
</html>
