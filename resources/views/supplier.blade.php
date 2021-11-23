@extends('layout.admin')

@section('title')
    List Supplier
@endsection

@section('content')
    <div class="breadcrumbs mb-5">
        <div class="breadcrumbs__name">
            List Supplier
        </div>
    </div>

    <div class="content__wrapper">
        <button class="btn btn-success mb-3" data-toggle="modal" data-target="#createSupplier">Tambah Supplier</button>
        <div class="content">
            <div class="table-responsive">

                <table class="table table-bordered" id="supplier">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th>Phone Number</th>
                            <th>Email</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        @foreach ($suppliers as $supplier)
                            <tr>
                                <td>{{ $no }}</td>
                                <td>{{ $supplier->name }}</td>
                                <td>{{ $supplier->phone_number }}</td>
                                <td>{{ $supplier->email }}</td>
                                <td>
                                    <i class="fas fa-eye detailsButton" data-toggle="modal" data-target="#supplierDetails"
                                        data-id="{{ $supplier->id }}"></i>
                                    <i class="fas fa-trash-alt deleteButton" data-toggle="modal" style="color: red"
                                        data-target="#deleteConfirmation" data-id="{{ $supplier->id }}"></i>
                                </td>
                            </tr>
                            <?php $no++; ?>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="createSupplier" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ url('/supplier') }}" method="POST">
                    @csrf
                    <div class="modal-header border-bottom-0">
                        <h5 class="modal-title">Tambah Supplier</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Nama supplier</label>
                            <input type="type" name="name" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="address">Alamat</label>
                            <textarea name="address" class="form-control"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="phone_number">Nomor telepon</label>
                            <input type="text" name="phone_number" class="form-control" minlength="8" maxlength="15">
                        </div>
                        <div class="form-group">
                            <label for="email">Email address</label>
                            <input type="email" name="email" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer border-top-0">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Tambah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="supplierDetails" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title">Details Supplier</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="updateForm">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Nama supplier</label>
                            <input type="type" name="name" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="address">Alamat</label>
                            <textarea name="address" class="form-control"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="phone_number">Nomor telepon</label>
                            <input type="text" name="phone_number" class="form-control" minlength="8" maxlength="15">
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" class="form-control">
                        </div>
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
                    Apa Anda yakin akan menghapus supplier ini?
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
            $('#supplier').DataTable();

            $('.detailsButton').click(function() {
                let id = $(this).data('id')
                $('#updateForm').data('id', id)

                $.ajax({
                    type: 'get',
                    url: `/supplier/${id}`,
                    success: function(response) {
                        let value = response.data
                        let modal = $('#supplierDetails')

                        modal.find('input[name="name"]').val(value.name)
                        modal.find('textarea[name="address"]').val(value.address)
                        modal.find('input[name="phone_number"]').val(value.phone_number)
                        modal.find('input[name="email"]').val(value.email)
                    }
                })
            })

            $('#updateForm').on('submit', function(e) {
                e.preventDefault()

                let id = $(this).data('id')

                $.ajax({
                    type: 'put',
                    url: `/supplier/${id}`,
                    data: $(this).serialize(),
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function() {
                        location.reload()
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
                    url: `/supplier/${id}`,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function() {
                        location.reload()
                    }
                })
            })
        });
    </script>
@endsection
