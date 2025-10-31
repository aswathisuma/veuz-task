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
            <div class="card-body">

                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <form action="{{ route('companies.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">Company Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="website" class="form-label">Website <span class="text-danger">*</span></label>
                        <input type="text" name="website" id="website" class="form-control @error('website') is-invalid @enderror"
                               value="{{ old('website') }}" required>
                        @error('website')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="logo" class="form-label">Logo <span class="text-danger">*</span></label>
                        <input type="file" name="logo" id="logo" class="form-control @error('logo') is-invalid @enderror" required>
                        @error('logo')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">
                            Minimum size: 100x100 pixels, Max 2MB
                        </small>
                    </div>

                    <button type="submit" class="btn btn-primary">Save</button>
                    <a href="{{ route('companies.index') }}" class="btn btn-light border">Cancel</a>
                </form>

            </div>
        </div>
    </div>
</section>

@endsection

@section('scripts')
<script>
$(document).ready(function() {
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 3000);
});
</script>
@endsection