<!-- Page JS Plugins -->
<script src="{{ asset('assets/js/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/sweetalert2/sweetalert2.min.js') }}"></script>

<script>
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$paypal_email = "";
$keyword = "";

$(".btn-save-paypal-email").on("click", function(){ 
    var url = "affiliate/save/paypal/"; // the script where you handle the form input.
    var profile_id = $("#paypal-email").attr("data-id");
    url = url + profile_id;
    $paypal_email = $("#paypal-email").val();
    savePaypalEmail(url);
});

$(".add-pixel-btn").on("click", function(){ 
    
    $keyword = $(this).attr('data-keyword');
    
    swal({
        title: 'Enter your Facebook Pixel Below',
        input: 'textarea',
        showCancelButton: true,
        confirmButtonText: 'Submit',
        showLoaderOnConfirm: true,
        preConfirm: function (pixelCode) {
          return new Promise(function (resolve, reject) {
            setTimeout(function() {
                
              $.ajax({
                    type: "POST",
                    url: 'affiliate/save/pixel',
                    dataType: "json",
                    data: {
                        keyword: $keyword,
                        pixel: pixelCode
                    },
                    success: function (data) {
                        if (data.success === true) {
                            resolve();
                        } else {
                            reject('There has been an issue with saving your pixel code, please try again later.');
                        }
                    }
              });
              
            }, 2000);
          });
        },
        allowOutsideClick: false
      }).then(function (pixelCode) {
        swal({
          type: 'success',
          title: 'Pixel Code Submitted!',
          html: 'Your Pixel has been submitted, we will put it under review & have it approved in 48 hours.'
        });
      });
});

function savePaypalEmail(url) {
    $.ajax({
        type: "POST",
        url: url,
        dataType: "json",
        data: {
            paypal_email: $paypal_email
        },
        success: function (data) {
            if (data.success === true) {
                swal('Success', data.message, 'success');
            } else {
                swal('Oops...', data.message, 'error');
            }
        }
    });
};

var BaseTableDatatables = function() {
    
    // Init full extra DataTable, for more examples you can check out https://www.datatables.net/
    var initDataTableFullPagination = function() {
        jQuery('.js-dataTable-full-pagination').dataTable({
            pagingType: "full_numbers",
            pageLength: 5,
            lengthMenu: [[5, 10, 15, 20], [5, 10, 15, 20]]
        });
    };

    // DataTables Bootstrap integration
    var bsDataTables = function() {
        var $DataTable = jQuery.fn.dataTable;

        // Set the defaults for DataTables init
        jQuery.extend( true, $DataTable.defaults, {
            dom:
                "<'row'<'col-sm-6'l><'col-sm-6'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-6'i><'col-sm-6'p>>",
            renderer: 'bootstrap',
            oLanguage: {
                sLengthMenu: "_MENU_",
                sInfo: "Showing <strong>_START_</strong>-<strong>_END_</strong> of <strong>_TOTAL_</strong>",
                oPaginate: {
                    sPrevious: '<i class="fa fa-angle-left"></i>',
                    sNext: '<i class="fa fa-angle-right"></i>'
                }
            }
        });

        // Default class modification
        jQuery.extend($DataTable.ext.classes, {
            sWrapper: "dataTables_wrapper form-inline dt-bootstrap",
            sFilterInput: "form-control",
            sLengthSelect: "form-control"
        });

        // Bootstrap paging button renderer
        $DataTable.ext.renderer.pageButton.bootstrap = function (settings, host, idx, buttons, page, pages) {
            var api     = new $DataTable.Api(settings);
            var classes = settings.oClasses;
            var lang    = settings.oLanguage.oPaginate;
            var btnDisplay, btnClass;

            var attach = function (container, buttons) {
                var i, ien, node, button;
                var clickHandler = function (e) {
                    e.preventDefault();
                    if (!jQuery(e.currentTarget).hasClass('disabled')) {
                        api.page(e.data.action).draw(false);
                    }
                };

                for (i = 0, ien = buttons.length; i < ien; i++) {
                    button = buttons[i];

                    if (jQuery.isArray(button)) {
                        attach(container, button);
                    }
                    else {
                        btnDisplay = '';
                        btnClass = '';

                        switch (button) {
                            case 'ellipsis':
                                btnDisplay = '&hellip;';
                                btnClass = 'disabled';
                                break;

                            case 'first':
                                btnDisplay = lang.sFirst;
                                btnClass = button + (page > 0 ? '' : ' disabled');
                                break;

                            case 'previous':
                                btnDisplay = lang.sPrevious;
                                btnClass = button + (page > 0 ? '' : ' disabled');
                                break;

                            case 'next':
                                btnDisplay = lang.sNext;
                                btnClass = button + (page < pages - 1 ? '' : ' disabled');
                                break;

                            case 'last':
                                btnDisplay = lang.sLast;
                                btnClass = button + (page < pages - 1 ? '' : ' disabled');
                                break;

                            default:
                                btnDisplay = button + 1;
                                btnClass = page === button ?
                                        'active' : '';
                                break;
                        }

                        if (btnDisplay) {
                            node = jQuery('<li>', {
                                'class': classes.sPageButton + ' ' + btnClass,
                                'aria-controls': settings.sTableId,
                                'tabindex': settings.iTabIndex,
                                'id': idx === 0 && typeof button === 'string' ?
                                        settings.sTableId + '_' + button :
                                        null
                            })
                            .append(jQuery('<a>', {
                                    'href': '#'
                                })
                                .html(btnDisplay)
                            )
                            .appendTo(container);

                            settings.oApi._fnBindAction(
                                node, {action: button}, clickHandler
                            );
                        }
                    }
                }
            };

            attach(
                jQuery(host).empty().html('<ul class="pagination"/>').children('ul'),
                buttons
            );
        };

        // TableTools Bootstrap compatibility - Required TableTools 2.1+
        if ($DataTable.TableTools) {
            // Set the classes that TableTools uses to something suitable for Bootstrap
            jQuery.extend(true, $DataTable.TableTools.classes, {
                "container": "DTTT btn-group",
                "buttons": {
                    "normal": "btn btn-default",
                    "disabled": "disabled"
                },
                "collection": {
                    "container": "DTTT_dropdown dropdown-menu",
                    "buttons": {
                        "normal": "",
                        "disabled": "disabled"
                    }
                },
                "print": {
                    "info": "DTTT_print_info"
                },
                "select": {
                    "row": "active"
                }
            });

            // Have the collection use a bootstrap compatible drop down
            jQuery.extend(true, $DataTable.TableTools.DEFAULTS.oTags, {
                "collection": {
                    "container": "ul",
                    "button": "li",
                    "liner": "a"
                }
            });
        }
    };

    return {
        init: function() {
            // Init Datatables
            bsDataTables();
            initDataTableFullPagination();
        }
    };
}();

jQuery(function () {
    // Init page helpers (Appear, CountTo)
    App.initHelpers(['appear', 'appear-countTo']);
    BaseTableDatatables.init();
});
</script>

