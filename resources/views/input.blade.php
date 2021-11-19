@extends('layout.admin')

@section('title')
    Input Data Pembelian
@endsection

@section('content')
    <div class="breadcrumbs mb-5">
        <div class="breadcrumbs__name">
            Input Pembelian
        </div>
    </div>

    <div class="content__wrapper">
        <div class="content">
            <div class="order-input__wrapper">
                <form action="/order" method="POST" enctype="multipart/form-data">
                    @csrf
                    @if (!tenant())
                        <div class="order-data-list">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <label class="input-group-text">Branch</label>
                                </div>
                                <select class="custom-select" name="branch" required>
                                    <option value="" selected>Choose...</option>
                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    @endif
                    <div class="order-data-list">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <label class="input-group-text">Supplier</label>
                            </div>
                            <select class="custom-select" name="supplier">
                                <option value="" selected>Choose...</option>
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="order-data-list">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <label class="input-group-text">Kategori</label>
                            </div>
                            <select class="custom-select" name="category" required>
                                <option value="" selected>Choose...</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="order-data-list">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <label class="input-group-text">No Nota</label>
                            </div>
                            <input type="text" name="transaction_id" class="form-control">
                        </div>
                    </div>
                    <div class="order-data-list">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <label class="input-group-text">Date</label>
                            </div>
                            <input type="text" name="date" class="form-control date">
                        </div>
                    </div>
                    <table class="table mt-5">
                        <thead class="table-dark">
                            <tr class="text-center">
                                <th>Nama</th>
                                <th>QTY</th>
                                <th>Harga</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <input type="text" name="name[]" class="form-control">
                                </td>
                                <td>
                                    <input type="number" name="qty[]" class="form-control qty">
                                </td>
                                <td>
                                    <input type="number" name="price[]" class="form-control price">
                                </td>
                                <td>
                                    <input type="number" name="subtotal[]" class="form-control subtotal" readonly>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="text" name="name[]" class="form-control">
                                </td>
                                <td>
                                    <input type="number" name="qty[]" class="form-control qty">
                                </td>
                                <td>
                                    <input type="number" name="price[]" class="form-control price">
                                </td>
                                <td>
                                    <input type="number" name="subtotal[]" class="form-control subtotal" readonly>
                                </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4">
                                    <div class="total__wrapper float-right">
                                        <div class="row">
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
                    <input type="file" name="files[]" accept="image/*" multiple>
                    <button type="submit" class="btn btn-primary mt-5 float-right">Submit</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $('.date').datetimepicker()

        function calculateSubTotal(element) {
            let row = element.parents('tr')
            let qty = row.find('.qty').val()
            let price = row.find('.price').val()

            if (qty && price) {
                let total = $('.grandTotal').val() || 0
                let subtotal = row.find('.subtotal').val(qty * price)
                $('.grandTotal').val(parseInt(total) + parseInt(subtotal.val()))
            }
        }

        function resetSubTotal(element) {
            let row = element.parents('tr')
            let subtotal = row.find('.subtotal').data('old')
            let total = $('.grandTotal').val() || 0
            $('.grandTotal').val(parseInt(total) - parseInt(subtotal))
        }

        function attachSubtotal(element) {
            let row = element.parents('tr')
            let subtotal = row.find('.subtotal').val() || 0
            row.find('.subtotal').data('old', subtotal)
            row.data('old', row.find('.subtotal').data('old', subtotal))
        }

        $('body').on('change', '.order-input__wrapper table tbody tr td input', function() {
            if ($(this).val()) {
                $(this).parents('tr').find('input').attr('required', true)
            } else {
                $(this).parents('tr').find('input').attr('required', false)
            }
        })

        $('body').on('focus', '.qty', function() {
            attachSubtotal($(this))

            let count = $('.order-input__wrapper table tbody tr').length
            let current = $(this).parents('tr').index() + 1
            count == current && $('.order-input__wrapper table tbody tr:last').clone().appendTo(
                '.order-input__wrapper table tbody').find('input').val('').attr('required', false)
        })

        $('body').on('focus', '.price', function() {
            attachSubtotal($(this))

            let count = $('.order-input__wrapper table tbody tr').length
            let current = $(this).parents('tr').index() + 1
            count == current && $('.order-input__wrapper table tbody tr:last').clone().appendTo(
                '.order-input__wrapper table tbody').find('input').val('').attr('required', false)
        })

        $('body').on('change', '.price', function() {
            $(this).val() ? calculateSubTotal($(this)) : $($(this).parents('tr').find('.subtotal').val(''))
            resetSubTotal($(this))
        })

        $('body').on('change', '.qty', function() {
            $(this).val() ? calculateSubTotal($(this)) : $($(this).parents('tr').find('.subtotal').val(''))
            resetSubTotal($(this))
        })
    </script>
@endsection
