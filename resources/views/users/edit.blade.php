@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Edit User') }}</div>

                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('users.update', $user->id) }}">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="email">{{ __('Email') }}</label>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $user->email) }}" required autocomplete="email">
                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="phone">{{ __('Phone') }}</label>
                                <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone', $user->phone) }}" required>
                                @error('phone')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>{{ __('Select Businesses') }}</label><br>
                                @foreach ($businesses as $business)
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" id="business_{{ $business->id }}" name="businesses[]" value="{{ $business->id }}" {{ $user->businesses->contains($business->id) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="business_{{ $business->id }}">{{ $business->company_name }}</label>
                                    </div>
                                @endforeach
                                @error('businesses')
                                <div class="invalid-feedback" style="display: block;">
                                    <strong>{{ $message }}</strong>
                                </div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary">{{ __('Update User') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
