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
			<div class="block">
				<div class="block-header">
					<ul class="block-options">
						<li>
							<button type="button"><i class="si si-settings"></i></button>
						</li>
					</ul>
					<h3 class="block-title">Mailer</h3>
				</div>
				<div class="block-content">
					<form class="form-horizontal" action="/mail/send/active" method="post" onsubmit="return false;">
						<div class="form-group">
							<div class="col-xs-12">
								<!-- SimpleMDE Container -->
								<textarea class="js-simplemde" id="simplemde" name="simplemde" style="display: none;">Email Content</textarea>
								<div class="editor-toolbar">
									<a title="Bold (Cmd-B)" tabindex="-1" class="fa fa-bold"></a>
									<a title="Italic (Cmd-I)" tabindex="-1" class="fa fa-italic"></a>
									<a title="Heading (Cmd-H)" tabindex="-1" class="fa fa-header"></a>
									<i class="separator">|</i>
									<a title="Quote (Cmd-')" tabindex="-1" class="fa fa-quote-left"></a>
									<a title="Generic List (Cmd-L)" tabindex="-1" class="fa fa-list-ul"></a>
									<a title="Numbered List (Cmd-⌥-L)" tabindex="-1" class="fa fa-list-ol"></a>
									<i class="separator">|</i>
									<a title="Create Link (Cmd-K)" tabindex="-1" class="fa fa-link"></a>
									<a title="Insert Image (Cmd-⌥-I)" tabindex="-1" class="fa fa-picture-o"></a>
									<i class="separator">|</i>
									<a title="Toggle Preview (Cmd-P)" tabindex="-1" class="fa fa-eye no-disable"></a>
									<a title="Toggle Side by Side (F9)" tabindex="-1"
									   class="fa fa-columns no-disable no-mobile"></a>
									<a title="Toggle Fullscreen (F11)" tabindex="-1"
									   class="fa fa-arrows-alt no-disable no-mobile"></a>
									<i class="separator">|</i>
									<a title="Markdown Guide" tabindex="-1" class="fa fa-question-circle"
									   href="https://simplemde.com/markdown-guide" target="_blank"></a>
								</div>
								<div class="CodeMirror cm-s-paper CodeMirror-wrap">
									<div style="overflow: hidden; position: relative; width: 3px; height: 0px; top: 15px; left: 15px;">
										<textarea autocorrect="off" autocapitalize="off" spellcheck="false"
										          style="position: absolute; padding: 0px; width: 1000px; height: 1em; outline: none;"
										          tabindex="0"></textarea></div>
									<div class="CodeMirror-vscrollbar" cm-not-content="true"
									     style="width: 18px; pointer-events: none;">
										<div style="min-width: 1px; height: 0px;"></div>
									</div>
									<div class="CodeMirror-hscrollbar" cm-not-content="true"
									     style="height: 18px; pointer-events: none;">
										<div style="height: 100%; min-height: 1px; width: 0px;"></div>
									</div>
									<div class="CodeMirror-scrollbar-filler" cm-not-content="true"></div>
									<div class="CodeMirror-gutter-filler" cm-not-content="true"></div>
									<div class="CodeMirror-scroll" tabindex="-1">
										<div class="CodeMirror-sizer"
										     style="margin-left: 0px; margin-bottom: 0px; border-right-width: 30px; min-height: 28px; padding-right: 0px; padding-bottom: 0px;">
											<div style="position: relative; top: 0px;">
												<div class="CodeMirror-lines">
													<div style="position: relative; outline: none;">
														<div class="CodeMirror-measure"></div>
														<div class="CodeMirror-measure"></div>
														<div style="position: relative; z-index: 1;"></div>
														<div class="CodeMirror-cursors">
															<div class="CodeMirror-cursor"
															     style="left: 4px; top: 0px; height: 20px;">&nbsp;
															</div>
														</div>
														<div class="CodeMirror-code">
															<pre class=" CodeMirror-line "><span
																		style="padding-right: 0.1px;">Hello SimpleMDE!</span></pre>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div style="position: absolute; height: 30px; width: 1px; border-bottom: 0px solid transparent; top: 28px;"></div>
										<div class="CodeMirror-gutters" style="display: none; height: 58px;"></div>
									</div>
								</div>
								<div class="editor-preview-side"></div>
								<div class="editor-statusbar"><span class="autosave"></span><span class="lines">1</span><span
											class="words">2</span><span class="cursor">0:0</span></div>
							</div>
						</div>
					</form>
				</div>
			</div>

		</div>

	</main>
@endsection

@section('js')
	@include('settings.js')
@endsection