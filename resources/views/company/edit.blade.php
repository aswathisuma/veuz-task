<x-layouts.app title="Edit Company">

    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Edit Company</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('companies.index') }}">Companies</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('companies.update', $company->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label">Company Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $company->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" id="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email', $company->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="website" class="form-label">Website <span class="text-danger">*</span></label>
                            <input type="text" name="website" id="website"
                                   class="form-control @error('website') is-invalid @enderror"
                                   value="{{ old('website', $company->website) }}" required>
                            @error('website')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="logo" class="form-label">Logo</label>
                            <input type="file" name="logo" id="logo" class="form-control @error('logo') is-invalid @enderror" accept="image/*">
                            @error('logo')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror

                            <div class="mt-3" id="logo-preview">
                                @if($company->logo)
                                    <img src="{{ asset('storage/' . $company->logo) }}" 
                                         alt="Company Logo" width="100" height="100" 
                                         class="rounded border" id="current-logo">
                                @endif
                            </div>

                            <small class="form-text text-muted">
                                Minimum size: 100x100 pixels, Max 2MB
                            </small>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">Update</button>
                            <a href="{{ route('companies.index') }}" class="btn btn-light border">Cancel</a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </section>

    <x-slot name="scripts">
        <script>
            $(document).ready(function() {
                setTimeout(function() {
                    $('.alert').fadeOut('slow');
                }, 3000);

                $('#logo').on('change', function(e) {
                    let file = e.target.files[0];
                    let previewDiv = $('#logo-preview');
                    if (file) {
                        let reader = new FileReader();
                        reader.onload = function(e) {
                            previewDiv.empty();
                            $('<img>', {
                                src: e.target.result,
                                width: 100,
                                height: 100,
                                alt: 'Preview',
                                class: 'rounded border mt-2'
                            }).appendTo(previewDiv);
                        };
                        reader.readAsDataURL(file);
                    } else {
                        previewDiv.empty();
                    }
                });
            });
        </script>
    </x-slot>

</x-layouts.app>
