@extends('layouts.app')
@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Companies</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Companies</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <a href="{{ route('companies.create') }}" class="btn btn-primary">Add New Company</a>
            </div>

            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                <table id="companiesTable" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>S.No</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Website</th>
                            <th>Logo</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="companyModal" tabindex="-1" aria-labelledby="companyModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="companyModalLabel">Company Details</h5>
      </div>
      <div class="modal-body">
          <div id="modal-content-body">
              <div class="text-center">
                  <div class="spinner-border text-primary" role="status">
                      <span class="visually-hidden">Loading...</span>
                  </div>
              </div>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light border" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')

<script>
$(document).ready(function() {
    let table = $('#companiesTable').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: "{{ route('companies.index') }}",
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            { data: 'website', name: 'website' },
            { data: 'logo', name: 'logo', orderable: false, searchable: false },
            { data: 'created_at', name: 'created_at' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ]
    });

    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 3000);

    $(document).on('click', '.btn-view', function(e) {
        e.preventDefault();
        let id = $(this).data('id');
        $('#companyModal').modal('show');
        $('#modal-content-body').html(`
            <div class="text-center">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        `);

        $.ajax({
            url: "{{ route('companies.show', ':id') }}".replace(':id', id),
            method: 'GET',
            success: function(data) {
                let logo = data.logo ? `<img src="/storage/${data.logo}" class="rounded border mt-2" width="120" height="120">` : `<span class="text-muted">No Logo</span>`;
                $('#modal-content-body').html(`
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold">Company Name:</label>
                            <p>${data.name}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold">Email:</label>
                            <p>${data.email}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold">Website:</label>
                            <p>${data.website}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold">Logo:</label><br>${logo}
                        </div>
                        <div class="col-md-12">
                            <label class="fw-bold">Created At:</label>
                            <p>${data.created_at_formatted}</p>
                        </div>
                    </div>
                `);
            },
            error: function() {
                $('#modal-content-body').html('<p class="text-danger text-center">Failed to load company details.</p>');
            }
        });
    });

    $(document).on('click', '.btn-delete', function(e) {
        e.preventDefault();
        let id = $(this).data('id');

        Swal.fire({
            title: 'Are you sure?',
            text: "Data will be permanently deleted!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('companies.destroy', ':id') }}".replace(':id', id),
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire('Deleted!', response.message, 'success');
                            table.ajax.reload(null, false);
                        } else {
                            Swal.fire('Error!', response.message, 'error');
                        }
                    },
                    error: function(xhr) {
                        Swal.fire('Error!', 'Something went wrong while deleting.', 'error');
                    }
                });
            }
        });
    });
});
</script>
@endsection