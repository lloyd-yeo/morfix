@extends('layouts.app')

@section('sidebar')
@include('sidebar', ['page' => 'faq'])
@endsection

@section('content')
<main id="main-container">
    <div class="content bg-gray-lighter">
        <div class="row items-push">
            <div class="col-sm-7">
                <h1 class="page-heading">
                    <i class="fa fa-question-circle-o"></i> FAQ <small> Frequently asked questions.</small>
                </h1>
            </div>
        </div>
    </div>

    <!-- Page Content -->
    <div class="content content-boxed">
        <div class="row">
            <div class="col-sm-12">
                <a class="block block-link-hover2 text-center" href="javascript:void(0)">
                    <div class="block-content block-content-full border-b">
                        <span class="text-left"><i class="si si-direction fa-5x text-info"></i></span>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <!-- END Page Content -->
</main>
@endsection