@extends('layouts.app')

@section('content')
<div class="container-fluid px-md-4 py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            {{-- Header --}}
            <div class="mb-5">
                <h1 class="display-6 fw-extrabold text-dark mb-1 tracking-tight">Profile Settings</h1>
                <p class="text-muted fw-medium">Manage your security and account preferences.</p>
            </div>

            @if(session('status') === 'profile-updated')
                <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center">
                    <i class="fas fa-check-circle me-3 fs-5"></i>
                    <span class="fw-bold">Profile information updated successfully.</span>
                </div>
            @endif

            @if(session('status') === 'password-updated')
                <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center">
                    <i class="fas fa-key me-3 fs-5"></i>
                    <span class="fw-bold">Your password has been changed successfully.</span>
                </div>
            @endif

            {{-- Update Password Card --}}
            <div class="card dash-card border-0 shadow-sm mb-4">
                <div class="card-body p-4 p-md-5">
                    <div class="d-flex align-items-center mb-4">
                        <div class="icon-box bg-primary-light me-3">
                            <i class="fas fa-shield-halved text-primary fs-4"></i>
                        </div>
                        <div>
                            <h5 class="fw-extrabold mb-1">Security & Password</h5>
                            <p class="text-muted small mb-0">Update your login credentials to keep your account secure.</p>
                        </div>
                    </div>

                    <form method="post" action="{{ route('password.update') }}" class="mt-4">
                        @csrf
                        @method('put')

                        <div class="row g-4">
                            <div class="col-12">
                                <label for="current_password" class="form-label small fw-bold text-uppercase tracking-wider">Current Password</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-gray-100"><i class="fas fa-lock opacity-50"></i></span>
                                    <input id="current_password" name="current_password" type="password" class="form-control border-gray-100 bg-gray-50 py-3" required autocomplete="current-password" placeholder="••••••••">
                                </div>
                                @if($errors->updatePassword->has('current_password'))
                                    <div class="text-danger smaller fw-bold mt-2 italic">
                                        {{ $errors->updatePassword->first('current_password') }}
                                    </div>
                                @endif
                            </div>

                            <div class="col-md-6">
                                <label for="password" class="form-label small fw-bold text-uppercase tracking-wider">New Password</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-gray-100"><i class="fas fa-key opacity-50"></i></span>
                                    <input id="password" name="password" type="password" class="form-control border-gray-100 bg-gray-50 py-3" required autocomplete="new-password" placeholder="New Password">
                                </div>
                                @if($errors->updatePassword->has('password'))
                                    <div class="text-danger smaller fw-bold mt-2 italic">
                                        {{ $errors->updatePassword->first('password') }}
                                    </div>
                                @endif
                            </div>

                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label small fw-bold text-uppercase tracking-wider">Confirm New Password</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-gray-100"><i class="fas fa-check-double opacity-50"></i></span>
                                    <input id="password_confirmation" name="password_confirmation" type="password" class="form-control border-gray-100 bg-gray-50 py-3" required autocomplete="new-password" placeholder="Confirm New Password">
                                </div>
                            </div>

                            <div class="col-12 text-end pt-3">
                                <button type="submit" class="btn btn-primary px-5 py-3 rounded-pill fw-bold shadow-primary">
                                    <i class="fas fa-save me-2 small"></i> Save Password
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Update Profile Card --}}
            <div class="card dash-card border-0 shadow-sm">
                <div class="card-body p-4 p-md-5">
                    <div class="d-flex align-items-center mb-4">
                        <div class="icon-box bg-info-light me-3">
                            <i class="fas fa-circle-user text-info fs-4"></i>
                        </div>
                        <div>
                            <h5 class="fw-extrabold mb-1">Personal Information</h5>
                            <p class="text-muted small mb-0">Manage your name and contact email address.</p>
                        </div>
                    </div>

                    <form method="post" action="{{ route('profile.update') }}" class="mt-4">
                        @csrf
                        @method('patch')

                        <div class="row g-4">
                            <div class="col-md-6">
                                <label for="name" class="form-label small fw-bold text-uppercase tracking-wider">Full Name</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-gray-100"><i class="fas fa-user opacity-50"></i></span>
                                    <input id="name" name="name" type="text" class="form-control border-gray-100 bg-gray-50 py-3" value="{{ old('name', Auth::user()->name) }}" required autocomplete="name">
                                </div>
                                @error('name')
                                    <div class="text-danger smaller fw-bold mt-2 italic">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="email" class="form-label small fw-bold text-uppercase tracking-wider">Email Address</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-gray-100"><i class="fas fa-envelope opacity-50"></i></span>
                                    <input id="email" name="email" type="email" class="form-control border-gray-100 bg-gray-50 py-3" value="{{ old('email', Auth::user()->email) }}" required autocomplete="username">
                                </div>
                                @error('email')
                                    <div class="text-danger smaller fw-bold mt-2 italic">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 text-end pt-3">
                                <button type="submit" class="btn btn-dark px-5 py-3 rounded-pill fw-bold">
                                    <i class="fas fa-save me-2 small"></i> Update Profile
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@200;300;400;500;600;700;800&display=swap');
    body { font-family: 'Plus Jakarta Sans', sans-serif !important; background-color: #f4f7fa; }
    .bg-primary-light { background-color: rgba(239, 124, 27, 0.08); }
    .bg-info-light { background-color: rgba(13, 202, 240, 0.08); }
    .shadow-primary { box-shadow: 0 8px 20px rgba(239, 124, 27, 0.3); }
    .fw-extrabold { font-weight: 800; }
    .tracking-tight { letter-spacing: -0.04em; }
    .italic { font-style: italic; }
</style>
@endsection
