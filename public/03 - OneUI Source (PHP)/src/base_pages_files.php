<?php require 'inc/config.php'; ?>
<?php require 'inc/views/template_head_start.php'; ?>
<?php require 'inc/views/template_head_end.php'; ?>
<?php require 'inc/views/base_head.php'; ?>

<!-- Navigation -->
<div class="bg-gray-light border-b">
    <div class="content content-narrow">
        <!-- Custom files functionality is initialized in js/pages/base_pages_files.js -->
        <!-- Add the category value you want each link in .js-media-filter to filter out in its data-category attribute. Add the value 'all' to show all items -->
        <ul class="js-media-filter nav nav-pills push">
            <li class="active">
                <a href="javascript:void(0)" data-category="all">
                    <i class="fa fa-fw fa-folder-open-o push-5-r"></i> All
                </a>
            </li>
            <li>
                <a href="javascript:void(0)" data-category="movies">
                    <i class="fa fa-fw fa-file-movie-o push-5-r"></i> Movies
                </a>
            </li>
            <li>
                <a href="javascript:void(0)" data-category="photos">
                    <i class="fa fa-fw fa-file-photo-o push-5-r"></i> Photos
                </a>
            </li>
            <li>
                <a href="javascript:void(0)" data-category="music">
                    <i class="fa fa-fw fa-file-audio-o push-5-r"></i> Music
                </a>
            </li>
            <li>
                <a href="javascript:void(0)" data-category="books">
                    <i class="fa fa-fw fa-file-text-o push-5-r"></i> Books
                </a>
            </li>
        </ul>
    </div>
</div>
<!-- END Navigation -->

<!-- Files -->
<div class="content content-narrow">
    <!-- Add the category value for each item in its data-category attribute (for the filter functionality to work) -->
    <div class="js-media-filter-items row">
        <div class="col-sm-6 col-md-4 col-lg-3">
            <!-- Music -->
            <div class="block block-rounded animated fadeIn" data-category="music">
                <div class="block-header">
                    <ul class="block-options">
                        <li>
                            <button type="button"><i class="si si-control-play"></i></button>
                        </li>
                        <li>
                            <button type="button"><i class="si si-star"></i></button>
                        </li>
                        <li>
                            <button type="button"><i class="si si-pencil"></i></button>
                        </li>
                        <li>
                            <button type="button"><i class="si si-close"></i></button>
                        </li>
                    </ul>
                </div>
                <div class="block-content block-content-full text-center">
                    <div class="item item-2x item-circle bg-success-light text-success">
                        <i class="si si-music-tone-alt"></i>
                    </div>
                </div>
                <div class="block-content block-content-full text-center mheight-100">
                    <h3 class="h4 font-w300 text-black push-5">Intro.mp3</h3>
                    <span class="text-gray">2 min | 384 kbps</span>
                </div>
            </div>
            <!-- END Music -->
        </div>
        <div class="col-sm-6 col-md-4 col-lg-3">
            <!-- Movie -->
            <div class="block block-rounded animated fadeIn" data-category="movies">
                <div class="block-header">
                    <ul class="block-options">
                        <li>
                            <button type="button"><i class="si si-control-play"></i></button>
                        </li>
                        <li>
                            <button type="button"><i class="si si-star"></i></button>
                        </li>
                        <li>
                            <button type="button"><i class="si si-pencil"></i></button>
                        </li>
                        <li>
                            <button type="button"><i class="si si-close"></i></button>
                        </li>
                    </ul>
                </div>
                <div class="block-content block-content-full text-center">
                    <div class="item item-2x item-circle bg-danger-light text-danger">
                        <i class="si si-film"></i>
                    </div>
                </div>
                <div class="block-content block-content-full text-center mheight-100">
                    <h3 class="h4 font-w300 text-black push-5">Iron Man 3.mov</h3>
                    <span class="text-gray">124 min | 1080p</span>
                </div>
            </div>
            <!-- END Movie -->
        </div>
        <div class="col-sm-6 col-md-4 col-lg-3">
            <!-- Photo -->
            <div class="block block-rounded animated fadeIn" data-category="photos">
                <div class="block-header">
                    <ul class="block-options">
                        <li>
                            <button type="button"><i class="si si-eye"></i></button>
                        </li>
                        <li>
                            <button type="button"><i class="si si-star"></i></button>
                        </li>
                        <li>
                            <button type="button"><i class="si si-pencil"></i></button>
                        </li>
                        <li>
                            <button type="button"><i class="si si-close"></i></button>
                        </li>
                    </ul>
                </div>
                <div class="block-content block-content-full text-center">
                    <div class="item item-2x item-circle bg-info-light text-info">
                        <i class="si si-picture"></i>
                    </div>
                </div>
                <div class="block-content block-content-full text-center mheight-100">
                    <h3 class="h4 font-w300 text-black push-5">DSC00015.jpg</h3>
                    <span class="text-gray">24 mp | 6 mb</span>
                </div>
            </div>
            <!-- END Photo -->
        </div>
        <div class="col-sm-6 col-md-4 col-lg-3">
            <!-- Book -->
            <div class="block block-rounded animated fadeIn" data-category="books">
                <div class="block-header">
                    <ul class="block-options">
                        <li>
                            <button type="button"><i class="si si-eye"></i></button>
                        </li>
                        <li>
                            <button type="button"><i class="si si-star"></i></button>
                        </li>
                        <li>
                            <button type="button"><i class="si si-pencil"></i></button>
                        </li>
                        <li>
                            <button type="button"><i class="si si-close"></i></button>
                        </li>
                    </ul>
                </div>
                <div class="block-content block-content-full text-center">
                    <div class="item item-2x item-circle bg-warning-light text-warning">
                        <i class="si si-book-open"></i>
                    </div>
                </div>
                <div class="block-content block-content-full text-center mheight-100">
                    <h3 class="h4 font-w300 text-black push-5">The Martian.epub</h3>
                    <span class="text-gray">~ 7 hrs | 426 pages</span>
                </div>
            </div>
            <!-- END Book -->
        </div>
        <div class="col-sm-6 col-md-4 col-lg-3">
            <!-- Photo -->
            <div class="block block-rounded animated fadeIn" data-category="photos">
                <div class="block-header">
                    <ul class="block-options">
                        <li>
                            <button type="button"><i class="si si-eye"></i></button>
                        </li>
                        <li>
                            <button type="button"><i class="si si-star"></i></button>
                        </li>
                        <li>
                            <button type="button"><i class="si si-pencil"></i></button>
                        </li>
                        <li>
                            <button type="button"><i class="si si-close"></i></button>
                        </li>
                    </ul>
                </div>
                <div class="block-content block-content-full text-center">
                    <div class="item item-2x item-circle bg-info-light text-info">
                        <i class="si si-picture"></i>
                    </div>
                </div>
                <div class="block-content block-content-full text-center mheight-100">
                    <h3 class="h4 font-w300 text-black push-5">DSC00018.jpg</h3>
                    <span class="text-gray">12 mp | 3 mb</span>
                </div>
            </div>
            <!-- END Photo -->
        </div>
        <div class="col-sm-6 col-md-4 col-lg-3">
            <!-- Book -->
            <div class="block block-rounded animated fadeIn" data-category="books">
                <div class="block-header">
                    <ul class="block-options">
                        <li>
                            <button type="button"><i class="si si-eye"></i></button>
                        </li>
                        <li>
                            <button type="button"><i class="si si-star"></i></button>
                        </li>
                        <li>
                            <button type="button"><i class="si si-pencil"></i></button>
                        </li>
                        <li>
                            <button type="button"><i class="si si-close"></i></button>
                        </li>
                    </ul>
                </div>
                <div class="block-content block-content-full text-center">
                    <div class="item item-2x item-circle bg-warning-light text-warning">
                        <i class="si si-book-open"></i>
                    </div>
                </div>
                <div class="block-content block-content-full text-center mheight-100">
                    <h3 class="h4 font-w300 text-black push-5">Learn HTML.epub</h3>
                    <span class="text-gray">~ 4 hrs | 330 pages</span>
                </div>
            </div>
            <!-- END Book -->
        </div>
        <div class="col-sm-6 col-md-4 col-lg-3">
            <!-- Movie -->
            <div class="block block-rounded animated fadeIn" data-category="movies">
                <div class="block-header">
                    <ul class="block-options">
                        <li>
                            <button type="button"><i class="si si-control-play"></i></button>
                        </li>
                        <li>
                            <button type="button"><i class="si si-star"></i></button>
                        </li>
                        <li>
                            <button type="button"><i class="si si-pencil"></i></button>
                        </li>
                        <li>
                            <button type="button"><i class="si si-close"></i></button>
                        </li>
                    </ul>
                </div>
                <div class="block-content block-content-full text-center">
                    <div class="item item-2x item-circle bg-danger-light text-danger">
                        <i class="si si-film"></i>
                    </div>
                </div>
                <div class="block-content block-content-full text-center mheight-100">
                    <h3 class="h4 font-w300 text-black push-5">CA: Civil War.mov</h3>
                    <span class="text-gray">154 min | 1080p</span>
                </div>
            </div>
            <!-- END Movie -->
        </div>
        <div class="col-sm-6 col-md-4 col-lg-3">
            <!-- Movie -->
            <div class="block block-rounded animated fadeIn" data-category="movies">
                <div class="block-header">
                    <ul class="block-options">
                        <li>
                            <button type="button"><i class="si si-control-play"></i></button>
                        </li>
                        <li>
                            <button type="button"><i class="si si-star"></i></button>
                        </li>
                        <li>
                            <button type="button"><i class="si si-pencil"></i></button>
                        </li>
                        <li>
                            <button type="button"><i class="si si-close"></i></button>
                        </li>
                    </ul>
                </div>
                <div class="block-content block-content-full text-center">
                    <div class="item item-2x item-circle bg-danger-light text-danger">
                        <i class="si si-film"></i>
                    </div>
                </div>
                <div class="block-content block-content-full text-center mheight-100">
                    <h3 class="h4 font-w300 text-black push-5">The Hobbit.mov</h3>
                    <span class="text-gray">180 min | 1080p</span>
                </div>
            </div>
            <!-- END Movie -->
        </div>
        <div class="col-sm-6 col-md-4 col-lg-3">
            <!-- Music -->
            <div class="block block-rounded animated fadeIn" data-category="music">
                <div class="block-header">
                    <ul class="block-options">
                        <li>
                            <button type="button"><i class="si si-control-play"></i></button>
                        </li>
                        <li>
                            <button type="button"><i class="si si-star"></i></button>
                        </li>
                        <li>
                            <button type="button"><i class="si si-pencil"></i></button>
                        </li>
                        <li>
                            <button type="button"><i class="si si-close"></i></button>
                        </li>
                    </ul>
                </div>
                <div class="block-content block-content-full text-center">
                    <div class="item item-2x item-circle bg-success-light text-success">
                        <i class="si si-music-tone-alt"></i>
                    </div>
                </div>
                <div class="block-content block-content-full text-center mheight-100">
                    <h3 class="h4 font-w300 text-black push-5">We will survive.mp3</h3>
                    <span class="text-gray">5 min | 384 kbps</span>
                </div>
            </div>
            <!-- END Music -->
        </div>
        <div class="col-sm-6 col-md-4 col-lg-3">
            <!-- Photo -->
            <div class="block block-rounded animated fadeIn" data-category="photos">
                <div class="block-header">
                    <ul class="block-options">
                        <li>
                            <button type="button"><i class="si si-eye"></i></button>
                        </li>
                        <li>
                            <button type="button"><i class="si si-star"></i></button>
                        </li>
                        <li>
                            <button type="button"><i class="si si-pencil"></i></button>
                        </li>
                        <li>
                            <button type="button"><i class="si si-close"></i></button>
                        </li>
                    </ul>
                </div>
                <div class="block-content block-content-full text-center">
                    <div class="item item-2x item-circle bg-info-light text-info">
                        <i class="si si-picture"></i>
                    </div>
                </div>
                <div class="block-content block-content-full text-center mheight-100">
                    <h3 class="h4 font-w300 text-black push-5">DSC00100.jpg</h3>
                    <span class="text-gray">32 mp | 7 mb</span>
                </div>
            </div>
            <!-- END Photo -->
        </div>
        <div class="col-sm-6 col-md-4 col-lg-3">
            <!-- Music -->
            <div class="block block-rounded animated fadeIn" data-category="music">
                <div class="block-header">
                    <ul class="block-options">
                        <li>
                            <button type="button"><i class="si si-control-play"></i></button>
                        </li>
                        <li>
                            <button type="button"><i class="si si-star"></i></button>
                        </li>
                        <li>
                            <button type="button"><i class="si si-pencil"></i></button>
                        </li>
                        <li>
                            <button type="button"><i class="si si-close"></i></button>
                        </li>
                    </ul>
                </div>
                <div class="block-content block-content-full text-center">
                    <div class="item item-2x item-circle bg-success-light text-success">
                        <i class="si si-music-tone-alt"></i>
                    </div>
                </div>
                <div class="block-content block-content-full text-center mheight-100">
                    <h3 class="h4 font-w300 text-black push-5">Every day.mp3</h3>
                    <span class="text-gray">4 min | 384 kbps</span>
                </div>
            </div>
            <!-- END Music -->
        </div>
        <div class="col-sm-6 col-md-4 col-lg-3">
            <!-- Photo -->
            <div class="block block-rounded animated fadeIn" data-category="photos">
                <div class="block-header">
                    <ul class="block-options">
                        <li>
                            <button type="button"><i class="si si-eye"></i></button>
                        </li>
                        <li>
                            <button type="button"><i class="si si-star"></i></button>
                        </li>
                        <li>
                            <button type="button"><i class="si si-pencil"></i></button>
                        </li>
                        <li>
                            <button type="button"><i class="si si-close"></i></button>
                        </li>
                    </ul>
                </div>
                <div class="block-content block-content-full text-center">
                    <div class="item item-2x item-circle bg-info-light text-info">
                        <i class="si si-picture"></i>
                    </div>
                </div>
                <div class="block-content block-content-full text-center mheight-100">
                    <h3 class="h4 font-w300 text-black push-5">DSC00025.jpg</h3>
                    <span class="text-gray">24 mp | 5 mb</span>
                </div>
            </div>
            <!-- END Photo -->
        </div>
        <div class="col-sm-6 col-md-4 col-lg-3">
            <!-- Book -->
            <div class="block block-rounded animated fadeIn" data-category="books">
                <div class="block-header">
                    <ul class="block-options">
                        <li>
                            <button type="button"><i class="si si-eye"></i></button>
                        </li>
                        <li>
                            <button type="button"><i class="si si-star"></i></button>
                        </li>
                        <li>
                            <button type="button"><i class="si si-pencil"></i></button>
                        </li>
                        <li>
                            <button type="button"><i class="si si-close"></i></button>
                        </li>
                    </ul>
                </div>
                <div class="block-content block-content-full text-center">
                    <div class="item item-2x item-circle bg-warning-light text-warning">
                        <i class="si si-book-open"></i>
                    </div>
                </div>
                <div class="block-content block-content-full text-center mheight-100">
                    <h3 class="h4 font-w300 text-black push-5">Start a business.epub</h3>
                    <span class="text-gray">~ 10 hrs | 590 pages</span>
                </div>
            </div>
            <!-- END Book -->
        </div>
        <div class="col-sm-6 col-md-4 col-lg-3">
            <!-- Book -->
            <div class="block block-rounded animated fadeIn" data-category="books">
                <div class="block-header">
                    <ul class="block-options">
                        <li>
                            <button type="button"><i class="si si-eye"></i></button>
                        </li>
                        <li>
                            <button type="button"><i class="si si-star"></i></button>
                        </li>
                        <li>
                            <button type="button"><i class="si si-pencil"></i></button>
                        </li>
                        <li>
                            <button type="button"><i class="si si-close"></i></button>
                        </li>
                    </ul>
                </div>
                <div class="block-content block-content-full text-center">
                    <div class="item item-2x item-circle bg-warning-light text-warning">
                        <i class="si si-book-open"></i>
                    </div>
                </div>
                <div class="block-content block-content-full text-center mheight-100">
                    <h3 class="h4 font-w300 text-black push-5">Marketing 101.epub</h3>
                    <span class="text-gray">~ 11 hrs | 630 pages</span>
                </div>
            </div>
            <!-- END Book -->
        </div>
        <div class="col-sm-6 col-md-4 col-lg-3">
            <!-- Photo -->
            <div class="block block-rounded animated fadeIn" data-category="photos">
                <div class="block-header">
                    <ul class="block-options">
                        <li>
                            <button type="button"><i class="si si-eye"></i></button>
                        </li>
                        <li>
                            <button type="button"><i class="si si-star"></i></button>
                        </li>
                        <li>
                            <button type="button"><i class="si si-pencil"></i></button>
                        </li>
                        <li>
                            <button type="button"><i class="si si-close"></i></button>
                        </li>
                    </ul>
                </div>
                <div class="block-content block-content-full text-center">
                    <div class="item item-2x item-circle bg-info-light text-info">
                        <i class="si si-picture"></i>
                    </div>
                </div>
                <div class="block-content block-content-full text-center mheight-100">
                    <h3 class="h4 font-w300 text-black push-5">DSC00039.jpg</h3>
                    <span class="text-gray">24 mp | 6 mb</span>
                </div>
            </div>
            <!-- END Photo -->
        </div>
        <div class="col-sm-6 col-md-4 col-lg-3">
            <!-- Book -->
            <div class="block block-rounded animated fadeIn" data-category="books">
                <div class="block-header">
                    <ul class="block-options">
                        <li>
                            <button type="button"><i class="si si-eye"></i></button>
                        </li>
                        <li>
                            <button type="button"><i class="si si-star"></i></button>
                        </li>
                        <li>
                            <button type="button"><i class="si si-pencil"></i></button>
                        </li>
                        <li>
                            <button type="button"><i class="si si-close"></i></button>
                        </li>
                    </ul>
                </div>
                <div class="block-content block-content-full text-center">
                    <div class="item item-2x item-circle bg-warning-light text-warning">
                        <i class="si si-book-open"></i>
                    </div>
                </div>
                <div class="block-content block-content-full text-center mheight-100">
                    <h3 class="h4 font-w300 text-black push-5">Web Dev 2016.pdf</h3>
                    <span class="text-gray">~ 12 hrs | 750 pages</span>
                </div>
            </div>
            <!-- END Book -->
        </div>
    </div>
</div>
<!-- END Files -->

<?php require 'inc/views/base_footer.php'; ?>
<?php require 'inc/views/template_footer_start.php'; ?>

<!-- Page JS Code -->
<script src="<?php echo $one->assets_folder; ?>/js/pages/base_pages_files.js"></script>

<?php require 'inc/views/template_footer_end.php'; ?>