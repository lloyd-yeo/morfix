<?php require 'inc/config.php'; ?>
<?php require 'inc/views/template_head_start.php'; ?>
<?php require 'inc/views/template_head_end.php'; ?>
<?php require 'inc/views/base_head.php'; ?>

<!-- Page Header -->
<div class="content bg-gray-lighter">
    <div class="row items-push">
        <div class="col-sm-7">
            <h1 class="page-heading">
                Contacts <small>All people in the right place!</small>
            </h1>
        </div>
        <div class="col-sm-5 text-right hidden-xs">
            <ol class="breadcrumb push-10-t">
                <li>Generic</li>
                <li><a class="link-effect" href="">Contacts</a></li>
            </ol>
        </div>
    </div>
</div>
<!-- END Page Header -->

<!-- Page Content -->
<div class="content">
    <div class="row">
        <?php
            $gender     = array('male', 'female');
            $category   = array('Friends', 'Work', 'Family');
            $work_title = array('Web Designer', 'Web Developer', 'Photographer', 'Author', 'Graphic Designer');

            for ($i = 1; $i < 17; $i++) {
                $gender_random = $gender[rand(0,1)];
        ?>
        <div class="col-sm-6 col-md-4 col-lg-3">
            <!-- Contact -->
            <div class="block block-rounded">
                <div class="block-header">
                    <ul class="block-options">
                        <li>
                            <button type="button" data-toggle="modal" data-target="#modal-contact-edit">
                                <i class="si si-pencil"></i>
                            </button>
                        </li>
                    </ul>
                    <div class="block-title"><?php $one->get_name($gender_random); ?></div>
                </div>
                <div class="block-content block-content-full bg-primary text-center">
                    <?php $one->get_avatar(0, $gender_random, 64, 'img-avatar'); ?>
                    <div class="font-s13 push-10-t"><?php echo $work_title[rand(0,4)]; ?></div>
                </div>
                <div class="block-content">
                    <div class="text-center push">
                        <a class="text-default" href="javascript:void(0)">
                            <i class="fa fa-2x fa-fw fa-facebook-square"></i>
                        </a>
                        <a class="text-info" href="javascript:void(0)">
                            <i class="fa fa-2x fa-fw fa-twitter-square"></i>
                        </a>
                        <a class="text-danger" href="javascript:void(0)">
                            <i class="fa fa-2x fa-fw fa-youtube-square"></i>
                        </a>
                    </div>
                    <table class="table table-borderless table-striped font-s13">
                        <tbody>
                            <tr>
                                <td class="font-w600" style="width: 30%;">Category</td>
                                <td><?php echo $category[rand(0,2)]; ?></td>
                            </tr>
                            <tr>
                                <td class="font-w600">Phone</td>
                                <td>+ 00 <?php echo rand(10000000, 99999999); ?></td>
                            </tr>
                            <tr>
                                <td class="font-w600">Email</td>
                                <td>user<?php echo $i; ?>@one.ui</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- END Contact -->
        </div>
        <?php } ?>
    </div>
</div>
<!-- END Page Content -->

<?php require 'inc/views/base_footer.php'; ?>

<!-- Contact Edit Modal -->
<div class="modal fade" id="modal-contact-edit" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-popout">
        <div class="modal-content">
            <div class="block block-themed block-transparent remove-margin-b">
                <div class="block-header bg-primary-dark">
                    <ul class="block-options">
                        <li>
                            <button data-dismiss="modal" type="button"><i class="si si-close"></i></button>
                        </li>
                    </ul>
                    <h3 class="block-title"><i class="fa fa-user-circle push-5-r"></i> Edit Contact</h3>
                </div>
                <div class="block-content">
                    <form class="form-horizontal push-10-t push-10" action="base_pages_contacts.php" method="post" onsubmit="return false;">
                        <div class="form-group">
                            <div class="col-sm-8 col-sm-offset-2">
                                <div class="push">
                                    <?php $one->get_avatar(15); ?>
                                </div>
                                <label for="contact-avatar">Select new avatar</label>
                                <input type="file" id="contact-avatar" name="contact-avatar">
                            </div>
                        </div>
                        <div class="form-group push-50-t">
                            <div class="col-sm-8 col-sm-offset-2">
                                <div class="form-material form-material-primary floating input-group">
                                    <input class="form-control" type="text" id="contact-name" name="contact-name" value="John Doe">
                                    <label for="contact-name">Name</label>
                                    <span class="input-group-addon"><i class="si si-user"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-8 col-sm-offset-2">
                                <div class="form-material form-material-primary floating input-group">
                                    <input class="form-control" type="email" id="contact-email" name="contact-email" value="user1@one.ui">
                                    <label for="contact-email">Email</label>
                                    <span class="input-group-addon"><i class="si si-envelope-open"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-8 col-sm-offset-2">
                                <div class="form-material form-material-primary floating input-group">
                                    <input class="form-control" type="text" id="contact-phone" name="contact-phone" value="+ 00 35874521">
                                    <label for="contact-phone">Phone</label>
                                    <span class="input-group-addon"><i class="si si-screen-smartphone"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group push-50-t">
                            <div class="col-sm-8 col-sm-offset-2">
                                <div class="form-material form-material-primary floating input-group">
                                    <input class="form-control" type="text" id="contact-facebook" name="contact-facebook" value="https://facebook.com/user.one.ui">
                                    <label for="contact-facebook">Facebook</label>
                                    <span class="input-group-addon"><i class="si si-social-facebook"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-8 col-sm-offset-2">
                                <div class="form-material form-material-primary floating input-group">
                                    <input class="form-control" type="text" id="contact-twitter" name="contact-twitter" value="https://twitter.com/user.one.ui">
                                    <label for="contact-twitter">Twitter</label>
                                    <span class="input-group-addon"><i class="si si-social-twitter"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-8 col-sm-offset-2">
                                <div class="form-material form-material-primary floating input-group">
                                    <input class="form-control" type="text" id="contact-youtube" name="contact-youtube" value="https://youtube.com/user.one.ui">
                                    <label for="contact-youtube">Youtube</label>
                                    <span class="input-group-addon"><i class="si si-social-youtube"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group push-50-t">
                            <div class="col-sm-8 col-sm-offset-2">
                                <div class="form-material form-material-primary floating">
                                    <select class="form-control" id="contact-work-title" name="contact-work-title" size="1">
                                        <option value="1" selected>Web Designer</option>
                                        <option value="2">Web Developer</option>
                                        <option value="3">Photographer</option>
                                        <option value="4">Author</option>
                                        <option value="5">Graphic Designer</option>
                                    </select>
                                    <label for="contact-work-title">Work Title</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-8 col-sm-offset-2">
                                <div class="form-material form-material-primary floating">
                                    <select class="form-control" id="contact-category" name="contact-category" size="1">
                                        <option value="1" selected>Friends</option>
                                        <option value="2">Work</option>
                                        <option value="3">Family</option>
                                    </select>
                                    <label for="contact-category">Category</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-8 col-sm-offset-2">
                                <button class="btn btn-sm btn-primary" type="submit"><i class="fa fa-check push-5-r"></i> Update Contact</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END Contact Edit Modal -->

<?php require 'inc/views/template_footer_start.php'; ?>
<?php require 'inc/views/template_footer_end.php'; ?>