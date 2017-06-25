@extends('layouts.app')

@section('sidebar')
@include('sidebar', ['page' => 'payment'])
@endsection

@section('content')
<main id="main-container">
    <div class="content bg-gray-lighter">
        <div class="row items-push">
            <div class="col-sm-7">
                <h1 class="page-heading">
                    <i class="si si-bag"></i> Payment <small> Upgrade your account now to unlock more training videos & functions.</small>
                </h1>
            </div>
        </div>
    </div>

    <div class="content content-boxed">
        <div class="row font-s13">
            @if (Auth::user()->tier == 1)
            @include('payment.table.premium')
            @include('payment.table.pro')
            @elseif (Auth::user()->tier == 2)
            @include('payment.table.pro')
            @include('payment.table.business')
            @include('payment.table.mastermind')
            @elseif (Auth::user()->tier == 3)
            @include('payment.table.business')
            @include('payment.table.mastermind')
            @elseif (Auth::user()->tier == 12)
            @include('payment.table.pro')
            @include('payment.table.mastermind')
            @elseif (Auth::user()->tier == 22)
            <div class='col-lg-3'></div>
            @include('payment.table.pro')
            @elseif (Auth::user()->tier == 13)
            <div class='col-lg-3'></div>
            @include('payment.table.mastermind')
            @endif
        </div>
    </div>
</main>
@endsection