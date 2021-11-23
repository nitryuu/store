<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | Pembelian</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css"
        integrity="sha512-YWzhKL2whUzgiheMoBFwW8CKV4qpHQAEuvilg9FAn5VJUDwKZZxkJNuGM4XkWuk94WCrrwslk8yWNGmY1EduTA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css"
        integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">
    <link rel="stylesheet" href="//cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="{{ global_asset('css/bootstrap-datetimepicker.min.css') }}">
    <link rel="stylesheet" href="{{ global_asset('css/custom-style.css') }}">
</head>

<body>
    <div class="app">
        <div class="sidebar__wrapper">
            <div class="sidebar">
                <div class="sidebar__header">
                    <div class="sidebar__title">
                        <span>
                            Pembelian
                        </span>
                    </div>
                </div>
                <div class="sidebar__menu">
                    <div class="sidebar__menu-list">
                        <a href="{{ url('/dashboard') }}">Dashboard</a>
                    </div>
                    <div class="sidebar__menu-list">
                        <a href="{{ url('/order-list') }}">Pembelian</a>
                    </div>
                    <div class="sidebar__menu-list">
                        <a href="{{ url('/order-input') }}">Input Pembelian</a>
                    </div>
                    <div class="sidebar__menu-list">
                        <a href="{{ url('/supplier') }}">Supplier</a>
                    </div>
                    <div class="sidebar__menu-list">
                        <a href="{{ url('/category') }}">Kategori</a>
                    </div>
                    @if (!tenant())
                        <div class="sidebar__menu-list">
                            <a href="{{ url('/branch') }}">Cabang</a>
                        </div>
                        <div class="sidebar__menu-list">
                            <a href="{{ url('/income') }}">Pendapatan</a>
                        </div>
                        <div class="sidebar__menu-list">
                            <a href="{{ url('/users') }}">User</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="wrapper">
            <div class="navbar__wrapper">
                <div class="navbar">
                    <div class="navbar__menu-mobile">
                        <i class="fas fa-bars"></i>
                    </div>
                    <div class="navbar__user">
                        <div class="navbar__user-name">
                            {{ strtoupper(auth()->user()->name) }}
                        </div>
                        <div class="navbar__user-menu">
                            <div class="navbar__user-menu-list">
                                <a href="{{ url('profile') }}">
                                    Profile
                                </a>
                            </div>
                            <div class="navbar__user-menu-list">
                                <a href="{{ url('logout') }}">
                                    Logout
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="main__wrapper">
                @yield('content')
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-fQybjgWLrvvRgtW6bFlB7jaZrFsaBXjsOMm/tB9LTS58ONXgqbR9W8oWht/amnpF" crossorigin="anonymous">
    </script>
    <script src="//cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script src="{{ global_asset('js/bootstrap-datetimepicker.min.js') }}"></script>
    <script src="//cdn.datatables.net/plug-ins/1.11.3/api/sum().js"></script>
    <script>
        $('.navbar__user-name').click(function() {
            $('.navbar__user-menu').toggleClass('show')
        })

        $('.navbar__menu-mobile i').click(function() {
            $('.app').toggleClass('show')
        })
    </script>
    @yield('script')
</body>

</html>
