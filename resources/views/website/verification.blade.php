@extends('layouts.web')

@section('title', 'التحقق من البريد الإلكتروني')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center p-3">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-light p-3" style="background-color: #061f46!important;">التحقق من البريد الإلكتروني</div>
                <div class="card-body">
                    <form action="{{ route('verify.otp') }}" method="POST">
                        @csrf
                        <div class="row p-5">
                            <input type="hidden" name="email" value="{{ $email }}">
                            
                            <div class="col-12">
                                <div class="form-group row mt-3">
                                    <label for="otp" class="col-md-6 col-form-label text-md-right">{{ __('رمز التحقق') }}</label>
                                    <div class="col-md-12">
                                        <input id="otp" type="text" class="form-control @error('otp') is-invalid @enderror" name="otp" required autofocus>
                                        @error('otp')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group row mt-4">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary btn-block" style="padding: 10px 0; font-size: 1.2em; background-color: #061f46!important;">
                                            {{ __('تحقق') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    
                    @if($errors->any())
                        <div class="alert alert-danger mt-3">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
