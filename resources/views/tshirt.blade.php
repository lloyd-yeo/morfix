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
						<i class="fa fa-question-circle-o"></i> T-Shirt <small> Ask for your Morfix T-Shirt!</small>
					</h1>
				</div>
			</div>
		</div>

		<!-- Page Content -->
		<div class="content content-boxed">
			<!-- Frequently Asked Questions -->
			<div class="block">
				<div class="block-content block-content-full block-content-narrow">

				</div>
			</div>
			<!-- END Frequently Asked Questions -->
		</div>
		<!-- END Page Content -->
	</main>
@endsection