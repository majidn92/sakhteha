@extends('common::layouts.master')
@section('rss')
    active
@endsection

@section('content')
    <div class="dashboard-ecommerce">
        <div class="container-fluid dashboard-content ">
            <!-- page info start-->
            {!! Form::open(['route' => ['save-rss-feed'], 'method' => 'post', 'id' => 'create-rss-feed']) !!}
            <input type="hidden" name="category_ids">
            <input type="hidden" name="sub_category_ids">

            <div class="row clearfix">
                <div class="col-12">
                    <div class="add-new-header clearfix m-b-20">
                        <div class="row">
                            <div class="col-6">
                                <div class="block-header">
                                    <h2>افزودن RSS</h2>
                                </div>
                            </div>
                            <div class="col-6 text-left">
                                <a href="{{ route('rss-feeds') }}" class="btn btn-primary btn-add-new"><i class="fas fa-list"></i> RSS
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            @if (session('error'))
                                <div id="error_m" class="alert alert-danger">
                                    {{ session('error') }}
                                </div>
                            @endif
                            @if (session('success'))
                                <div id="success_m" class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>

                        <!-- Main Content section start -->
                        <div class="col-12">
                            <div class="add-new-page  bg-white p-20 m-b-20">
                                <div class="block-header">
                                    <h2>جزئیات RSS</h2>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="feed_name" class="col-form-label">نام RSS *</label>
                                        <input id="feed_name" name="name" value="{{ old('name') }}" type="text" class="form-control">
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="feed_url" class="col-form-label">لینک RSS *</label>
                                        <input id="feed_url" name="feed_url" placeholder="{{ __('feed_url') }}" value="{{ old('feed_url') }}" type="text" class="form-control">
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <label for="post_content" class="col-form-label">{{ __('number_of_post_to_import') }} *</label>
                                    <input type="number" class="form-control max-100" name="post_limit" placeholder="{{ __('number_of_post_to_import') }}" value="" min="1" max="100">
                                </div>
                            </div>
                            <!-- options section start -->
                            <div class="add-new-page  bg-white p-20 m-b-20">
                                <div class="block-header">
                                    <h2>{{ __('options') }}</h2>
                                </div>
                                <div class="row p-l-15">
                                    <div class="col-12 col-md-4">
                                        <div class="form-title">
                                            <label for="auto_update">{{ __('auto_update') }}</label>
                                        </div>
                                    </div>
                                    <div class="col-3 col-md-2">
                                        <label class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" name="auto_update" id="auto_update" checked value="1" class="custom-control-input">
                                            <span class="custom-control-label">{{ __('yes') }}</span>
                                        </label>
                                    </div>
                                    <div class="col-3 col-md-2">
                                        <label class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" name="auto_update" id="auto_update" value="0" class="custom-control-input">
                                            <span class="custom-control-label">{{ __('no') }}</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="row p-l-15">
                                    <div class="col-12 col-md-4">
                                        <div class="form-title">
                                            <label for="show_read_more">{{ __('show_read_more') }}</label>
                                        </div>
                                    </div>
                                    <div class="col-3 col-md-2">
                                        <label class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" name="show_read_more" id="show_read_more" checked value="1" class="custom-control-input">
                                            <span class="custom-control-label">{{ __('yes') }}</span>
                                        </label>
                                    </div>
                                    <div class="col-3 col-md-2">
                                        <label class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" name="show_read_more" id="show_read_more" value="0" class="custom-control-input">
                                            <span class="custom-control-label">{{ __('no') }}</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="row p-l-15">
                                    <div class="col-12 col-md-4">
                                        <div class="form-title">
                                            <label for="show_author">{{ __('keep_post_original_publish_date') }}</label>
                                        </div>
                                    </div>
                                    <div class="col-3 col-md-2">
                                        <label class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" name="keep_date" id="keep_date" checked value="1" class="custom-control-input">
                                            <span class="custom-control-label">{{ __('yes') }}</span>
                                        </label>
                                    </div>
                                    <div class="col-3 col-md-2">
                                        <label class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" name="keep_date" id="keep_date" value="0" class="custom-control-input">
                                            <span class="custom-control-label">{{ __('no') }}</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <!-- options section end -->
                            <!-- SEO section start -->
                            <div class="add-new-page  bg-white p-20 m-b-20">
                                <div class="block-header">
                                    <h2>{{ __('seo_details') }}</h2>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="post-keywords" class="col-form-label">متاتگ کلمات کلیدی</label>
                                        <input id="post-keywords" name="meta_keywords" value="{{ old('meta_keywords') }}" type="text" class="form-control">
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="post_desc">متاتگ توضیحات</label>
                                        <textarea class="form-control" id="meta_description" value="{{ old('meta_description') }}" name="meta_description" rows="3"></textarea>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="post_tags" class="col-form-label">{{ __('tags') }}</label>
                                        <input id="post_tags" name="tags" type="text" value="{{ old('tags') }}" data-role="tagsinput" class="form-control" />
                                    </div>
                                </div>
                            </div>
                            <!-- SEO section end -->
                        </div>
                        <!-- Main Content section end -->

                        <!-- right sidebar start -->
                        <div class="col-12">
                            <div class="row add-new-page  bg-white p-20 m-b-20">
                                <div class="col-sm-6 p-l-15">
                                    <label>
                                        <b>استان </b>
                                        <span>(اختیاری)</span>
                                    </label>
                                    <select name="state_id[]" class="form-control" multiple>
                                        @foreach ($states as $state)
                                            <option value="{{ $state->id }}">{{ $state->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-6 p-l-15">
                                    <label>
                                        <b>بخش یا صنعت </b>
                                        <span>(اختیاری)</span>
                                    </label>
                                    <select name="section_id[]" id="section" class="form-control" multiple>
                                        @foreach ($sections as $section)
                                            <option value="{{ $section->id }}">{{ $section->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="post_language">{{ __('select_language') }}*</label>
                                        <select class="form-control dynamic-category" id="post_language" name="language" data-dependent="category_id">
                                            @foreach ($activeLang as $lang)
                                                <option @if (App::getLocale() == $lang->code) Selected @endif value="{{ $lang->code }}">{{ $lang->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="post_status">{{ __('publish') }}*</label>
                                        <select class="form-control" id="post_status" name="status">
                                            <option value="1">انتشار</option>
                                            <option value="0">{{ __('draft') }}</option>
                                            @if (Sentinel::getUser()->hasAccess(['scheduled_post']))
                                                <option value="2">{{ __('scheduled') }}</option>
                                            @endif
                                        </select>
                                    </div>
                                    <div class="col-sm-12 divScheduleDate">
                                        <label for="scheduled_date">{{ __('schedule_date') }}</label>
                                        <div class="input-group">
                                            <label class="input-group-text" for="scheduled_date"><i class="fa fa-calendar-alt"></i></label>
                                            <input type="text" class="form-control example1" name="scheduled_date" style="direction: rtl" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div id="category-select-box" class="form-group">
                                        <label>{{ __('category') }}*</label>
                                        <div id="custom-category-drop-down-title">--انتخاب نمائید--</div>
                                        <div class="form-control" id="category-select">
                                            @foreach ($categories as $category)
                                                <div class="option" style="margin-bottom: 2px" data-category-id="{{ $category->id }}">
                                                    <input data-category-id="{{ $category->id }}" data-category-name="{{ $category->category_name }}" type="checkbox">
                                                    {{ $category->category_name }}
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group" id="sub-category-box">
                                        <label>{{ __('sub_category') }}</label>
                                        <div id="custom-drop-down-box">--انتخاب نمائید--</div>
                                        <div id="custom-drop-down"></div>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label class="custom-control" for="btnSubmit"></label>
                                        <button type="submit" name="btnSubmit" class="btn btn-primary pull-right"><i class="m-l-5 mdi mdi-plus"></i>افزودن RSS</button>
                                        <label class="" for="btnSubmit"></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- right sidebar end -->
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
            <!-- page info end-->
        </div>
    </div>

@endsection
@section('script')
    <script>
        $(document).ready(function() {

            $('.dynamic-category').change(function() {
                if ($(this).val() != '') {
                    var select = $(this).attr("id");
                    var value = $(this).val();
                    var dependent = $(this).data('dependent');
                    var _token = "{{ csrf_token() }}";
                    $.ajax({
                        url: "{{ route('category-fetch') }}",
                        method: "POST",
                        data: {
                            select: select,
                            value: value,
                            _token: _token
                        },
                        success: function(result) {
                            $('#' + dependent).html(result);
                        }

                    })
                }
            });


            $('#post_language').change(function() {
                $('#category_id').val('');
                $('#sub_category_id').val('');
            });

            $('.dynamic').change(function() {
                if ($(this).val() != '') {
                    var select = $(this).attr("id");
                    var value = $(this).val();
                    var dependent = $(this).data('dependent');
                    var _token = "{{ csrf_token() }}";
                    $.ajax({
                        url: "{{ route('subcategory-fetch') }}",
                        method: "POST",
                        data: {
                            select: select,
                            value: value,
                            _token: _token
                        },
                        success: function(result) {
                            $('#' + dependent).html(result);
                        }

                    })
                }
            });

            $('#category').change(function() {
                $('#sub_category_id').val('');
            });
        });
    </script>

    <script type="text/javascript" src="{{ static_asset('js/post.js') }}"></script>
    <script src="{{ static_asset('js/tagsinput.js') }}"></script>
    <script src="{{ url('public/js/multiselect-dropdown.js') }}"></script>
    <script>
        $(".multiselect-dropdown").addClass("form-control");
    </script>











    {{-- //////////////////////////////////////////////////////////////////////////////////////// --}}



    <script>
        $(document).ready(function() {

            $('.dynamic-category').change(function() {
                if ($(this).val() != '') {
                    var select = $(this).attr("id");
                    var value = $(this).val();
                    var dependent = $(this).data('dependent');
                    var _token = "{{ csrf_token() }}";
                    $.ajax({
                        url: "{{ route('category-fetch') }}",
                        method: "POST",
                        data: {
                            select: select,
                            value: value,
                            _token: _token
                        },
                        success: function(result) {
                            $('#' + dependent).html(result);
                        }
                    })
                }
            });

            $('#post_language').change(function() {
                $('#category_id').val('');
                $('#sub_category_id').val('');
            });




            ////////////////////////////////////////////////////////////////////////////////////////////////////////



            $('.dynamic').change(function() {
                var select = $(this).attr("id");
                var value = $(this).val();
                var dependent = $(this).data('dependent');
                var _token = "{{ csrf_token() }}";
                $.ajax({
                    url: "{{ route('subcategory-fetch') }}",
                    method: "POST",
                    data: {
                        select: select,
                        value: value,
                        _token: _token
                    },
                    success: function(result) {
                        $('#custom-drop-down').html(result);
                        $('#custom-drop-down').show();
                        if (result == '') {
                            $('#custom-drop-down').hide();
                        }
                    }
                })
            });

            category_ids = [];
            sub_category_ids = [];
            first_step = 0;

            // change subcategory input selection
            $(document).on('click', "#custom-drop-down .option input[type='checkbox']", function() {
                var sub_category_id = $(this).data('sub-category-id');
                if ($(this).is(':checked')) {
                    sub_category_ids.push(sub_category_id);
                } else {
                    sub_category_ids = sub_category_ids.filter(function(elem) {
                        return elem != sub_category_id;
                    });
                }
                // console.log(sub_category_ids);

            });

            $("#custom-category-drop-down-title").click(function(e) {

                $("#category-select").show();

            });

            // $("#custom-drop-down-box").click(function(e) {

            //     $("#custom-drop-down").toggle();

            // });

            $("body").on('click',
                function(e) {
                    // alert(99);
                    console.log($(e.target));
                    return true;
                    if ($(e.target).parents("#category-select-box").length == 0 && $(e.target).parents("#custom-drop-down").length == 0) {
                        $("#category-select").hide();
                        $("#custom-drop-down").hide();
                    }

                    console.log($(e.target).siblings("#custom-drop-down-box"));
                    if ($(e.target).parents("#custom-drop-down-box").length > 1) {
                        $("#custom-drop-down").toggle();
                    }
                }
            );

            // $("body").on('click',
            //     function(e) {
            //         console.log($(e.target).parents("#category-select-box").length === 0);
            //         return true;
            //         if ($(e.target).parents("#category-select-box").length == 0 || $(e.target).parents("#custom-drop-down").length === 0) {
            //             $("#category-select").hide();
            //         }
            //     }
            // );

            // $("body").on('click', '#custom-drop-down-box',
            //     function(e) {
            //         // alert(8888);
            //         $("#custom-drop-down").toggle();
            //         if ($("#custom-drop-down-box .badge").length < 1) {}
            //     }
            // );



            // changed category check box event
            $("#category-select-box input").click(function(e) {
                var category_id = $(this).data('category-id');
                var category_name = $(this).data('category-name');
                if ($(this).is(':checked')) {
                    category_ids.push(category_id);
                    $(this).parent(".option").css("background", "#ccc");
                    var content = `<span class="badge ml-1" style="background: #ccc" data-category-id="${category_id}">
                                    <span class="fa fa-times" data-category-id="${category_id}" title="حذف این گروه"></span>
                                    ${category_name}
                                </span>`;
                    if (first_step) {
                        $("#custom-category-drop-down-title").append(content);
                    } else {
                        first_step = 1;
                        $("#custom-category-drop-down-title").html(content);
                    }
                    var _token = "{{ csrf_token() }}";
                    // console.log(category_id);
                    $.ajax({
                        url: "{{ route('subcategory-fetch') }}",
                        method: "POST",
                        data: {
                            category_id: category_id,
                            _token: _token
                        },
                        success: function(result) {
                            $('#custom-drop-down').append(result);
                            $('#custom-drop-down').show();
                            if (result == '') {
                                $('#custom-drop-down').hide();
                            }
                        }
                    })

                } else {
                    category_ids = category_ids.filter(function(elem) {
                        return elem != category_id;
                    });
                    $(this).parent(".option").css("background", "white");

                    $("#custom-category-drop-down-title span.badge").each(function() {
                        var category_id_elem = $(this).data('category-id');
                        if (category_id_elem == category_id) {
                            $(this).remove();
                        }

                        var count = $("#custom-category-drop-down-title span.badge").length;
                        if (count == 0) {
                            $("#custom-category-drop-down-title").html("--انتخاب نمائید--");
                        }
                    });
                    $("#custom-drop-down .category-box").each(function() {
                        var category_id_elem = $(this).data('category-id');
                        if (category_id == category_id_elem) {
                            $(this).remove();
                        }
                    });

                    if ($("#custom-drop-down .category-box").length == 0) {
                        $("#custom-drop-down").hide();
                    }

                    $("#category-select-box input").each(function() {
                        var category_id_elem = $(this).data('category-id');
                        if (category_id == category_id_elem) {
                            $(this).prop('checked', false);
                            $(this).parents(".option").css("background", "white");
                        }
                        var count = $("#custom-category-drop-down-title span.badge").length;
                        if (count == 0) {
                            $("#custom-category-drop-down-title").html("--انتخاب نمائید--");
                            first_step = 0;
                        }
                    });
                }
                console.log(category_ids);
            });



            // press x button for delete category
            $(document).on('click', '#custom-category-drop-down-title span.fa-times', function() {
                var category_id = $(this).data('category-id');
                $(this).parent(".badge").remove();
                $("#category-select-box input").each(function() {
                    var category_id_elem = $(this).data('category-id');
                    if (category_id == category_id_elem) {
                        $(this).prop('checked', false);
                        $(this).parents(".option").css("background", "white");
                    }
                    var count = $("#custom-category-drop-down-title span.badge").length;
                    if (count == 0) {
                        $("#custom-category-drop-down-title").html("--انتخاب نمائید--");
                        first_step = 0;
                    }
                });

                $("#custom-drop-down .category-box").each(function() {
                    var category_id_elem = $(this).data('category-id');
                    if (category_id == category_id_elem) {
                        $(this).remove();
                    }
                });

                if ($("#custom-drop-down .category-box").length == 0) {
                    $("#custom-drop-down").hide();
                }


                category_ids = category_ids.filter(function(elem) {
                    return elem != category_id;
                });
                // console.log(category_ids);

            });


            $("#create-rss-feed").submit(function(e) {
                $("input[name='category_ids']").val(category_ids);
                $("input[name='sub_category_ids']").val(sub_category_ids);
                console.log(sub_category_ids);
                return true;
            });



            //press x button for delete sub_category
            $(document).on('click', '#custom-drop-down-box .fa-times', function() {

                var sub_category_id = $(this).parent('.badge').data('sub-category-id');
                // console.log(sub_category_id);
                $("#custom-drop-down input").each(function() {
                    if ($(this).data("sub-category-id") == sub_category_id && $(this).is(":checked")) {
                        $(this).prop("checked", false);
                    }
                });
                $(this).parent('span.badge').remove();

                var count = $("#custom-drop-down-box .badge").length;
                if (count == 0) {
                    $("#custom-drop-down-box").html('--انتخاب نمائید--');
                }

                sub_category_ids = sub_category_ids.filter(function(elem) {
                    return elem != sub_category_id;
                });

            });


            // change input sub_category
            $(document).on('change', '#custom-drop-down input', function(e) {
                e.preventDefault();
                var count = $("#custom-drop-down-box .badge").length;
                var category_id = $(this).data('category-id');
                var sub_category_id = $(this).data('sub-category-id');
                var sub_category_name = $(this).data('sub-category-name');
                console.log('first', sub_category_ids);

                if ($(this).is(":checked")) {
                    var content = `<span class="badge ml-1" style="background: #ccc" data-category-id="${category_id}" data-sub-category-id="${sub_category_id}">
                                    <span class="fa fa-times" data-category-id="${category_id}" title="حذف این زیر گروه"></span>
                                    ${sub_category_name}
                                </span>`;
                    if (count == 0) {
                        $("#custom-drop-down-box").html(content);
                    } else {
                        $("#custom-drop-down-box").append(content);
                    }

                    // sub_category_ids.push(sub_category_id);
                    console.log('second', sub_category_ids);

                } else {
                    $(this).parent('span.badge').remove();
                    $("#custom-drop-down-box .badge").each(function() {
                        if ($(this).data("sub-category-id") == sub_category_id) {
                            $(this).remove();
                        }
                    });
                    if (count == 1) {
                        $("#custom-drop-down-box").html('--انتخاب نمائید--');
                    }

                    sub_category_ids = sub_category_ids.filter(function(elem) {
                        return elem != sub_category_id;
                    });

                }

            });





            //////////////////////////////////////////////////////////////////////////////////////////////////



            $('#category').change(function() {
                $('#sub_category_id').val('');
            });
        });
    </script>
    <script type="text/javascript" src="{{ static_asset('js/post.js') }}"></script>
    <script type="text/javascript" src="{{ static_asset('js/tagsinput.js') }}"></script>
    <script>
        addContent = function(value) {

            var content_number = $("#content_number").val();
            content_number++;

            $.ajax({
                url: "{{ route('add-content') }}",
                method: "GET",
                data: {
                    value: value,
                    content_count: content_number
                },
                success: function(result) {
                    $('.content-area').append(result);
                    $("#content_number").val(content_number);

                    // auto scrolling to newly added element
                    var newlyAdded = 'content_' + content_number;
                    $('body, html').animate({
                        scrollTop: $('.' + newlyAdded).offset().top
                    }, 1000);
                }
            });
        }

        $(document).on("click", ".add-new-page .row_remove", function() {
            let element = $(this).parents('.add-new-page');
            element.hide("slow", function() {
                $(this).remove();
            })
        });
    </script>

    <script type="text/javascript" src="{{ url('public/vendor/persian-datepicker/persian-date.js') }}"></script>
    <script type="text/javascript" src="{{ url('public/vendor/persian-datepicker/persian-datepicker.js') }}"></script>


    <script>
        $(document).ready(function() {
            $(".example1").pDatepicker({
                'timePicker': {
                    'enabled': false,
                },
                format: ' H:m:s YYYY/MM/DD ',

            });


            $("input[name='title']").blur(function() {
                $("input[name='meta_title']").val($(this).val());
                var text = $(this).val();
                text = text.replace(/\ /g, "-");
                $("input[name='slug']").val(text);
            });

            $("textarea[name='sub_title']").blur(function() {
                $("textarea[name='meta_description']").val($(this).val());
            });
        });
    </script>
    <script>
        $("#post-keywords").bind("keypress", function(e) {
            if (e.keyCode == 13) {
                e.preventDefault();
                $(this).val($(this).val() + ',');
            }
        });

        $("#meta_title").bind("keypress", function(e) {
            if (e.keyCode == 13) {
                e.preventDefault();
            }
        });
    </script>



    {{-- //////////////////////////////////////////////////////////////////////////////////////// --}}
@endsection
