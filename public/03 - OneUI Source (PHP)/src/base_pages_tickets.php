<?php require 'inc/config.php'; ?>
<?php require 'inc/views/template_head_start.php'; ?>
<?php require 'inc/views/template_head_end.php'; ?>
<?php require 'inc/views/base_head.php'; ?>

<!-- Page Content -->
<div class="content">
    <div class="row">
        <div class="col-sm-5 col-lg-3">
            <!-- Collapsible Tickets Navigation (using Bootstrap collapse functionality) -->
            <button class="btn btn-block btn-primary visible-xs push" data-toggle="collapse" data-target="#tickets-nav" type="button">Navigation</button>
            <div class="collapse navbar-collapse remove-padding" id="tickets-nav">
                <!-- Tickets Menu -->
                <div class="block">
                    <div class="block-header bg-gray-lighter">
                        <ul class="block-options">
                            <li>
                                <button data-toggle="modal" data-target="#modal-compose" type="button"><i class="si si-settings"></i></button>
                            </li>
                        </ul>
                        <h3 class="block-title">Tickets</h3>
                    </div>
                    <div class="block-content">
                        <ul class="nav nav-pills nav-stacked push">
                            <li class="active">
                                <a href="base_pages_tickets.php">
                                    <span class="badge pull-right">750</span><i class="fa fa-fw fa-inbox push-5-r"></i> Archive
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0)">
                                    <span class="badge pull-right">5</span><i class="fa fa-fw fa-warning push-5-r"></i> Urgent
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0)">
                                    <span class="badge pull-right">50</span><i class="fa fa-fw fa-folder-open-o push-5-r"></i> Open
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0)">
                                    <span class="badge pull-right">700</span><i class="fa fa-fw fa-folder-o push-5-r"></i> Closed
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- END Tickets Menu -->

                <!-- Quick Stats -->
                <div class="block">
                    <div class="block-header bg-gray-lighter">
                        <ul class="block-options">
                            <li>
                                <button type="button"><i class="si si-settings"></i></button>
                            </li>
                        </ul>
                        <h3 class="block-title">Quick Stats</h3>
                    </div>
                    <div class="block-content">
                        <table class="table table-borderless table-condensed table-vcenter font-s13">
                            <tbody>
                                <tr>
                                    <td class="font-w600" style="width: 75%;">Tickets</td>
                                    <td class="text-right">720</td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <div class="clearfix push-5">
                                            <span class="pull-right text-muted text-right">500</span>
                                            -- Email
                                        </div>
                                        <div class="clearfix push-5">
                                            <span class="pull-right text-muted text-right">220</span>
                                            -- Contact Form
                                        </div>
                                        <div class="clearfix">
                                            <span class="pull-right text-muted text-right">30</span>
                                            -- Forum
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-w600">Responses</td>
                                    <td class="text-right">2355</td>
                                </tr>
                                <tr>
                                    <td class="font-w600">Response Time (avg)</td>
                                    <td class="text-right">2 hrs</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- END Quick Stats -->
            </div>
            <!-- END Collapsible Tickets Navigation -->
        </div>
        <div class="col-sm-7 col-lg-9">
            <!-- Tickets List -->
            <div class="block">
                <div class="block-header bg-gray-lighter">
                    <ul class="block-options">
                        <li>
                            <button class="js-tooltip" title="Previous 10 Tickets" type="button" data-toggle="block-option"><i class="si si-arrow-left"></i></button>
                        </li>
                        <li>
                            <button class="js-tooltip" title="Next 10 Tickets" type="button" data-toggle="block-option"><i class="si si-arrow-right"></i></button>
                        </li>
                        <li>
                            <button type="button" data-toggle="block-option" data-action="refresh_toggle" data-action-mode="demo"><i class="si si-refresh"></i></button>
                        </li>
                        <li>
                            <button type="button" data-toggle="block-option" data-action="fullscreen_toggle"></button>
                        </li>
                    </ul>
                    <div class="block-title text-normal">
                        <strong>11-20</strong> <span class="font-w400">from</span> <strong>750</strong>
                    </div>
                </div>
                <div class="block-content">
                    <!-- Tickets Table -->
                    <div class="pull-r-l">
                        <table class="table table-hover table-vcenter">
                            <tbody>
                                <tr>
                                    <td class="font-w600 text-center" style="width: 80px;">#TCK0011</td>
                                    <td class="hidden-xs hidden-sm hidden-md text-center" style="width: 100px;">
                                        <span class="label label-success">Open</span>
                                    </td>
                                    <td>
                                        <a class="font-w600" data-toggle="modal" data-target="#modal-ticket" href="#">I get an error message and I can't submit the form</a>
                                        <div class="text-muted">
                                            <em>18 hours ago</em> by <a href="javascript:void(0)"><?php $one->get_name(); ?></a>
                                        </div>
                                    </td>
                                    <td class="hidden-xs hidden-sm hidden-md text-muted" style="width: 120px;">
                                        <em>Forum</em>
                                    </td>
                                    <td class="hidden-xs hidden-sm hidden-md text-center" style="width: 60px;">
                                        <span class="badge badge-primary"><i class="fa fa-comments-o"></i> 5</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-w600 text-center">#TCK0012</td>
                                    <td class="hidden-xs hidden-sm hidden-md text-center">
                                        <span class="label label-warning">Urgent</span>
                                    </td>
                                    <td>
                                        <a class="font-w600" data-toggle="modal" data-target="#modal-ticket" href="#">Thank you for a great service!</a>
                                        <div class="text-muted">
                                            <em>3 hours ago</em> by <a href="javascript:void(0)"><?php $one->get_name(); ?></a>
                                        </div>
                                    </td>
                                    <td class="hidden-xs hidden-sm hidden-md text-muted">
                                        <em>Contact Form</em>
                                    </td>
                                    <td class="hidden-xs hidden-sm hidden-md text-center">
                                        <span class="badge badge-primary"><i class="fa fa-comments-o"></i> 3</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-w600 text-center">#TCK0013</td>
                                    <td class="hidden-xs hidden-sm hidden-md text-center">
                                        <span class="label label-default">Closed</span>
                                    </td>
                                    <td>
                                        <a class="font-w600" data-toggle="modal" data-target="#modal-ticket" href="#">Payment issue with Paypal</a>
                                        <div class="text-muted">
                                            <em>1 month ago</em> by <a href="javascript:void(0)"><?php $one->get_name(); ?></a>
                                        </div>
                                    </td>
                                    <td class="hidden-xs hidden-sm hidden-md text-muted">
                                        <em>Forum</em>
                                    </td>
                                    <td class="hidden-xs hidden-sm hidden-md text-center">
                                        <span class="badge badge-primary"><i class="fa fa-comments-o"></i> 7</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-w600 text-center">#TCK0014</td>
                                    <td class="hidden-xs hidden-sm hidden-md text-center">
                                        <span class="label label-success">Open</span>
                                    </td>
                                    <td>
                                        <a class="font-w600" data-toggle="modal" data-target="#modal-ticket" href="#">Can you help me with a plugin?</a>
                                        <div class="text-muted">
                                            <em>19 hours ago</em> by <a href="javascript:void(0)"><?php $one->get_name(); ?></a>
                                        </div>
                                    </td>
                                    <td class="hidden-xs hidden-sm hidden-md text-muted">
                                        <em>Forum</em>
                                    </td>
                                    <td class="hidden-xs hidden-sm hidden-md text-center">
                                        <span class="badge badge-primary"><i class="fa fa-comments-o"></i> 4</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-w600 text-center">#TCK0015</td>
                                    <td class="hidden-xs hidden-sm hidden-md text-center">
                                        <span class="label label-default">Closed</span>
                                    </td>
                                    <td>
                                        <a class="font-w600" data-toggle="modal" data-target="#modal-ticket" href="#">Are you available for contract work?</a>
                                        <div class="text-muted">
                                            <em>15 min ago</em> by <a href="javascript:void(0)"><?php $one->get_name(); ?></a>
                                        </div>
                                    </td>
                                    <td class="hidden-xs hidden-sm hidden-md text-muted">
                                        <em>Email</em>
                                    </td>
                                    <td class="hidden-xs hidden-sm hidden-md text-center">
                                        <span class="badge badge-primary"><i class="fa fa-comments-o"></i> 2</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-w600 text-center">#TCK0016</td>
                                    <td class="hidden-xs hidden-sm hidden-md text-center">
                                        <span class="label label-default">Closed</span>
                                    </td>
                                    <td>
                                        <a class="font-w600" data-toggle="modal" data-target="#modal-ticket" href="#">How can I use the advanced features?</a>
                                        <div class="text-muted">
                                            <em>2 min ago</em> by <a href="javascript:void(0)"><?php $one->get_name(); ?></a>
                                        </div>
                                    </td>
                                    <td class="hidden-xs hidden-sm hidden-md text-muted">
                                        <em>Email</em>
                                    </td>
                                    <td class="hidden-xs hidden-sm hidden-md text-center">
                                        <span class="badge badge-primary"><i class="fa fa-comments-o"></i> 1</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-w600 text-center">#TCK0017</td>
                                    <td class="hidden-xs hidden-sm hidden-md text-center">
                                        <span class="label label-default">Closed</span>
                                    </td>
                                    <td>
                                        <a class="font-w600" data-toggle="modal" data-target="#modal-ticket" href="#">How can I create tasks using the advanced guide?</a>
                                        <div class="text-muted">
                                            <em>2 hours ago</em> by <a href="javascript:void(0)"><?php $one->get_name(); ?></a>
                                        </div>
                                    </td>
                                    <td class="hidden-xs hidden-sm hidden-md text-muted">
                                        <em>Email</em>
                                    </td>
                                    <td class="hidden-xs hidden-sm hidden-md text-center">
                                        <span class="badge badge-primary"><i class="fa fa-comments-o"></i> 8</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-w600 text-center">#TCK0018</td>
                                    <td class="hidden-xs hidden-sm hidden-md text-center">
                                        <span class="label label-default">Closed</span>
                                    </td>
                                    <td>
                                        <a class="font-w600" data-toggle="modal" data-target="#modal-ticket" href="#">Can you help me with the installation?</a>
                                        <div class="text-muted">
                                            <em>14 hours ago</em> by <a href="javascript:void(0)"><?php $one->get_name(); ?></a>
                                        </div>
                                    </td>
                                    <td class="hidden-xs hidden-sm hidden-md text-muted">
                                        <em>Email</em>
                                    </td>
                                    <td class="hidden-xs hidden-sm hidden-md text-center">
                                        <span class="badge badge-primary"><i class="fa fa-comments-o"></i> 6</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-w600 text-center">#TCK0019</td>
                                    <td class="hidden-xs hidden-sm hidden-md text-center">
                                        <span class="label label-warning">Urgent</span>
                                    </td>
                                    <td>
                                        <a class="font-w600" data-toggle="modal" data-target="#modal-ticket" href="#">I would like to learn more about the VIP plan</a>
                                        <div class="text-muted">
                                            <em>13 hours ago</em> by <a href="javascript:void(0)"><?php $one->get_name(); ?></a>
                                        </div>
                                    </td>
                                    <td class="hidden-xs hidden-sm hidden-md text-muted">
                                        <em>Contact Form</em>
                                    </td>
                                    <td class="hidden-xs hidden-sm hidden-md text-center">
                                        <span class="badge badge-primary"><i class="fa fa-comments-o"></i> 8</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-w600 text-center">#TCK0020</td>
                                    <td class="hidden-xs hidden-sm hidden-md text-center">
                                        <span class="label label-success">Open</span>
                                    </td>
                                    <td>
                                        <a class="font-w600" data-toggle="modal" data-target="#modal-ticket" href="#">What is the best way to back up?</a>
                                        <div class="text-muted">
                                            <em>15 hours ago</em> by <a href="javascript:void(0)"><?php $one->get_name(); ?></a>
                                        </div>
                                    </td>
                                    <td class="hidden-xs hidden-sm hidden-md text-muted">
                                        <em>Email</em>
                                    </td>
                                    <td class="hidden-xs hidden-sm hidden-md text-center">
                                        <span class="badge badge-primary"><i class="fa fa-comments-o"></i> 9</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- END Tickets Table -->
                </div>
            </div>
            <!-- END Tickets List -->
        </div>
    </div>
</div>
<!-- END Page Content -->

<?php require 'inc/views/base_footer.php'; ?>

<!-- Ticket Modal -->
<div class="modal fade" id="modal-ticket" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-popout">
        <div class="modal-content">
            <div class="block block-themed block-transparent remove-margin-b">
                <div class="block-header bg-primary-dark">
                    <ul class="block-options">
                        <li>
                            <span class="label label-success">Open</span>
                        </li>
                        <li>
                            <button data-dismiss="modal" type="button"><i class="si si-close"></i></button>
                        </li>
                    </ul>
                    <h3 class="block-title">#TCK0014</h3>
                </div>
                <div class="block-content block-content-full">
                    <div class="row">
                        <div class="col-sm-6">
                            <ul class="nav-users">
                                <li>
                                    <a href="base_pages_profile_v2.php">
                                        <img class="img-avatar" src="assets/img/avatars/avatar2.jpg" alt="">
                                        <i class="fa fa-circle text-success"></i> Helen Silva
                                        <div class="font-w400 text-muted"><small><i class="fa fa-user"></i> Client</small></div>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="col-sm-6">
                            <ul class="nav-users">
                                <li>
                                    <a href="base_pages_profile_v2.php">
                                        <img class="img-avatar" src="assets/img/avatars/avatar9.jpg" alt="">
                                        <i class="fa fa-circle text-success"></i> <span class="text-amethyst">Roger Hart</span>
                                        <div class="font-w400 text-muted"><small><i class="fa fa-support"></i> Support</small></div>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="block-content block-content-full block-content-mini bg-gray-light">
                    <span class="text-muted pull-right"><em>40 min ago</em></span>
                    <span class="font-w600">Payment issue with Paypal</span> by
                    <a href="javascript:void(0)">Helen Silva</a>
                </div>
                <div class="block-content">
                    <p>Hi there, I'm getting the following error when trying to buy the product. Could you please help me out?</p>
                    <p>
                        <code>Error code 589: Please contact support</code>
                    </p>
                </div>
                <div class="block-content block-content-full block-content-mini bg-gray-light">
                    <span class="text-muted pull-right"><em>10 min ago</em></span>
                    <span class="font-w600">Re: Payment issue with Paypal</span> by
                    <a class="text-amethyst" href="javascript:void(0)">Roger Hart</a>
                </div>
                <div class="block-content">
                    <p>Hi there, thanks for contacting support!</p>
                    <p>We are really sorry about the inconvenience, there was an issue with our payment getaway. It is now resolved, so could you please try again one more time?</p>
                    <p>Thank you</p>
                </div>
                <div class="block-content block-content-full block-content-mini bg-gray-light">
                    <i class="fa fa-fw fa-plus"></i> <span class="font-w600">New Reply</span>
                </div>
                <div class="block-content">
                    <form class="form-horizontal" action="base_pages_tickets.php" method="post" onsubmit="return false;">
                        <div class="form-group push-10">
                            <div class="col-xs-12">
                                <textarea class="form-control" rows="4" placeholder="Your answer.."></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12">
                                <button class="btn btn-sm btn-default" type="submit">
                                    <i class="fa fa-fw fa-reply text-success"></i> Reply
                                </button>
                                <button class="btn btn-sm btn-default" type="reset">
                                    <i class="fa fa-fw fa-repeat text-danger"></i> Reset
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="block-content block-content-full bg-gray-lighter clearfix">
                    <button class="pull-right btn btn-sm btn-rounded btn-noborder btn-primary" type="button">
                        <i class="fa fa-fw fa-check"></i> Mark as resolved
                    </button>
                    <button class="btn btn-sm btn-rounded btn-noborder btn-warning" type="button">
                        <i class="fa fa-fw fa-warning"></i> Mark as urgent
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END Ticket Modal -->

<?php require 'inc/views/template_footer_start.php'; ?>
<?php require 'inc/views/template_footer_end.php'; ?>