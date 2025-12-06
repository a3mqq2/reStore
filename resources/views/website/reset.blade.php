@extends('layouts.web')

@section('title', 'هل نسيت كلمة السر؟')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center p-3">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-light p-3" style="background-color: #061f46!important;"> اعادة تعيين كلمة السر   </div>
                <div class="card-body">
                    <form action="{{route('website.reset-send')}}" method="POST">
                        @csrf
                        @method('POST')
                        <div class="row m-3">
                            <div class="form-group row mt-3">
                                <label for="name" class="col-md-6 col-form-label text-md-right">{{ __('ادخل بريدك الالكتروني') }}</label>
                                <div class="col-md-12">
                                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group row mt-4">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary btn-block" style="padding: 10px 0; font-size: 1.2em;background-color: #061f46!important;">
                                            {{ __('ارسال رابط تعيين كلمة السر') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
