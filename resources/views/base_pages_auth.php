<?php require 'inc/config.php'; ?>
<?php require 'inc/views/template_head_start.php'; ?>
<?php require 'inc/views/template_head_end.php'; ?>
<?php require 'inc/views/base_head.php'; ?>

<!-- Page Header -->
<div class="content bg-gray-lighter">
    <div class="row items-push">
        <div class="col-sm-7">
            <h1 class="page-heading">
                Authentication <small>All pages in one spot!</small>
            </h1>
        </div>
        <div class="col-sm-5 text-right hidden-xs">
            <ol class="breadcrumb push-10-t">
                <li>Authentication</li>
                <li><a class="link-effect" href="">All Pages</a></li>
            </ol>
        </div>
    </div>
</div>
<!-- END Page Header -->

<!-- Page Content -->
<div class="content content-boxed">
    <div class="row">
        <div class="col-sm-6">
            <a class="block block-rounded block-link-hover3 text-center" href="base_pages_login.php">
                <div class="block-content block-content-full">
                    <div class="item item-circle push-10">
                        <i class="si si-login text-default"></i>
                    </div>
                    <div class="h4">Log in</div>
                </div>
            </a>
        </div>
        <div class="col-sm-6">
            <a class="block block-rounded block-link-hover3 text-center" href="base_pages_login_v2.php">
                <div class="block-content block-content-full">
                    <div class="item item-circle push-10">
                        <i class="si si-login text-default"></i>
                    </div>
                    <div class="h4">Log in v2</div>
                </div>
            </a>
        </div>
        <div class="col-sm-6">
            <a class="block block-rounded block-link-hover3 text-center" href="base_pages_register.php">
                <div class="block-content block-content-full">
                    <div class="item item-circle push-10">
                        <i class="si si-user-follow text-success"></i>
                    </div>
                    <div class="h4">Register</div>
                </div>
            </a>
        </div>
        <div class="col-sm-6">
            <a class="block block-rounded block-link-hover3 text-center" href="base_pages_register_v2.php">
                <div class="block-content block-content-full">
                    <div class="item item-circle push-10">
                        <i class="si si-user-follow text-success"></i>
                    </div>
                    <div class="h4">Register v2</div>
                </div>
            </a>
        </div>
        <div class="col-sm-6">
            <a class="block block-rounded block-link-hover3 text-center" href="base_pages_lock.php">
                <div class="block-content block-content-full">
                    <div class="item item-circle push-10">
                        <i class="si si-lock text-city"></i>
                    </div>
                    <div class="h4">Lock Screen</div>
                </div>
            </a>
        </div>
        <div class="col-sm-6">
            <a class="block block-rounded block-link-hover3 text-center" href="base_pages_lock_v2.php">
                <div class="block-content block-content-full">
                    <div class="item item-circle push-10">
                        <i class="si si-lock text-city"></i>
                    </div>
                    <div class="h4">Lock Screen v2</div>
                </div>
            </a>
        </div>
        <div class="col-sm-6">
            <a class="block block-rounded block-link-hover3 text-center" href="base_pages_reminder.php">
                <div class="block-content block-content-full">
                    <div class="item item-circle push-10">
                        <i class="si si-support text-modern"></i>
                    </div>
                    <div class="h4">Password Reminder</div>
                </div>
            </a>
        </div>
        <div class="col-sm-6">
            <a class="block block-rounded block-link-hover3 text-center" href="base_pages_reminder_v2.php">
                <div class="block-content block-content-full">
                    <div class="item item-circle push-10 push-10">
                        <i class="si si-support text-modern"></i>
                    </div>
                    <div class="h4 push-5">Password Reminder v2</div>
                </div>
            </a>
        </div>
    </div>
</div>
<!-- END Page Content -->

<?php require 'inc/views/base_footer.php'; ?>
<?php require 'inc/views/template_footer_start.php'; ?>
<?php require 'inc/views/template_footer_end.php'; ?>