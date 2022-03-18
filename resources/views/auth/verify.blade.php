@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Verificiation Pin') }}</div>

                <div class="card-body">
                    <h4> Verification Pin: <b> {{$code}} </b>  </h4>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
