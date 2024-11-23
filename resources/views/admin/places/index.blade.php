@extends('admin.part.app')
@section('title')
    @lang('Places')
@endsection
@section('styles')
    <style>
        input[type="checkbox"] {
            transform: scale(1.5);
        }

        .input-images-2 .image-uploader .uploaded .uploaded-image img:hover {
            transform: scale(2.2); /* تكبير الصورة عند تحويم المؤشر */

        }
        #map {
            height: 400px;
            width: 100%;
        }

        #edit_map {
            height: 400px;
            width: 100%;
        }
    </style>
@endsection
@section('content')
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-left mb-0">@lang('Places')</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">

                                <li class="breadcrumb-item"><a
                                        href="{{ route('places.index') }}">@lang('Places')</a>
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
                                    <h4 class="card-title">@lang('Places')</h4>
                                </div>
                                {{--                                @can('place-create')--}}
                                <div class="text-right">
                                    <div class="form-group">
                                        <button class="btn btn-outline-primary button_modal" type="button"
                                                data-toggle="modal" id=""
                                                data-target="#full-modal-stem"><span><i
                                                    class="fa fa-plus"></i>@lang('add')</span>
                                        </button>
                                        <button

                                            class="btn_delete_all btn btn-outline-danger " type="button">
                                            <span><i aria-hidden="true"></i> @lang('delete')</span>
                                        </button>
                                        <button
                                            data-status="1" class="btn_status btn btn-outline-success " type="button">
                                            <span><i aria-hidden="true"></i> @lang('activate')</span>
                                        </button>
                                        <button
                                            data-status="0" class="btn_status btn btn-outline-warning " type="button">
                                            <span><i aria-hidden="true"></i> @lang('deactivate')</span>
                                        </button>
                                    </div>
                                </div>
                                {{--                                @endcan--}}
                            </div>
                            <div class="card-body">
                                <form id="search_form">
                                    <div class="row">
                                        <div class="col-3">
                                            <div class="form-group">
                                                <label for="s_title">@lang('title')</label>
                                                <input id="s_title" type="text"
                                                       class="search_input form-control"
                                                       placeholder="@lang('title')">
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-group">
                                                <label for="s_entity_name">@lang('Name')</label>
                                                <input id="s_entity_name" type="text"
                                                       class="search_input form-control"
                                                       placeholder="@lang('Name')">
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-group">
                                                <label for="s_category_uuid">@lang('categories')</label>
                                                <select name="s_category_uuid" id="s_category_uuid"
                                                        class="search_input form-control">
                                                    <option selected
                                                            disabled>@lang('select')  @lang('categories')</option>
                                                    @foreach ($categories as $item)
                                                        <option value="{{ $item->uuid }}"> {{ $item->name }} </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="s_status">@lang('status')</label>
                                                <select name="s_status" id="s_status" class="search_input form-control">
                                                    <option selected disabled>@lang('select') @lang('status')</option>
                                                    <option value="1"> @lang('active') </option>
                                                    <option value="2"> @lang('inactive') </option>
                                                </select>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </div>

                                        <div class="col-3" style="margin-top: 20px">
                                            <button id="search_btn" class="btn btn-outline-info" type="submit">
                                                <span><i class="fa fa-search"></i> @lang('search')</span>
                                            </button>
                                            <button id="clear_btn" class="btn btn-outline-secondary" type="submit">
                                                <span><i class="fa fa-undo"></i> @lang('reset')</span>
                                            </button>


                                            <div class="col-3" style="margin-top: 20px">

                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="table-responsive card-datatable" style="padding: 20px">
                                <table class="table" id="datatable">
                                    <thead>
                                    <tr>
                                        <th><input name="select_all" id="example-select-all" type="checkbox"
                                                   onclick="CheckAll('box1', this)"/></th>
                                        <th>@lang('name')</th>
                                        <th>@lang('title')</th>
                                        <th>@lang('image')</th>
                                        <th>@lang('category name')</th>
                                        <th>@lang('status')</th>
                                        <th style="width: 225px;">@lang('actions')</th>
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

    <!-- Modal -->
    <div class="modal fade" class="full-modal-stem" id="full-modal-stem" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog  modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">@lang('add')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('places.store') }}" method="POST" id="add-mode-form" class="add-mode-form"
                      enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        @foreach (locales() as $key => $value)
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="name_{{ $key }}">@lang('name') @lang($value)</label>
                                    <input type="text" class="form-control"
                                           placeholder="@lang('name') @lang($value)" name="name_{{ $key }}"
                                           id="name_{{ $key }}">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>

                        @endforeach
                        @foreach (locales() as $key => $value)
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="title_{{ $key }}">@lang('title') @lang($value)</label>
                                    <input type="text" class="form-control"
                                           placeholder="@lang('title') @lang($value)" name="title_{{ $key }}"
                                           id="title_{{ $key }}">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>

                        @endforeach

                        @foreach (locales() as $key => $value)
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="details_{{ $key }}">@lang('details') @lang($value)</label>
                                    <textarea rows="5" type="text" class="form-control"
                                              placeholder="@lang('details') @lang($value)" name="details_{{ $key }}"
                                              id="details_{{ $key }}"></textarea>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>

                        @endforeach
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="about">@lang('mobile')
                                    </label>
                                    <input type="tel" class="form-control" placeholder="@lang('mobile')"
                                           name="mobile" id="mobile">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        <div class="col-12">
                            <label class="form-label select-label">@lang('select'),@lang('categories')</label>
                            <select name="category_uuid" class="select">
                                @foreach ($categories as $item)
                                    <option value="{{ $item->uuid }}"> {{ $item->name }} </option>
                                @endforeach
                            </select>
                        </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="about">@lang('whatsapp')
                                    </label>
                                    <input type="text" class="form-control" placeholder="@lang('whatsapp')"
                                           name="whatsapp" id="whatsapp">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="about">@lang('x')
                                    </label>
                                    <input type="text" class="form-control" placeholder="@lang('x')"
                                           name="x" id="x">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="about">@lang('telegram')
                                    </label>
                                    <input type="text" class="form-control" placeholder="@lang('telegram')"
                                           name="telegram" id="telegram">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="about">@lang('instagram')
                                    </label>
                                    <input type="text" class="form-control" placeholder="@lang('instagram')"
                                           name="instagram" id="instagram">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="about">@lang('facebook')
                                    </label>
                                    <input type="text" class="form-control" placeholder="@lang('facebook')"
                                           name="facebook" id="facebook">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            @foreach (locales() as $key => $value)
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="address_{{ $key }}">@lang('details') @lang($value)</label>
                                        <input  type="text" class="form-control"
                                                placeholder="@lang('address') @lang($value)" name="address_{{ $key }}"
                                                id="">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>

                            @endforeach
                            <div id="map"></div>
                            <input type="hidden" name="lat" id="lat">
                            <input type="hidden" name="lng" id="lng">
                        <div class="col-12">
                            <label for="icon">@lang('flag')</label>
                            <div>
                                <div class="fileinput fileinput-exists"
                                     data-provides="fileinput">
                                    <div class="fileinput-preview thumbnail"
                                         data-trigger="fileinput"
                                         style="width: 200px; height: 150px;">
                                        <img id="flag"
                                             src="{{asset('dashboard/app-assets/images/placeholder.jpeg')}}"
                                             alt=""/>
                                    </div>
                                    <div class="form-group">
                                                    <span class="btn btn-secondary btn-file">
                                                        <span class="fileinput-new"> @lang('select_image')</span>
                                                        <span class="fileinput-exists"> @lang('select_image')</span>
                                                        <input class="form-control" type="file" name="image">
                                                    </span>
                                        <div class="invalid-feedback" style="display: block;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>


                    <div class="modal-footer">
                        <button class="btn btn-primary done">@lang('save')</button>

                        <button type="button" class="btn btn-secondary"
                                data-dismiss="modal">@lang('close')</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="edit_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
         aria-hidden="true">
        <div class="modal-dialog  modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">@lang('edit')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('places.update') }}" method="POST" id="form_edit" class=""
                      enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="uuid" id="uuid" class="form-control"/>
                    <div class="modal-body">
                        @foreach (locales() as $key => $value)
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="name_{{ $key }}">@lang('name') @lang($value)</label>
                                    <input type="text" class="form-control"
                                           placeholder="@lang('name') @lang($value)" name="name_{{ $key }}"
                                           id="edit_name_{{ $key }}">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>

                        @endforeach
                        @foreach (locales() as $key => $value)
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="title_{{ $key }}">@lang('title') @lang($value)</label>
                                    <input type="text" class="form-control"
                                           placeholder="@lang('title') @lang($value)" name="title_{{ $key }}"
                                           id="edit_title_{{ $key }}">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>

                        @endforeach

                        @foreach (locales() as $key => $value)
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="details_{{ $key }}">@lang('details') @lang($value)</label>
                                    <textarea rows="5" type="text" class="form-control"
                                              placeholder="@lang('details') @lang($value)" name="details_{{ $key }}"
                                              id="edit_details_{{ $key }}"></textarea>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>

                        @endforeach
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="about">@lang('mobile')
                                    </label>
                                    <input type="tel" class="form-control" placeholder="@lang('mobile')"
                                           name="mobile" id="edit_mobile">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        <div class="col-12">
                            <label class="form-label select-label">@lang('select'),@lang('categories')</label>
                            <select name="category_uuid" id="edit_category_uuid" class="select">
                                @foreach ($categories as $item)
                                    <option value="{{ $item->uuid }}"> {{ $item->name }} </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="about">@lang('whatsapp')
                                </label>
                                <input type="text" class="form-control" placeholder="@lang('whatsapp')"
                                       name="whatsapp" id="edit_whatsapp">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="about">@lang('x')
                                </label>
                                <input type="text" class="form-control" placeholder="@lang('x')"
                                       name="x" id="edit_x">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="about">@lang('telegram')
                                </label>
                                <input type="text" class="form-control" placeholder="@lang('telegram')"
                                       name="telegram" id="edit_telegram">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="about">@lang('instagram')
                                </label>
                                <input type="text" class="form-control" placeholder="@lang('instagram')"
                                       name="instagram" id="edit_instagram">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="about">@lang('facebook')
                                </label>
                                <input type="text" class="form-control" placeholder="@lang('facebook')"
                                       name="facebook" id="edit_facebook">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                            @foreach (locales() as $key => $value)
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="address_{{ $key }}">@lang('details') @lang($value)</label>
                                        <input  type="text" class="form-control"
                                                  placeholder="@lang('address') @lang($value)" name="address_{{ $key }}"
                                                  id="edit_address_{{ $key }}">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>

                            @endforeach
                            <div id="edit_map"></div>
                            <input type="hidden" name="lat" id="edit_lat">
                            <input type="hidden" name="lng" id="edit_lng">
                        <div class="col-12">
                            <label for="icon">@lang('flag')</label>
                            <div>
                                <div class="fileinput fileinput-exists"
                                     data-provides="fileinput">
                                    <div class="fileinput-preview thumbnail"
                                         data-trigger="fileinput"
                                         style="width: 200px; height: 150px;">
                                        <img id="edit_src_image"
                                             src="{{asset('dashboard/app-assets/images/placeholder.jpeg')}}"
                                             alt=""/>
                                    </div>
                                    <div class="form-group">
                                                    <span class="btn btn-secondary btn-file">
                                                        <span class="fileinput-new"> @lang('select_image')</span>
                                                        <span class="fileinput-exists"> @lang('select_image')</span>
                                                        <input class="form-control" type="file" name="image">
                                                    </span>
                                        <div class="invalid-feedback" style="display: block;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                            <div class="modal-footer">
                                <button class="btn btn-primary done">@lang('save')</button>

                                <button type="button" class="btn btn-secondary"
                                        data-dismiss="modal">@lang('close')</button>
                            </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="https://cdn.ckeditor.com/ckeditor5/25.0.0/classic/ckeditor.js"></script>
    <script>
        let map, edit_map;
        let marker, edit_marker;

        async function initMap() {
            // The location of Uluru
            const position = {lat: 24.121894767907012, lng: 46.74972295072583};
            // Request needed libraries.
            //@ts-ignore
            const {Map} = await google.maps.importLibrary("maps");

            // The map, centered at Uluru
            map = new Map(document.getElementById("map"), {
                zoom: 4,
                center: position,
                mapId: "DEMO_MAP_ID",
            });

            marker = new google.maps.Marker({
                map: map,
                position: position,
                title: "Center"
            });

            google.maps.event.addListener(map, 'click', function (e) {
                let myLatlng = e["latLng"];
                marker.setPosition(myLatlng);
                map.setCenter(myLatlng);
                $('#lat').val(myLatlng.lat)
                $('#lng').val(myLatlng.lng)

            });


            // The map, centered at Uluru
            edit_map = new Map(document.getElementById("edit_map"), {
                zoom: 4,
                center: position,
                mapId: "DEMO_MAP_ID",
            });

            edit_marker = new google.maps.Marker({
                map: edit_map,
                position: position,
                title: "Center"
            });


            google.maps.event.addListener(edit_map, 'click', function (e) {
                let myLatlng = e["latLng"];
                edit_marker.setPosition(myLatlng);
                edit_map.setCenter(myLatlng);
                $('#edit_lat').val(myLatlng.lat)
                $('#edit_lng').val(myLatlng.lng)
            });

        }

    </script>
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        //bindTable
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
                url: '{{ route('places.indexTable', app()->getLocale()) }}',
                data: function (d) {
                    d.status = $('#s_status').val();
                    d.title = $('#s_title').val();
                    d.category_uuid = $('#s_category_uuid').val();
                    d.entity_name = $('#s_name').val();

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
            columns: [{
                "render": function (data, type, full, meta) {
                    return `<td><input type="checkbox" onclick="checkClickFunc()" value="${data}" class="box1" ></td>
`;
                },
                name: 'checkbox',
                data: 'checkbox',
                orderable: false,
                searchable: false
            },

                {
                    data: 'name_translate',
                    name: 'name_translate'
                },
                {
                    data: 'title_translate',
                    name: 'title_translate'
                },
                {
                    data: 'image',
                    name: 'image',
                    render: function (data, type, full, meta) {
                        return `<img src="${data}" style="width:100px;height:100px;"  class="img-fluid img-thumbnail">`;
                    },
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'category_name',
                    name: 'category_name',
                    orderable: false,
                    searchable: false,
                },
                {
                    data: 'status',
                    name: 'status'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: true
                },
                {{--                @endif--}}
            ]

        });

        $(document).ready(function () {
            $(document).on('click', '.btn_edit', function (event) {
                $('input').removeClass('is-invalid');
                $('.invalid-feedback').text('');
                event.preventDefault();
                var button = $(this)
                var uuid = button.data('uuid')
                $('#uuid').val(uuid);
                $('#edit_title').val(button.data('title'))
                $('#edit_x').val(button.data('x'))
                $('#edit_mobile').val(button.data('mobile'))

                $('#edit_whatsapp').val(button.data('whatsapp'))
                $('#edit_telegram').val(button.data('telegram'))
                $('#edit_instagram').val(button.data('instagram'))
                $('#edit_facebook').val(button.data('facebook'))
                $('#edit_address').val(button.data('address'))
                let latlng = {lat: parseFloat(button.data('lat')), lng: parseFloat(button.data('lng'))};
                edit_marker.setPosition(latlng);
                edit_map.setCenter(latlng);
                @foreach (locales() as $key => $value)
                $('#edit_title_{{ $key }}').val(button.data('title_{{ $key }}'))
                $('#edit_details_{{ $key }}').val(button.data('details_{{ $key }}'))
                $('#edit_name_{{ $key }}').val(button.data('name_{{ $key }}'))
                $('#edit_address_{{ $key }}').val(button.data('address_{{ $key }}'))

                @endforeach

                var category_categories_uuids = button.data('categories_uuid') + '';
                if (category_categories_uuids.indexOf(',') >= 0) {
                    category_categories_uuids = button.data('categories_uuid').split(',');
                }
                $('#edit_categories_uuid').val(category_categories_uuids).trigger('change');
                console.log(button.data('key'))
                $('#edit_details').val(button.data('details'))
                $('#edit_src_image').attr('src', button.data('image'));


            });
        });

    </script>
    <script async
            src="https://maps.googleapis.com/maps/api/js?key={{ GOOGLE_API_KEY }}&callback=initMap">
    </script>
@endsection
