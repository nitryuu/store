@extends('layout.admin')

@section('title')
    List User
@endsection

@section('content')
    <div class="breadcrumbs mb-5">
        <div class="breadcrumbs__name">
            List User
        </div>
    </div>

    <div class="content__wrapper">
        <button class="btn btn-success mb-3" data-toggle="modal" data-target="#createUser">Tambah User</button>
        <div class="content table-responsive">
            <table id="userTable" class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Branch</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; ?>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $no }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->tenant->name }}</td>
                            <td>
                                <i class="fas fa-eye detailsButton" data-toggle="modal" data-target="#userDetails"
                                    style="color: green; cursor: pointer" data-id="{{ $user->id }}"></i>
                                <i class="fas fa-trash-alt deleteButton" data-toggle="modal"
                                    style="color: red; cursor: pointer" data-target="#deleteConfirmation"
                                    data-id="{{ $user->id }}"></i>
                            </td>
                        </tr>
                        <?php $no++; ?>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="createUser" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ url('/users') }}" method="POST">
                    @csrf
                    <div class="modal-header border-bottom-0">
                        <h5 class="modal-title">Tambah User</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Nama User</label>
                            <input type="type" name="name" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" name="password" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="name">Cabang</label>
                            <select name="branch" class="custom-select" required>
                                <option value="" selected disabled>Choose...</option>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                @endforeach
                            </select>
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

    <div class="modal fade" id="userDetails" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title">Details User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="updateForm">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Nama user</label>
                            <input type="type" name="name" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" name="password" class="form-control"
                                placeholder="Kosongkan jika tidak ingin mengubah password">
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
                    Apa Anda yakin akan menghapus user ini?
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
            $('#userTable').dataTable()
        })

        $('.detailsButton').click(function() {
            let id = $(this).data('id')
            $('#updateForm').data('id', id)

            $.ajax({
                type: 'get',
                url: `/users/${id}`,
                success: function(response) {
                    let value = response.data
                    let modal = $('#userDetails')

                    modal.find('input[name="name"]').val(value.name)
                    modal.find('input[name="email"]').val(value.email)
                }
            })
        })

        $('#updateForm').on('submit', function(e) {
            e.preventDefault()

            let id = $(this).data('id')

            $.ajax({
                type: 'put',
                url: `/users/${id}`,
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
                url: `/users/${id}`,
                headers: {
                    'X_CSRF_TOKEN': '{{ csrf_token() }}'
                },
                success: function() {
                    location.reload()
                }
            })
        })
    </script>
@endsection
