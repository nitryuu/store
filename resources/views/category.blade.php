@extends('layout.admin')

@section('title')
    List Kategori
@endsection

@section('content')
    <div class="breadcrumbs mb-5">
        <div class="breadcrumbs__name">
            List Kategori
        </div>
    </div>

    <div class="content__wrapper">
        <button class="btn btn-success mb-3" data-toggle="modal" data-target="#createCategory">Tambah Kategori</button>
        <div class="content">
            <div class="table-responsive">
                <table class="table table-bordered" id="category">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        @foreach ($categories as $category)
                            <tr>
                                <td>{{ $no }}</td>
                                <td>{{ $category->name }}</td>
                                <td>
                                    <i class="fas fa-eye detailsButton" data-toggle="modal" data-target="#categoryDetails"
                                        style="color: green; cursor: pointer" data-id="{{ $category->id }}"></i>
                                    <i class="fas fa-trash-alt deleteButton" data-toggle="modal"
                                        style="color: red; cursor: pointer" data-target="#deleteConfirmation"
                                        data-id="{{ $category->id }}"></i>
                                </td>
                            </tr>
                            <?php $no++; ?>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="createCategory" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ tenant() ? route('tenant.category.post', [tenant('id')]) : url('/category') }}"
                    method="POST">
                    @csrf
                    <div class="modal-header border-bottom-0">
                        <h5 class="modal-title">Tambah Kategori</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Nama kategori</label>
                            <input type="type" name="name" class="form-control">
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

    <div class="modal fade" id="categoryDetails" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title">Details Kategori</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="updateForm">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Nama kategori</label>
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
                    Apa Anda yakin akan menghapus kategori ini?
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
            $('#category').DataTable();

            $('.detailsButton').click(function() {
                let id = $(this).data('id')
                $('#updateForm').data('id', id)

                $.ajax({
                    type: 'get',
                    url: `category/${id}`,
                    success: function(response) {
                        let value = response.data
                        let modal = $('#categoryDetails')

                        modal.find('input[name="name"]').val(value.name)
                    }
                })
            })

            $('#updateForm').on('submit', function(e) {
                e.preventDefault()

                let id = $(this).data('id')

                $.ajax({
                    type: 'put',
                    url: `category/${id}`,
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
                    url: `category/${id}`,
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
