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
        <div class="content">
            <table id="branch" class="table">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Name</th>
                        <th>Tanggal Pembuatan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; ?>
                    @foreach ($branches as $branch)
                        <tr>
                            <td>{{ $no }}</td>
                            <td>{{ $branch->name }}</td>
                            <td>{{ $branch->created_at }}</td>
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
                            <label for="name">Nama cabang</label>
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
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('#branch').DataTable();
        });
    </script>
@endsection
