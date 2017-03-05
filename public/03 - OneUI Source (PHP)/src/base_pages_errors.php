<?php require 'inc/config.php'; ?>
<?php require 'inc/views/template_head_start.php'; ?>
<?php require 'inc/views/template_head_end.php'; ?>
<?php require 'inc/views/base_head.php'; ?>

<!-- Page Header -->
<div class="content bg-gray-lighter">
    <div class="row items-push">
        <div class="col-sm-7">
            <h1 class="page-heading">
                Errors <small>All pages in one spot!</small>
            </h1>
        </div>
        <div class="col-sm-5 text-right hidden-xs">
            <ol class="breadcrumb push-10-t">
                <li>Error Pages</li>
                <li><a class="link-effect" href="">All Pages</a></li>
            </ol>
        </div>
    </div>
</div>
<!-- END Page Header -->

<!-- Page Content -->
<div class="content content-boxed">
    <div class="row">
        <div class="col-xs-6 col-sm-4">
            <a class="block block-rounded block-link-hover3 text-center" href="base_pages_400.php">
                <div class="block-content block-content-full">
                    <div class="h1 font-w700 text-default push-5">400</div>
                    <div>Error Page</div>
                </div>
            </a>
        </div>
        <div class="col-xs-6 col-sm-4">
            <a class="block block-rounded block-link-hover3 text-center" href="base_pages_401.php">
                <div class="block-content block-content-full">
                    <div class="h1 font-w700 text-amethyst push-5">401</div>
                    <div>Error Page</div>
                </div>
            </a>
        </div>
        <div class="col-xs-6 col-sm-4">
            <a class="block block-rounded block-link-hover3 text-center" href="base_pages_403.php">
                <div class="block-content block-content-full">
                    <div class="h1 font-w700 text-flat push-5">403</div>
                    <div>Error Page</div>
                </div>
            </a>
        </div>
        <div class="col-xs-6 col-sm-4">
            <a class="block block-rounded block-link-hover3 text-center" href="base_pages_404.php">
                <div class="block-content block-content-full">
                    <div class="h1 font-w700 text-city push-5">404</div>
                    <div>Error Page</div>
                </div>
            </a>
        </div>
        <div class="col-xs-6 col-sm-4">
            <a class="block block-rounded block-link-hover3 text-center" href="base_pages_500.php">
                <div class="block-content block-content-full">
                    <div class="h1 font-w700 text-modern push-5">500</div>
                    <div>Error Page</div>
                </div>
            </a>
        </div>
        <div class="col-xs-6 col-sm-4">
            <a class="block block-rounded block-link-hover3 text-center" href="base_pages_503.php">
                <div class="block-content block-content-full">
                    <div class="h1 font-w700 text-smooth push-5">503</div>
                    <div>Error Page</div>
                </div>
            </a>
        </div>
    </div>
</div>
<!-- END Page Content -->

<?php require 'inc/views/base_footer.php'; ?>
<?php require 'inc/views/template_footer_start.php'; ?>
<?php require 'inc/views/template_footer_end.php'; ?>