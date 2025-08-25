@extends('layout.main')

@push('script')
<script>
    $('input#accountActivation').on('change', function () {
        $('button.deactivate-account').attr('disabled', !$(this).is(':checked'));
    });
    
    // Reset image
    $('.account-image-reset').on('click', function() {
        $('#uploadedAvatar').attr('src', 'https://ui-avatars.com/api/?background=6D67E4&color=fff&name={{ urlencode($data->name) }}');
        // Buat input hidden untuk menandai bahwa avatar harus direset
        if ($('input[name="reset_avatar"]').length === 0) {
            $('<input>').attr({
                type: 'hidden',
                name: 'reset_avatar',
                value: '1'
            }).appendTo('form');
        }
    });
    
    // Preview image before upload
    $('#upload').on('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#uploadedAvatar').attr('src', e.target.result);
            };
            reader.readAsDataURL(file);
            // Hapus input reset_avatar jika ada
            $('input[name="reset_avatar"]').remove();
        }
    });
    
    // Toggle password visibility
    document.addEventListener('DOMContentLoaded', function() {
        const toggles = document.querySelectorAll('.toggle-password');
        toggles.forEach(toggle => {
            toggle.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const passwordInput = document.getElementById(targetId);
                
                // Toggle type attribute
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                
                // Toggle icon
                const icon = this.querySelector('i');
                if (type === 'password') {
                    icon.className = 'bx bx-hide';
                } else {
                    icon.className = 'bx bx-show';
                }
            });
        });
    });
</script>
@endpush

@section('content')
<x-breadcrumb :values="[__('navbar.profile.profile')]"></x-breadcrumb>

<div class="row">
    <div class="col">
        @if(auth()->user()->role == 'admin')
        <ul class="nav nav-pills flex-column flex-md-row mb-3">
            <li class="nav-item">
                <a class="nav-link active" href="javascript:void(0);">{{ __('navbar.profile.profile') }}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('settings.show') }}">{{ __('navbar.profile.settings') }}</a>
            </li>
        </ul>
        @endif

        <div class="card mb-4">
            <form action="{{ route('profile.update') }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="d-flex align-items-start align-items-sm-center gap-4">
                        <img src="{{ $data->profile_picture }}" alt="user-avatar"
                            class="d-block rounded" height="100" width="100" id="uploadedAvatar">
                        <div class="button-wrapper">
                            <label for="upload" class="btn btn-primary me-2 mb-4" tabindex="0">
                                <span class="d-none d-sm-block">Unggah Foto</span>
                                <i class="bx bx-upload d-block d-sm-none"></i>
                                <input type="file" name="profile_picture" id="upload" class="account-file-input" hidden=""
                                    accept="image/png, image/jpeg">
                            </label>
                            <button type="button" class="btn btn-outline-secondary account-image-reset mb-4">
                                <i class="bx bx-reset d-block d-sm-none"></i>
                                <span class="d-none d-sm-block">Hapus Foto</span>
                            </button>
                            <p class="text-muted mb-0">< 800K (JPG, GIF, PNG)</p>
                        </div>
                    </div>
                </div>
                <hr class="my-0">
                <div class="card-body">
                    <div class="row">
                        <input type="hidden" name="id" value="{{ $data->id }}">
                        <div class="col-md-6 col-lg-12">
                            <x-input-form name="name" :label="__('model.user.name')" :value="$data->name" />
                        </div>
                        <div class="col-md-6 col-lg-12">
                            <x-input-form name="email" :label="__('model.user.email')" :value="$data->email" />
                        </div>
                    </div>
                    
                    <h5 class="fw-semibold mt-4 mb-3">Ubah Password</h5>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="current_password" class="form-label">Password Saat Ini</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="current_password" name="current_password">
                                    <span class="input-group-text toggle-password" data-target="current_password">
                                        <i class="bx bx-hide"></i>
                                    </span>
                                </div>
                                @error('current_password')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="new_password" class="form-label">Password Baru</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="new_password" name="new_password">
                                    <span class="input-group-text toggle-password" data-target="new_password">
                                        <i class="bx bx-hide"></i>
                                    </span>
                                </div>
                                @error('new_password')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="new_password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation">
                                    <span class="input-group-text toggle-password" data-target="new_password_confirmation">
                                        <i class="bx bx-hide"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <small class="text-muted">
                                Kosongkan field password jika tidak ingin mengubahnya. Jika diisi, password harus minimal 8 karakter, mengandung huruf besar, huruf kecil, dan angka.
                            </small>
                        </div>
                    </div>
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary me-2">Simpan</button>
                        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">Kembali</a>
                    </div>
                </div>
            </form>
        </div>

        @if(auth()->user()->role == 'staff')
        <div class="card">
            <h5 class="card-header">{{ __('navbar.profile.deactivate_account') }}</h5>
            <div class="card-body">
                <div class="mb-3 col-12 mb-0">
                    <div class="alert alert-warning">
                        <h6 class="alert-heading fw-bold mb-1">{{ __('navbar.profile.deactivate_confirm_message') }}</h6>
                    </div>
                </div>
                <form id="formAccountDeactivation" action="{{ route('profile.deactivate') }}" method="post">
                    @csrf
                    @method('PUT')
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="accountActivation" id="accountActivation">
                        <label class="form-check-label" for="accountActivation">{{ __('navbar.profile.deactivate_confirm') }}</label>
                    </div>
                    <button type="submit" class="btn btn-danger deactivate-account" disabled>{{ __('navbar.profile.deactivate_account') }}</button>
                </form>
            </div>
        </div>
        @endif
    </div>
</div>

@endsection
