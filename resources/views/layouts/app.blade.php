<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="{{asset('css/styles.css')}}">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
        <script src="
https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.all.min.js
"></script>
<link href="
https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.min.css
" rel="stylesheet"> <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css"
        integrity="sha384-XdYbMnZ/QjLh6iI4ogqCTaIjrFk87ip+ekIjefZch0Y+PvJ8CDYtEs1ipDmPorQ+" crossorigin="anonymous">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700">

    <!-- Styles -->
    <link href="https://unpkg.com/tailwindcss@^1.0/dist/tailwind.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Lato';
        }

        .create-btn {
            margin-top: 7px;
        }

        .label,
        .btn,
        .form-control {
            border-radius: 0px;
        }
    </style>

    @yield('style')
</head>

<body id="app-layout">

    <nav class="flex items-center justify-between flex-wrap bg-teal-500 p-6">
        <div class="flex items-center flex-shrink-0 text-white mr-6">
            <svg class="fill-current h-8 w-8 mr-2" width="54" height="54" viewBox="0 0 54 54"
                xmlns="http://www.w3.org/2000/svg">
                <!-- Your SVG icon code -->
            </svg>
            <a href="/">
                <span class="font-semibold text-xl tracking-tight">Shift Polls</span>
            </a>
        </div>
        <div class="block lg:hidden">
            <button
                class="flex items-center px-3 py-2 border rounded text-teal-200 border-teal-400 hover:text-white hover:border-white">
                <svg class="fill-current h-3 w-3" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <title> {{ __('Menu') }}</title>
                    <path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z" />
                </svg>
            </button>
        </div>

        <div class="flex justify-center pt-8 sm:justify-start sm:pt-0">
            <button id="languageDividerButton" data-dropdown-toggle="languageDivider"
                class="inline-block text-sm px-4 py-2 leading-none border rounded text-white border-white hover:border-transparent hover:text-gray hover:bg-white mt-4 lg:mt-0"
                type="button">{{ Config::get('languages')[App::getLocale()] }}
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m1 1 4 4 4-4" />
                </svg>
            </button>


            <div id="languageDivider"
                class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700 dark:divide-gray-600">
                <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownDividerButton">
                    @foreach (Config::get('languages') as $lang => $language)
                    @if ($lang != App::getLocale())
                    <li>
                        <a href="{{ route('lang.switch', $lang) }}" class="block px-4 py-2 text-gray-500 hover:bg-gray-300 dark:hover:bg-gray-600 dark:hover:text-white">{{$language}}</a>
                    </li>
                    @endif
                    @endforeach
                </ul>
            </div>
        </div>
        </div>

        <button id="dropdownDividerButton" data-dropdown-toggle="dropdownDivider"
            class="inline-block text-sm px-4 py-2 leading-none border rounded text-white border-white hover:border-transparent hover:text-gray hover:bg-white mt-4 lg:mt-0"
            type="button">{{ __('Menu') }}<svg class="w-2.5 h-2.5 ms-3" aria-hidden="true"
                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="m1 1 4 4 4-4" />
            </svg>
        </button>

        <!-- Dropdown menu -->
        <div id="dropdownDivider"
            class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700 dark:divide-gray-600">
            <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownDividerButton">

                @auth
                <li>
                    <div class="py-2">
                        <a href="{{ route('profile.edit') }}"
                            class="block px-4 py-2 text-gray-500 hover:bg-gray-300 dark:hover:bg-gray-600 dark:hover:text-white">{{__('ViewProfile')}}</a>
                    </div>
                </li>
                <li>
                    <a href="{{ route('questions.index') }}"
                        class="block px-4 py-2 text-gray-500 hover:bg-gray-300 dark:hover:bg-gray-600 dark:hover:text-white">{{__('EditPolls')}}</a>

                </li>
                <li>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="block px-4 py-2 text-gray-500 hover:bg-gray-300 dark:hover:bg-gray-600 dark:hover:text-white">
                            {{__('Logout')}}
                        </button>
                    </form>
                </li>
                @else
                <li>
                    <a href="{{ route('login') }}"
                        class="block px-4 py-2 text-gray-500 hover:bg-gray-300 dark:hover:bg-gray-600 dark:hover:text-white">
                        {{__('SignIn')}}</a>
                </li>
                <li>
                    <a href="{{ route('register') }}"
                        class="block px-4 py-2 text-gray-500 hover:bg-gray-300 dark:hover:bg-gray-600 dark:hover:text-white">{{__('SignUp')}}</a>
                </li>
                @endauth
            </ul>
        </div>
        </div>
    </nav>


    @yield('content')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.1.1/flowbite.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@2.8.2/dist/alpine.js" defer></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.3/jquery.min.js"
        integrity="sha384-I6F5OKECLVtK/BL+8iSLDEHowSAfUo76ZL9+kGAgTRdiByINKJaqTPH/QVNS1VDb"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.19.2/axios.js"></script>
    <!--Datatables -->
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
    <script>
    </script>
    @yield('js')

</body>

</html>