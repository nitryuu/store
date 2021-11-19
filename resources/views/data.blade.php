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
        <div class="content table-responsive">
            <table id="order" class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        @if (!tenant())
                            <th>Branch</th>
                        @endif
                        <th>Total</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                        <tr>
                            <td>{{ $order->id }}</td>
                            @if (!tenant())
                                <td>{{ $order->tenant->name }}</td>
                            @endif
                            <td>{{ $order->grand_total }}</td>
                            <td>{{ $order->created_at }}</td>
                            <td>
                                <i class="fas fa-eye detailsButton" data-toggle="modal" data-target="#orderDetails"
                                    data-id="{{ $order->id }}"></i>
                                <i class="fas fa-trash-alt deleteButton" data-toggle="modal"
                                    style="color: red; cursor: pointer" data-target="#deleteConfirmation"
                                    data-id="{{ $order->id }}"></i>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="orderDetails" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                @csrf
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title">Detail Pembelian</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="updateForm">
                    <div class="modal-body">
                        <div class="form-group row">
                            <label for="branch" class="col-sm-2 col-form-label">Cabang</label>
                            <div class="col-sm-10">
                                <select name="branch" class="custom-select">
                                    <option value="" selected>Choose...</option>
                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="supplier" class="col-sm-2 col-form-label">Supplier</label>
                            <div class="col-sm-10">
                                <select name="supplier" class="custom-select">
                                    <option value="" selected>Choose...</option>
                                    @foreach ($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="category" class="col-sm-2 col-form-label">Kategori</label>
                            <div class="col-sm-10">
                                <select name="category" class="custom-select">
                                    <option value="" selected>Choose...</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="transaction_id" class="col-sm-2 col-form-label">No Nota</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="transaction_id" name="transaction_id">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="date" class="col-sm-2 col-form-label">Tanggal</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control date" id="date" name="date">
                            </div>
                        </div>
                        <table class="table mt-4" id="orderItemsDetails">
                            <thead class="table-dark">
                                <tr>
                                    <th>Nama</th>
                                    <th>QTY</th>
                                    <th>Price</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="order-items__list">

                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4">
                                        <div class="total__wrapper float-right">
                                            <div class="row align-items-center">
                                                <div class="col-2">
                                                    <span>
                                                        Total:
                                                    </span>
                                                </div>
                                                <div class="col-10">
                                                    <input type="number" name="total" class="form-control grandTotal"
                                                        name="total" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="modal-footer border-top-0">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Ubah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteConfirmation" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title">Delete Confirmation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Apa Anda yakin akan menghapus pembelian ini?
                </div>
                <div class="modal-footer border-top-0">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger deleteConfirmationButton">Hapus</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('#order').DataTable();
            $('.date').datetimepicker()
        });


        $('.detailsButton').click(function() {
            let id = $(this).data('id')
            $('#updateForm').data('id', id)

            $.ajax({
                type: 'get',
                url: `/order-details/${id}`,
                success: function(response) {
                    $('.order-items__list tr').remove()
                    $('#updateForm img').remove()
                    let value = response.order
                    let items = response.items
                    let files = response.files

                    let branch = value.branch
                    let supplier = value.supplier || ''
                    let category = value.category

                    let modal = $('#orderDetails')

                    modal.find('select[name="branch"]').val(branch.id)
                    modal.find('input[name="branch"]').val(branch.name)
                    modal.find('select[name="supplier"]').val((value.supplier && value.supplier.id) ||
                        supplier)
                    modal.find('select[name="category"]').val(category.id)
                    modal.find('input[name="transaction_id"]').val(value.transaction_id)
                    modal.find('input[name="date"]').val(value.created_at)
                    modal.find('input[name="total"]').val(value.grand_total)

                    for (let i = 0; i < items.length; i++) {
                        $('.order-items__list').append(
                            `<tr><td><input type="text" value="${items[i].name}" name="name[]" class="form-control" /></td><td><input type="number" name="qty[]" value="${items[i].qty}" class="form-control" /></td><td><input type="number" name="price[]" value="${items[i].price}" class="form-control" /></td><td><input type="number" name="subtotal[]" value="${items[i].subtotal}" readonly class="form-control" /></td></tr>`
                        )
                    }

                    for (let j = 0; j < files.length; j++) {
                        $('#updateForm .modal-body').append(
                            `<img src="{{ global_asset('assets/${files[j].filename}') }}" style="width: 200px; height: 200px; object-fit:cover;" />`
                        )
                    }
                }
            })
        })

        $('.deleteButton').click(function() {
            $('.deleteConfirmationButton').data('id', $(this).data('id'))
        })

        $('.deleteConfirmationButton').click(function() {
            let id = $(this).data('id')

            $.ajax({
                type: 'delete',
                url: `/order/${id}`,
                headers: {
                    'X_CSRF_TOKEN': '{{ csrf_token() }}'
                },
                success: function() {
                    location.reload()
                }
            })
        })

        $('#updateForm').on('submit', function(e) {
            e.preventDefault()

            let id = $(this).data('id')

            $.ajax({
                type: 'put',
                url: `/order/${id}`,
                data: $(this).serialize(),
                headers: {
                    'X_CSRF_TOKEN': '{{ csrf_token() }}'
                },
                success: function() {
                    // location.reload()
                }
            })
        })
    </script>
@endsection
