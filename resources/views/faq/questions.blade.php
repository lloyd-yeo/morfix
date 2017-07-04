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
                    <i class="si si-direction"></i> {{ $topic->topic }} Queries <small> </small>
                </h1>
            </div>
        </div>
    </div>

    <!-- Page Content -->
    <div class="content content-boxed">
        @foreach ($qnas as $qna)
        <div class="row">
            <div class="col-sm-12">
                <a class="block block-link-hover3 question-link" data-q='{{ $qna->id }}'>
                    <div class="block-content" style='padding-bottom: 40px;'>
                        <div class="push">
                            <em class="pull-right">{{ \Carbon\Carbon::parse($qna->written_at)->diffForHumans() }}</em>
                            <span class="text-primary font-w600">{{ $qna->author }}</span> last updated this on {{ \Carbon\Carbon::parse($qna->written_at)->toFormattedDateString() }}
                        </div>
                        
                        <h4 class="push-10">{{ $qna->question }}</h4>
                        <p>{{ str_limit($qna->answer, 100, '...') }}...</p>
                    </div>
                </a>
            </div>
        </div>
        @endforeach
    </div>
    <!-- END Page Content -->
    
    @include('faq.modal')
    
</main>
@endsection


@section('js')
@include('dashboard.js')
@endsection