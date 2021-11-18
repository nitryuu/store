@extends('layout.admin')

@section('title')
    List Pembelian
@endsection


@section('content')
    <div class="breadcrumbs mb-5">
        <div class="breadcrumbs__name">
            List Pembelian
        </div>
    </div>

    <div class="content__wrapper">
        <div class="content">
            <table id="order" class="table">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Total</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                        <tr>
                            <td>{{ $order->id }}</td>
                            <td>{{ $order->grand_total }}</td>
                            <td>{{ $order->created_date }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('#order').DataTable();
        });
    </script>
@endsection
