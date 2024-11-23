@extends('admin.part.app')
@section('title')
    @lang('offers')
@endsection
@section('styles')
    <style>
        input[type="checkbox"] {
            transform: scale(1.5);
        }

        body {
            margin-top: 20px;
        }

        .icon-box.medium {
            font-size: 20px;
            width: 50px;
            height: 50px;
            line-height: 50px;
        }

        .icon-box {
            font-size: 30px;
            margin-bottom: 33px;
            display: inline-block;
            color: #ffffff;
            height: 65px;
            width: 65px;
            line-height: 65px;
            background-color: #59b73f;
            text-align: center;
            border-radius: 0.3rem;
        }

        .social-icon-style2 li a {
            display: inline-block;
            font-size: 14px;
            text-align: center;
            color: #ffffff;
            background: #59b73f;
            height: 41px;
            line-height: 41px;
            width: 41px;
        }

        .rounded-3 {
            border-radius: 0.3rem !important;
        }

        .social-icon-style2 {
            margin-bottom: 0;
            display: inline-block;
            padding-left: 10px;
            list-style: none;
        }

        .social-icon-style2 li {
            vertical-align: middle;
            display: inline-block;
            margin-right: 5px;
        }

        a, a:active, a:focus {
            color: #616161;
            text-decoration: none;
            transition-timing-function: ease-in-out;
            -ms-transition-timing-function: ease-in-out;
            -moz-transition-timing-function: ease-in-out;
            -webkit-transition-timing-function: ease-in-out;
            -o-transition-timing-function: ease-in-out;
            transition-duration: .2s;
            -ms-transition-duration: .2s;
            -moz-transition-duration: .2s;
            -webkit-transition-duration: .2s;
            -o-transition-duration: .2s;
        }

        .text-secondary, .text-secondary-hover:hover {
            color: #59b73f !important;
        }

        .display-25 {
            font-size: 1.4rem;
        }

        .text-primary, .text-primary-hover:hover {
            color: #ff712a !important;
        }

        p {
            margin: 0 0 20px;
        }

        .mb-1-6, .my-1-6 {
            margin-bottom: 1.6rem;
        }
    </style>
@endsection
@section('content')
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-left mb-0">@lang('offers')</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                {{--                                <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">@lang('home')</a>--}}
                                {{--                                </li>--}}
                                <li class="breadcrumb-item"><a
                                        href="{{ route('offers.index') }}">@lang('offers')</a>
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">

            <section id="">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="head-label">
                                    <h4 class="card-title">@lang('offers')</h4>
                                </div>
                            </div>
                            <div class="card-body">
                                <form id="search_form">
                                    <div class="row">
                                        <div class="col-3">
                                            <div class="form-group">
                                                <label for="s_user_name">@lang('user name')</label>
                                                <input id="s_user_name" type="text" class="search_input form-control"
                                                       placeholder="@lang('name')">
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-group">
                                                <label for="s_post_name">اسم المنشور</label>
                                                <input id="s_post_name" type="text" class="search_input form-control"
                                                       placeholder="@lang('اسم المنشور')">
                                            </div>
                                        </div>
                                        <div class="col-3" style="margin-top: 20px">
                                            <button id="search_btn" class="btn btn-outline-info" type="submit">
                                                <span><i class="fa fa-search"></i> @lang('search')</span>
                                            </button>
                                            <button id="clear_btn" class="btn btn-outline-secondary" type="submit">
                                                <span><i class="fa fa-undo"></i> @lang('reset')</span>
                                            </button>
{{--                                            <button id="btn_delete_all"--}}
{{--                                                    class="btn_delete_all btn btn-outline-danger " type="button">--}}
{{--                                                <span><i class="fa fa-lg fa-trash-alt" aria-hidden="true"></i> @lang('delete')</span>--}}
{{--                                            </button>--}}
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="table-responsive card-datatable" style="padding: 20px">
                                <table class="table" id="datatable">
                                    <thead>
                                    <tr>
                                        <th>@lang('user name')</th>
                                        <th>@lang('post name')</th>
                                        <th>@lang('place')</th>
                                        <th>@lang('title')</th>
                                        <th >@lang('category')</th>
                                        <th>@lang('image')</th>
                                        <th>@lang('status')</th>
                                        <th>@lang('details')</th>


                                    </tr>
                                    </thead>
                                    <tbody></tbody>

                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

        </div>
    </div>
    <div class="modal fade" id="btn_details" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">

{{--                <div class="modal-header">--}}
{{--                    <h5 class="modal-title" id="exampleModalLongTitle">@lang('details')</h5>--}}
{{--                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">--}}
{{--                        <span aria-hidden="true">&times;</span>--}}
{{--                    </button>--}}
{{--                </div>--}}

            <div class="modal-content">
                        <div class="card">
                            <textarea id="details" readonly></textarea>
                        </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('js')
@endsection
@section('scripts')
    <script src="https://cdn.ckeditor.com/ckeditor5/25.0.0/classic/ckeditor.js"></script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var table = $('#datatable').DataTable({

            processing: true,
            serverSide: true,
            responsive: true,
            "oLanguage": {
                @if (app()->isLocale('ar'))
                "sEmptyTable": "ليست هناك بيانات متاحة في الجدول",
                "sLoadingRecords": "جارٍ التحميل...",
                "sProcessing": "جارٍ التحميل...",
                "sLengthMenu": "أظهر _MENU_ مدخلات",
                "sZeroRecords": "لم يعثر على أية سجلات",
                "sInfo": "إظهار _START_ إلى _END_ من أصل _TOTAL_ مدخل",
                "sInfoEmpty": "يعرض 0 إلى 0 من أصل 0 سجل",
                "sInfoFiltered": "(منتقاة من مجموع _MAX_ مُدخل)",
                "sInfoPostFix": "",
                "sSearch": "ابحث:",
                "oAria": {
                    "sSortAscending": ": تفعيل لترتيب العمود تصاعدياً",
                    "sSortDescending": ": تفعيل لترتيب العمود تنازلياً"
                },
                @endif // "oPaginate": {"sPrevious": '<-', "sNext": '->'},
            },
            ajax: {
                url: '{{ route('offers.indexTable', app()->getLocale()) }}',
                data: function (d) {
                    d.status = $('#s_status').val();
                    d.post_name = $('#s_post_name').val();
                    d.user_name = $('#s_user_name').val();

                }
            },
            dom: '<"row"<"col-md-12"<"row"<"col-md-6"B><"col-md-6"f> > ><"col-md-12"rt> <"col-md-12"<"row"<"col-md-5"i><"col-md-7"p>>> >',
            "buttons": [
                {

                    "extend": 'excel',
                    text: '<span class="fa fa-file-excel-o"></span> @lang('Excel Export')',
                    "titleAttr": 'Excel',
                    "action": newexportaction,
                    "exportOptions": {
                        columns: ':not(:last-child)',
                    },
                    "filename": function () {
                        var d = new Date();
                        var l = d.getFullYear() + '-' + (d.getMonth() + 1) + '-' + d.getDate();
                        var n = d.getHours() + "-" + d.getMinutes() + "-" + d.getSeconds();
                        return 'List_' + l + ' ' + n;
                    },
                },
            ],

            columns: [

                {
                    data: 'user_name',
                    name: 'user_name',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'post_name',
                    name: 'post_name',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'place',
                    name: 'place',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'title',
                    name: 'title',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'category_name',
                    name: 'category_name',
                    orderable: false,
                    searchable: false
                },
                {
                    "data": 'image',
                    "name": 'image',
                    render: function (data, type, full, meta) {
                        return `<img src="${data}" style="width:50px;height:50px;" class="avatar avatar-sm me-3">`;
                    },
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'status',
                    name: 'status',
                },
                {
                    data: 'detailss',
                    name: 'detailss',
                },
            ]

        });

        $(document).on('click', '.btn_details', function (event) {

            $('.ss').remove();
            event.preventDefault();
            var button = $(this)
            var uuid = button.data('uuid')
            $('#uuid').val(uuid);
            $('#details').val(button.data('details'))



        });

        //Edit
    </script>
@endsection
