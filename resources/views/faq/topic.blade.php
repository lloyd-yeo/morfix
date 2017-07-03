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
                    <div class="block-content block-content-full border-b" style="overflow-y:auto;">
                        <div class="col-lg-3 col-sm-3">
                            <span class="pull-left push-20-l"><i class="si si-direction text-info" style="font-size:10em;"></i></span>
                        </div>
                        <div class="col-lg-9" style="float:left;">
                            <p class="pull-left font-s36 font-w600 push-20-l">General Queries</p>
                        </div>
                        <br/><br/>
                        <div class="col-lg-9" style="float:left;">
                            <p class="pull-left push-20-l">Some general things about general queries that are really general.</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <!-- END Page Content -->
</main>
@endsection