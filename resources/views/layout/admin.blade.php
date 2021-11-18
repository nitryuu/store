<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | Pembelian</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css"
        integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">
    <link rel="stylesheet" href="//cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
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
                        <a href="{{ url('/') }}">Dashboard</a>
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
                    @endif
                </div>
            </div>
        </div>
        <div>
            <div class="navbar__wrapper">
                <div class="navbar">
                    <div class="navbar__user">
                        <span>
                            ADMINISTRATOR
                        </span>
                    </div>
                </div>
            </div>
            <div class="main__wrapper">
                @yield('content')
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-fQybjgWLrvvRgtW6bFlB7jaZrFsaBXjsOMm/tB9LTS58ONXgqbR9W8oWht/amnpF" crossorigin="anonymous">
    </script>
    <script src="//cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>

    @yield('script')
</body>

</html>
