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
                    <i class="fa-quetion-circle-o"></i> Faq <small> Frequently asked questions.</small>
                </h1>
            </div>
        </div>
    </div>

    <!-- Page Content -->
    <div class="content content-boxed">
        <!-- Frequently Asked Questions -->
        <div class="block">
            <div class="block-content block-content-full block-content-narrow">
                <!-- Introduction -->
                @foreach($question_and_answers as $topic_name=>$qnas)
                <h2 class="h3 font-w600 push-30-t push">{{ $topic_name }}</h2>
                <div id="topic{{ $loop->iteration }}" class="panel-group">
                    @foreach($qnas as $qna)
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#topic{{ $loop->parent->iteration }}" href="#topic{{ $loop->parent->iteration }}_q{{ $loop->iteration }}">{{ $qna->question }}</a>
                            </h3>
                        </div>
                        <div id="topic{{ $loop->parent->iteration }}_q{{ $loop->iteration }}" class="panel-collapse collapse">
                            <div class="panel-body">
                                <p>{{ $qna->answer }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endforeach

            </div>
        </div>
        <!-- END Frequently Asked Questions -->
    </div>
    <!-- END Page Content -->
</main>
@endsection