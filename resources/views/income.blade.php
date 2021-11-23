@extends('layout.admin')

@section('title')
    List Pendapatan
@endsection


@section('content')
    <div class="breadcrumbs mb-5">
        <div class="breadcrumbs__name">
            List Pendapatan
        </div>
    </div>

    {{-- WHEN WHO --}}

    <div class="content__wrapper">
        <button class="btn btn-success mb-3" data-toggle="modal" data-target="#createIncome">Tambah Pendapatan</button>
        <div class="content table-responsive">
            <table id="income" class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Pemasukan</th>
                        <th>Penginput</th>
                        <th>Tanggal pemasukan</th>
                        <th>Tanggal input</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; ?>
                    @foreach ($income as $list)
                        <tr>
                            <td>{{ $no }}</td>
                            <td>{{ $list->income }}</td>
                            <td>{{ $list->created_by }}</td>
                            <td>{{ $list->date }}</td>
                            <td>{{ $list->created_at }}</td>
                            <td>
                                <i class="fas fa-eye detailsButton" data-toggle="modal" data-target="#incomeDetails"
                                    style="color: green; cursor: pointer" data-id="{{ $list->id }}"></i>
                                <i class="fas fa-trash-alt deleteButton" data-id="{{ $list->id }}" data-toggle="modal"
                                    style="color: red; cursor: pointer;" data-target="#deleteConfirmation"></i>
                            </td>
                        </tr>
                        <?php $no++; ?>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="createIncome" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ url('/income') }}" method="POST">
                    @csrf
                    <div class="modal-header border-bottom-0">
                        <h5 class="modal-title">Tambah Pendapatan</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="branch">Cabang</label>
                            <select name="branch" class="custom-select">
                                <option value="" selected disabled>Choose...</option>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="income_date">Tanggal Pemasukan</label>
                            <input type="text" name="income_date" class="form-control date" required>
                        </div>
                        <div class="form-group">
                            <label for="income">Pemasukan</label>
                            <input type="number" name="income" class="form-control" required>
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

    <div class="modal fade" id="incomeDetails" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title">Details Pendapatan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="updateForm">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="branch">Cabang</label>
                            <select name="branch" class="custom-select">
                                <option value="" selected disabled>Choose...</option>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="income_date">Tanggal Pemasukan</label>
                            <input type="text" name="income_date" class="form-control date" required>
                        </div>
                        <div class="form-group">
                            <label for="income">Pemasukan</label>
                            <input type="number" name="income" class="form-control" required>
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
                    Apa Anda yakin akan menghapus pendapatan ini?
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
            $('#income').DataTable();
            $('.date').datetimepicker()

            $('.detailsButton').click(function() {
                let id = $(this).data('id')
                $('#updateForm').data('id', id)

                $.ajax({
                    type: 'get',
                    url: `/income/${id}`,
                    success: function(response) {
                        let value = response.data
                        let modal = $('#incomeDetails')
                        modal.find('input').val('')
                        modal.find('select').val('')

                        modal.find('input[name="income_date"]').val(value.date)
                        modal.find('select[name="branch"]').val(value.branch.id)
                        modal.find('input[name="income"]').val(value.income)
                    }
                })
            })

            $('#updateForm').on('submit', function(e) {
                e.preventDefault()

                let id = $(this).data('id')
                let data = $(this).serialize()

                $.ajax({
                    type: 'put',
                    url: `/income/${id}`,
                    data: data,
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
                    url: `/income/${id}`,
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
