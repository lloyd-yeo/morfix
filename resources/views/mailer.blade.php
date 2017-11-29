@extends('layouts.app')


@section('css')
	@include('settings.css')
	<link rel="stylesheet" href="{{ asset('assets/js/plugins/summernote/summernote.min.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/js/plugins/simplemde/simplemde.min.css') }}">
@endsection

@section('sidebar')
	@include('sidebar', ['page' => 'mailer'])
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
			<form class="form-horizontal" action="/mailer/send/active" method="post">
				<div class="form-group">
					<div class="col-xs-12">
						<div class="block">
							<div class="block-header">
								<ul class="block-options">
									<li>
										<button type="button"><i class="si si-mail"></i></button>
									</li>
								</ul>
								<h3 class="block-title">Send Mail to Active/Paying Affiliates</h3>
							</div>
							<div class="block-content">
								{{--<form id="mailer-form" class="form-horizontal" action="/mailer/send/active" method="post">           --}}
									<div class="form-group">
										<div class="col-xs-12">
											<label>
												Subject:
											</label>
											<input class="form-control" type="text" id="subject-text" name="subject">
										</div>
										<br/>
										<div class="col-xs-12">
											<!-- SimpleMDE Container -->
											<textarea class="js-simplemde" id="simplemde" name="text">Type your content here!</textarea>
										</div>
									</div>
									<button id="send-email-btn" type="submit" class="btn btn-primary pull-right" style="margin-bottom: 20px;">Send Email</button>
								{{--</form> --}}
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