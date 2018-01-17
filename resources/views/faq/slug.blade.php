@extends('layouts.app')

@section('sidebar')
    @include('sidebar', ['page' => 'faq'])
@endsection

@section('content')
    @foreach ($qnas as $qna)
    <main id="main-container">
        <div class="content bg-gray-lighter">
            <div class="row items-push">
                <div class="col-sm-7">
                    <h1 class="page-heading">
                        <i class="si si-direction"></i> FAQ: {{ $qna->question }}<small> </small>
                    </h1>
                </div>
            </div>
        </div>

        <!-- Page Content -->
        <div class="content content-boxed">
                <div class="row">
                    <div class="col-sm-12">
                        <a class="block block-link-hover3 question-link" href="#">
                            <div class="block-content" style='padding-bottom: 40px;'>

                                <p> {!! $qna->answer !!}</p>

                            </div>
                            <div class="push" style="padding:15px">
                                <em class="pull-right"><small>{{ \Carbon\Carbon::parse($qna->written_at)->diffForHumans() }}</small></em>
                                <small><span class="text-primary font-w600">{{ $qna->author }}</span>
                                    last updated this on {{ \Carbon\Carbon::parse($qna->written_at)->toFormattedDateString() }}</small>
                            </div>
                        </a>
                    </div>
                </div>
        </div>
    </main>
    @endforeach
@endsection
@section('js')
    @include('dashboard.js')
@endsection