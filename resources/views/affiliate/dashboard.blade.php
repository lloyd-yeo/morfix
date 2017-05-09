@extends('layouts.app')

@section('sidebar')
@include('sidebar', ['page' => 'affiliate'])
@endsection

@section('content')
<main id="main-container">
    <div class="content bg-gray-lighter">
        <div class="row items-push">
            <div class="col-sm-7">
                <h1 class="page-heading">
                    <i class="si si-trophy"></i> Affiliate <small> View your referrals & commisions here.</small>
                </h1>
            </div>
        </div>
    </div>

    <div class="content content-boxed">
        <div class="row font-s13">
            
        </div>
    </div>
</main>
@endsection