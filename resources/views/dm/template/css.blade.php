<link rel="stylesheet" href="{{ asset('assets/js/plugins/slick/slick.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/js/plugins/slick/slick-theme.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/js/plugins/select2/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/js/plugins/jquery-auto-complete/jquery.auto-complete.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/js/plugins/ion-rangeslider/css/ion.rangeSlider.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/js/plugins/ion-rangeslider/css/ion.rangeSlider.skinHTML5.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/js/plugins/dropzonejs/dropzone.min.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/emojione/2.2.7/assets/css/emojione.min.css"/>
<link rel="stylesheet" href="{{ asset('assets/js/plugins/sweetalert2/sweetalert2.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/js/plugins/bootstrap-datepicker/bootstrap-datepicker3.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css') }}">

<style>
    /* AutoComplete styles for Emoji One */
    .dropdown-menu {
        list-style: none;
        padding: .3em 0 0;
        margin: 0;
        border: 1px solid #6E6E6E;
        background-color: white;
        border-radius: 5px;
        overflow: hidden;
        font-size: inherit;
        letter-spacing: .025em;
        box-shadow: 3px 3px 3px rgba(0,0,0,.2);
    }
    .dropdown-menu a:hover {
        cursor: pointer;
    }
    .dropdown-menu li {
        letter-spacing: 0;
        display: block;
        float: none;
        margin: 0;
        padding: 0;
        border:none;
    }
    .dropdown-menu li:before {
        display: none;
    }
    .dropdown-menu .textcomplete-footer {
        margin-top: .3em;
        background: #e6e6e6;
    }
    .dropdown-menu .textcomplete-footer a {
        color: #999999;
        text-decoration: none;
        text-transform: uppercase;
        letter-spacing: .05em;
        line-height: 2.1818em;
        padding-left: 1.8181em;
        font-size: .84em;
    }
    .dropdown-menu .textcomplete-footer .arrow {
        margin-left: .8em;
        font-size: 1.3em;
    }
    .dropdown-menu li .emojione {
        vertical-align: middle;
        font-size: 1.23em;
        width: 1em;
        height: 1em;
        top: -1px;
        margin: 0 .3em 0 0;
    }
    .dropdown-menu li a {
        display: block;
        height: 100%;
        line-height: 1.8em;
        padding: 0 1.54em 0 .615em;
        color: #4f4f4f;
    }
    .dropdown-menu .active,
    .dropdown-menu li:hover {
        background: #6E6E6E;
        color: white;
    }
    .dropdown-menu .active a,
    .dropdown-menu li:hover a {
        color: inherit;
    }
</style>