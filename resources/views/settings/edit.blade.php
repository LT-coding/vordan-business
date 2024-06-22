@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">{{ __('User Settings') }}</div>

            <div class="card-body">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('settings.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="email" class="form-label">{{ __('Email address') }}</label>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $user->email) }}" required autofocus>
                        @error('email')
                        <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label">{{ __('Phone number') }}</label>
                        <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone', $user->phone) }}" required>
                        @error('phone')
                        <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">{{ __('Password') }}</label>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password">
                        @error('password')
                        <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">{{ __('Confirm Password') }}</label>
                        <input id="password_confirmation" type="password" class="form-control" name="password_confirmation">
                    </div>

                    <button type="submit" class="btn btn-primary">{{ __('Update Settings') }}</button>
                </form>
            </div>
        </div>
    </div>
@endsection
