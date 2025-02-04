<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- favicon -->

    <link rel="shortcut icon" type="image/png" href="{{ static_asset(settingHelper('favicon')) }}">

    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="theme-color" content="#ffffff">
    <!-- end favicon -->
    <title>{{ settingHelper('application_name') }}</title>
    <link href="{{ static_asset('vendor') }}/fonts/circular-std/style.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ static_asset('vendor') }}/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ static_asset('vendor') }}/fonts/fontawesome/css/fontawesome-all.css">
    <link rel="stylesheet" href="{{ static_asset('vendor') }}/fonts/material-design-iconic-font/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="{{ static_asset('vendor') }}/fonts/flag-icon-css/flag-icon.min.css">
    <link rel="stylesheet" href="{{ static_asset('css') }}/style.css">
    <link rel="stylesheet" href="{{ static_asset('css') }}/custom.css">
    <link rel="stylesheet" href="{{ static_asset('css') }}/custom2.css">
    <link rel="stylesheet" href="{{ static_asset('vendor') }}/datepicker/tempusdominus-bootstrap-4.css" />
    {{-- select teg --}}
    <link rel="stylesheet" href="{{ static_asset('css') }}/bootstrap-tagsinput.css">
    <link rel="stylesheet" href="{{ static_asset('css/tagsinput.css') }}">

    <link rel="stylesheet" href="{{ static_asset('css/flatpickr.min.css') }}">

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="{{ static_asset('css/bootstrap-select.min.css') }}">

    <link rel="stylesheet" href="{{ static_asset('site/css/font-awesome.min.css') }}">

    <!-- tinymce Css-->
    <link href="{{ static_asset('vendor') }}/tinymce/skins/lightgray/skin.min.css" rel="stylesheet" />

    <!-- select2 -->
    <link href="{{ static_asset('Select2/css/select2.css') }}" rel="stylesheet" />

    <script src="{{ static_asset('js/prebid-ads.js') }}"></script>

    {{-- majid css --}}
    <link rel="stylesheet" href="{{ static_asset('css/majid.css') }}">
    {{-- majid admin css --}}
    <link rel="stylesheet" href="{{ static_asset('css/majid_admin.css') }}">

    @yield('style')

</head>

<body>
    <!-- ============================================================== -->
    <!-- main wrapper -->
    <!-- ============================================================== -->
    <div class="dashboard-main-wrapper">
        @include('common::layouts.header')
        @include('common::layouts.left-sidebar')

        <!-- ============================================================== -->
        <!-- wrapper  -->
        <!-- ============================================================== -->
        <div class="dashboard-wrapper">
            @yield('content')
        </div>
        <!-- ============================================================== -->
        <!-- end wrapper  -->
        <!-- ============================================================== -->

    </div>
    <!-- ============================================================== -->
    <!-- end main wrapper  -->
    <!-- ============================================================== -->
    <!-- Optional JavaScript -->


    <!-- jquery 3.3.1 -->
    <script src="{{ static_asset('vendor') }}/jquery/jquery-3.3.1.min.js"></script>

    <!-- bootstap bundle js -->
    <script src="{{ static_asset('vendor') }}/bootstrap/js/bootstrap.bundle.js"></script>

    <!-- Tinemce -->
    <script src="{{ static_asset('vendor/tinymce/tinymce.min.js') }}"></script>
    <script>
        endOfMessage = 1;
        //TinyMCE
        tinymce.init({
            selector: "textarea#post_content",
            theme: "modern",
            height: 400,
            plugins: [
                'advlist autolink lists link image charmap print preview hr anchor pagebreak',
                'searchreplace wordcount visualblocks visualchars code fullscreen',
                'insertdatetime media nonbreaking save table contextmenu directionality',
                'emoticons template paste textcolor colorpicker textpattern imagetools lineheight',
            ],
            toolbar1: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link',
            toolbar2: 'print preview media | forecolor backcolor emoticons | paste | lineheightselect | image | editimage',
            image_advtab: true,
            content_css: "{{ static_asset('css/editor.css') }}",
            statusbar: false,
            paste_data_images: true,
            image_title: true,
            automatic_uploads: true,
            file_picker_types: 'image',
            convert_urls: false,
            lineheight_formats: "0.25pt 0.5pt 0.75pt 1pt 1.5pt 2pt 4pt 6pt 8pt 10pt 12pt 14pt 16pt 18pt 20pt 22pt 24pt 26pt 36pt",

            setup: function(ed) {
                ed.on('blur', function() {
                    let content = tinyMCE.activeEditor.getContent();
                    content = content.replace(/\اخبار ساخته ها/g, '<a href="https://sakhteha.news" class="main-link">اخبار ساخته ها</a>');
                    if (endOfMessage == 1) {
                        content = content + '<p>انتهای پیام/</p>';
                        endOfMessage = 0;
                    }
                    tinyMCE.activeEditor.setContent(content);
                });
            }
        });



        editor_image = false;

        $("#editor-image-btn").click(function(e) {
            e.preventDefault();
            editor_image = true;
            $('.image-modal-lg').modal('show');
        });

        tinymce.init({
            selector: "textarea#content",
            theme: "modern",
            height: 400,
            plugins: [
                'advlist autolink lists link image charmap print preview hr anchor pagebreak',
                'searchreplace wordcount visualblocks visualchars code fullscreen',
                'insertdatetime media nonbreaking save table contextmenu directionality',
                'emoticons template paste textcolor colorpicker textpattern imagetools lineheight',
            ],
            toolbar1: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
            toolbar2: 'print preview media | forecolor backcolor emoticons | paste | editimage | lineheightselect',
            image_advtab: true,
            content_css: "{{ static_asset('css/editor.css') }}",
            statusbar: false,
            paste_data_images: true,
            image_title: true,
            automatic_uploads: true,
            file_picker_types: 'image',
            convert_urls: false,
            // content_style: "body { line-height: 30px !important; }",
            // lineheight_formats: "1px 5px",
            lineheight_formats: "0.25pt 0.5pt 0.75pt 1pt 1.5pt 2pt 4pt 6pt 8pt 10pt 12pt 14pt 16pt 18pt 20pt 22pt 24pt 26pt 36pt",
        });
        // tinymce.suffix = ".min";
        // tinyMCE.baseURL = 'vendor/tinymce';
    </script>

    <!-- slimscroll js -->
    <script src="{{ static_asset('vendor') }}/slimscroll/jquery.slimscroll.js"></script>

    <!-- main js -->
    <script src="{{ static_asset('js') }}/main-js.js"></script>
    <script src="{{ static_asset('js') }}/drag-n-drop-js.js"></script>

    <!-- notify -->
    <script src="{{ static_asset('js/notify.min.js') }}"></script>

    <script src="{{ static_asset('vendor') }}/datepicker/moment.js"></script>
    <script src="{{ static_asset('vendor') }}/datepicker/tempusdominus-bootstrap-4.js"></script>
    <script src="{{ static_asset('vendor') }}/datepicker/datepicker.js"></script>
    <script type="text/javascript" src="{{ static_asset('js/custom.js') }}"></script>


    <script src="{{ static_asset('js/flatpickr.js') }}"></script>
    <script>
        const fp = flatpickr(".date", {
            enableTime: true,
            dateFormat: "F j, Y h:i K",
            minDate: "today",
            weekNumbers: true,
            minTime: "now",
        });
    </script>

    <!-- SwAl -->

    <script src="{{ static_asset('js/sweetalert.min.js') }}"></script>

    <script type="text/javascript">
        function delete_item(table_name, row_id) {
            var table_row = '#row_' + row_id

            if (table_name == 'quiz_questions') {
                var table_row = '#panel_quiz_question_' + row_id
            }

            if (table_name == 'quiz_answers') {
                var table_row = '#quiz_answer_' + row_id
            }

            if (table_name == 'quiz_results') {
                var table_row = '#panel_quiz_result_' + row_id
            }
            var token = "{{ csrf_token() }}";
            url = "{{ route('delete') }}";

            swal({
                    title: "{{ __('are_you_sure?') }}",
                    text: "{{ __('it_will_be_deleted_permanently') }}",
                    icon: "warning",
                    buttons: true,
                    buttons: ["{{ __('cancel') }}", "{{ __('delete') }}"],
                    dangerMode: true,
                    closeOnClickOutside: false
                })
                .then(function(confirmed) {
                    if (confirmed) {
                        $.ajax({
                                url: url,
                                type: 'post',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                data: 'row_id=' + row_id + '&table_name=' + table_name + '&_token=' + token + '&_method=DELETE',
                                dataType: 'json'
                            })
                            .done(function(response) {
                                // console.log(response);
                                swal.stopLoading();
                                if (response.status == "success") {
                                    swal("{{ __('deleted') }}!", response.message, response.status);
                                    $(table_row).fadeOut(2000).remove();
                                    if (table_name == 'menu') {
                                        window.location = response.url
                                    }

                                } else {
                                    swal("خطا", response.message, response.status);
                                }
                            })
                            .fail(function() {
                                swal('خطا', '{{ __('something_went_wrong_with_ajax') }}', 'error');
                            })
                    }
                })
        }
    </script>

    <script type="text/javascript">
        function remove_post_form(page, feature, row_id) {
            var table_row = '#row_' + row_id
            var token = "{{ csrf_token() }}";
            url = "{{ route('remove-post-form') }}"
            swal({
                    title: "{{ __('are_you_sure?') }}",
                    // text: "{{ __() }}",
                    icon: "warning",
                    buttons: true,
                    buttons: ["{{ __('no') }}", "{{ __('yes') }}"],
                    dangerMode: true,
                    closeOnClickOutside: false
                })
                .then(function(confirmed) {
                    if (confirmed) {
                        $.ajax({
                                url: url,
                                type: 'post',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                data: 'post_id=' + row_id + '&feature=' + feature + '&_token=' + token + '&_method=DELETE',
                                dataType: 'json'
                            })
                            .done(function(response) {
                                // console.log(response);

                                swal.stopLoading();
                                if (response.status == "success") {
                                    // console.log(response);
                                    swal("{{ __('removed') }}!", response.message, response.status);
                                    if (page == 'index') {
                                        window.location.reload();
                                    } else {
                                        $(table_row).fadeOut(2000);
                                    }

                                } else {
                                    swal("خطا", response.message, response.status);
                                }
                            })
                            .fail(function() {
                                swal('خطا', '{{ __('something_went_wrong_with_ajax') }}', 'error');
                            })
                    }
                })
        }
    </script>

    <script type="text/javascript">
        function add_post_to(feature, row_id) {
            var table_row = '#row_' + row_id
            var token = "{{ csrf_token() }}";
            url = "{{ route('add-to') }}"

            swal({
                    title: "{{ __('are_you_sure?') }}",
                    // text: "{{ __('it_will_be_added_to_this_feature') }}",
                    icon: "info",
                    buttons: true,
                    buttons: ["{{ __('no') }}", "{{ __('yes') }}"],
                    dangerMode: false,
                    closeOnClickOutside: false
                })
                .then(function(confirmed) {
                    if (confirmed) {
                        $.ajax({
                                url: url,
                                type: 'post',
                                data: 'post_id=' + row_id + '&feature=' + feature + '&_token=' + token,
                                dataType: 'json'
                            })
                            .done(function(response) {
                                swal.stopLoading();
                                if (response.status == "success") {
                                    // console.log(response);
                                    swal("{{ __('added') }}!", response.message, response.status);
                                    window.location.reload();
                                } else {
                                    swal("خطا", response.message, response.status);
                                }
                            })
                            .fail(function() {
                                swal('خطا', '{{ __('something_went_wrong_with_ajax') }}', 'error');
                            })
                    }
                })
        }
    </script>


    <!-- ajax modal  -->
    <div id="common-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content p-0 b-0">
                <div class="panel panel-color panel-primary">
                    <div class="modal-header">
                        <h5 class="modal-title" id="common-modal-title">عنوان</h5>
                        <button type="button" class="close ml-0 mr-auto" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="modal-loader"> <img src="{{ static_asset('/preloader.gif') }}" /> </div>
                        <!-- content will be load here -->
                        <div id="dynamic-content"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.modal -->
    <script src="{{ static_asset('js/ajax-modal.js') }}"></script>
    <!-- END Ajax modal  -->
    @yield('modal')


    <!-- Latest compiled and minified JavaScript -->
    <script src="{{ static_asset('js/bootstrap-select.min.js') }}"></script>
    <script src="{{ static_asset('vendor/parsley/parsley.js') }}"></script>

    <script>
        $('form').parsley();
    </script>

    <script src="{{ static_asset('js/collapse.js') }}"></script>
    <script src="{{ static_asset('Select2/js/select2.js') }}"></script>

    {{-- for select file in editor added by majid molaea --}}
    <script>
        $(".url").click(function(e) {
            var url = $(this).data('url');
            node = $(this);

            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val(url).select();
            document.execCommand("copy");
            $temp.remove();
            $(this).html('کپی شد');
            $(this).removeClass('btn-info');
            $(this).addClass('btn-success');
            $(this).css("cursor", "auto");

            setTimeout(function() {
                node.html('کپی لینک');
                node.removeClass('btn-success');
                node.addClass('btn-info');
                node.css("cursor", "pointer");
            }, 3000);
        });
    </script>

    @yield('script')

    @stack('include_js')
</body>

</html>
