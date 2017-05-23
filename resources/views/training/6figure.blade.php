@extends('layouts.app')

@section('css')
@include('postscheduling.css')
@endsection

@section('sidebar')
@include('sidebar', ['page' => 'training-morfix'])
@endsection

@section('content')
<main id="main-container">
    <div class="content bg-gray-lighter">
        <div class="row items-push">
            <div class="col-sm-7">
                <h1 class="page-heading">
                    <i class="si si-star"></i>  6 Figure Profile <small> Learn the tips & tricks to build a 6 figure profile!</small>
                </h1>
            </div>
        </div>
    </div>
</main>
@endsection

@section('js')
@include('postscheduling.js')
@endsection