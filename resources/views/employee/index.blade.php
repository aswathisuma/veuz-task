@extends('layouts.app')
@section('content')
<div class="app-content-header">
     <div class="container-fluid">
         <div class="row">
             <div class="col-sm-6">
                 <h3 class="mb-0">Employees</h3>
             </div>
             <div class="col-sm-6">
                 <ol class="breadcrumb float-sm-end">
                     <li class="breadcrumb-item"><a href="#">Home</a></li>
                     <li class="breadcrumb-item active" aria-current="page">Employees</li>
                 </ol>
             </div>
         </div>
     </div>
 </div>

 <section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <a href="{{ route('employees.create') }}" class="btn btn-primary">Add New Employee</a>
            </div>

            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <table id="employeesTable" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>S.No</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Company</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="employeeModal" tabindex="-1" aria-labelledby="employeeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="employeeModalLabel">Employee Details</h5>
      </div>
      <div class="modal-body">
          <div id="employee-modal-body">
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
    let table = $('#employeesTable').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: "{{ route('employees.index') }}",
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'first_name', name: 'first_name' },
            { data: 'last_name', name: 'last_name' },
            { data: 'email', name: 'email' },
            { data: 'phone', name: 'phone' },
            { data: 'company', name: 'company.name', defaultContent: '-' },
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
        $('#employeeModal').modal('show');
        $('#employee-modal-body').html(`
            <div class="text-center">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        `);

        $.ajax({
            url: "{{ route('employees.show', ':id') }}".replace(':id', id),
            method: 'GET',
            success: function(data) {
                $('#employee-modal-body').html(`
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold">First Name:</label>
                            <p>${data.first_name}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold">Last Name:</label>
                            <p>${data.last_name}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold">Email:</label>
                            <p>${data.email ?? '-'}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold">Phone:</label>
                            <p>${data.phone ?? '-'}</p>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="fw-bold">Company:</label>
                            <p>${data.company?.name ?? '-'}</p>
                        </div>
                        <div class="col-md-12">
                            <label class="fw-bold">Created At:</label>
                            <p>${data.created_at_formatted}</p>
                        </div>
                    </div>
                `);
            },
            error: function() {
                $('#employee-modal-body').html('<p class="text-danger text-center">Failed to load employee details.</p>');
            }
        });
    });


    $(document).on('click', '.btn-delete', function(e) {
        e.preventDefault();
        let id = $(this).data('id');

        Swal.fire({
            title: 'Are you sure?',
            text: "This employee will be permanently deleted!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('employees.destroy', ':id') }}".replace(':id', id),
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
                    error: function() {
                        Swal.fire('Error!', 'Something went wrong while deleting.', 'error');
                    }
                });
            }
        });
    });
});
</script>
@endsection