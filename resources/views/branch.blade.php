@extends('layout.admin')

@section('title')
    List Cabang
@endsection


@section('content')
    <div class="breadcrumbs mb-5">
        <div class="breadcrumbs__name">
            List Cabang
        </div>
    </div>

    <div class="content__wrapper">
        <button class="btn btn-success mb-3" data-toggle="modal" data-target="#createBranch">Tambah Cabang</button>
        <div class="content table-responsive">
            <table id="branch" class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Name</th>
                        <th>Tanggal Pembuatan</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; ?>
                    @foreach ($branches as $branch)
                        <tr>
                            <td>{{ $no }}</td>
                            <td>{{ $branch->name }}</td>
                            <td>{{ $branch->created_at }}</td>
                            <td>
                                <i class="fas fa-eye detailsButton" data-toggle="modal" data-target="#branchDetails"
                                    style="color: green; cursor: pointer" data-id="{{ $branch->id }}"></i>
                                <i class="fas fa-trash-alt deleteButton" data-id="{{ $branch->id }}" data-toggle="modal"
                                    data-target="#deleteConfirmation"></i>
                            </td>
                        </tr>
                        <?php $no++; ?>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="createBranch" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ url('/branch') }}" method="POST">
                    @csrf
                    <div class="modal-header border-bottom-0">
                        <h5 class="modal-title">Tambah Cabang</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="branch_name">Nama cabang</label>
                            <input type="type" name="branch_name" class="form-control">
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

    <div class="modal fade" id="branchDetails" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title">Details Cabang</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="updateForm">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Nama Cabang</label>
                            <input type="type" name="name" class="form-control">
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
                    Apa Anda yakin akan menghapus cabang ini?
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
            $('#branch').DataTable();

            $('.detailsButton').click(function() {
                let id = $(this).data('id')
                $('#updateForm').data('id', id)

                $.ajax({
                    type: 'get',
                    url: `/branch/${id}`,
                    success: function(response) {
                        let value = response.data
                        let modal = $('#branchDetails')

                        modal.find('input[name="name"]').val(value.name)
                    }
                })
            })

            $('#updateForm').on('submit', function(e) {
                e.preventDefault()

                let id = $(this).data('id')

                $.ajax({
                    type: 'put',
                    url: `/branch/${id}`,
                    data: $(this).serialize(),
                    headers: {
                        'X_CSRF_TOKEN': '{{ csrf_token() }}'
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
                    url: `/branch/${id}`,
                    headers: {
                        'X_CSRF_TOKEN': '{{ csrf_token() }}'
                    },
                    success: function() {
                        location.reload()
                    }
                })
            })
        });
    </script>
@endsection
