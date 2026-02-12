@extends('layouts.app')

@section('content')
<div class="card mb-3">
    <div class="card-body">
        Selamat datang {{ Auth::user()->name }}, {{ $pos }}
    </div>
</div>
@endsection
