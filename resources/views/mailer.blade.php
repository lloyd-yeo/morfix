@extends('layouts.app')


@section('css')
	@include('settings.css')
	<link rel="stylesheet" href="{{ asset('assets/js/plugins/summernote/summernote.min.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/js/plugins/simplemde/simplemde.min.css') }}">
@endsection

@section('sidebar')
	@include('sidebar', ['page' => 'settings'])
@endsection

@section('content')
	<main id="main-container">
		<div class="content bg-gray-lighter">
			<div class="row items-push">
				<div class="col-sm-7">
					<h1 class="page-heading">
						<i class="si si-mailer"></i> Mailer
						<small> Administrative Mailer</small>
					</h1>
				</div>
			</div>
		</div>

		<div class="content content-boxed">
			<!-- Dynamic Table Full -->
			<form class="form-horizontal" action="/mail/send/active" method="post" onsubmit="return false;">
				<div class="form-group">
					<div class="col-xs-12">
						<!-- SimpleMDE Editor (js-simplemde class is initialized in App() -> uiHelperSimpleMDE()) -->
						<!-- For more info and examples you can check out https://github.com/NextStepWebs/simplemde-markdown-editor -->
						<h2 class="content-heading">Mail to Active/Paying Users</h2>
						<div class="block">
							<div class="block-header">
								<ul class="block-options">
									<li>
										<button type="button"><i class="si si-mail"></i></button>
									</li>
								</ul>
								<h3 class="block-title">Mailers</h3>
							</div>
							<div class="block-content">
								<form class="form-horizontal" action="/mailer/send/active" method="post"
								      onsubmit="return false;">
									<div class="form-group">
										<div class="col-xs-12">
											<!-- SimpleMDE Container -->
											<textarea class="js-simplemde" id="simplemde" name="simplemde">Type your content here!</textarea>
										</div>
									</div>
								</form>
							</div>
						</div>
						<!-- END SimpleMDE Editor -->
					</div>
				</div>
			</form>
		</div>

		</div>

	</main>
@endsection

@section('js')
	<script src="{{ asset('assets/js/plugins/simplemde/simplemde.min.js') }}"></script>
	<script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        jQuery(function () {
            // Init page helpers (Summernote + CKEditor + SimpleMDE plugins)
            App.initHelpers(['simplemde']);
        });
	</script>
@endsection