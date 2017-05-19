@extends('layouts.app')

@section('css')
@include('postscheduling.css')
@endsection

@section('sidebar')
@include('sidebar', ['page' => 'payment'])
@endsection

@section('content')
<main id="main-container">
    <div class="content bg-gray-lighter">
        <div class="row items-push">
            <div class="col-sm-7">
                <h1 class="page-heading">
                    <i class="si si-bag"></i>  Upgrade <small> Upgrade to enjoy more of Morfix!</small>
                </h1>
            </div>
        </div>
    </div>
</main>
@endsection

@section('js')
@include('postscheduling.js')
@endsection