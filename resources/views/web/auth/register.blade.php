@extends('web.part.app')
@section('title')
    @lang('register')
@endsection
@section('content')

    <div class="container-fluid">
        <div class="directory d-flex gap-2 my-5">
            <a href="index.html" class="text-dark">الرئيسيه</a>
            <svg class="w-[24px] h-[24px] text-gray-800 text-dark" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                 width="24" height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="m15 19-7-7 7-7" />
            </svg>
            <span>تسجيل الدخول</span>
        </div>
    </div>
    <!-- start login form  -->
    <section>
        <div class="container-fluid">
            <div class="form-all">
                <h1 class="gray black text-center">انشاء حساب جديد</h1>
                @include('web.errors')
                <form class="my-5" method="post" action="{{route('register')}}">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label">الاسم الاول</label>
                        <input type="text" name="name" class="form-control shadow-none">
                    </div>

                    <div class="mb-4">
                        <label class="form-label">الاسم الاخير</label>
                        <input type="text" name="last_name"  class="form-control shadow-none">
                    </div>

                    <div class="mb-4">
                        <label class="form-label">النوع</label>
                        <select class="form-select shadow-none" name="gender"  aria-label="Default select example">
                            <option selected disabled class="h-0"></option>
                            <option value="1">ذكر</option>
                            <option value="2">انثى</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">البريد الاكتروني</label>
                        <input type="email" name="email"  class="form-control shadow-none">
                    </div>

                    <div class="mb-4">
                        <label class="form-label">رقم الهاتف</label>
                        <div class="form-control d-flex align-items-center gap-1">
                            <div class="special-drop-select">
                                @php
                                    $country=App\Models\Country::all();

                                @endphp
                                <div class="special-drop-button bg-transparent">
                                    <img id="special-selected-image" src="{{$country[0]->image}}" width="20px" alt="Selected">
                                    <span class="special-arrow">
                                        <svg class="w-[15px] h-[15px] text-dark" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7" />
                                        </svg>
                                    </span>
                                </div>
                                <div class="special-drop-content">
                                    @foreach($country as $item)
                                        <div class="special-drop-item text-dark" data-text="+{{$item->key}}" data-img="{{$item->image}}">
                                            <img src="{{$item->image}}" width="20px" alt="Image 1">
                                            <span>+{{$item->key}}</span>
                                        </div>


                                    @endforeach
                                </div>
                            </div>

                            <input type="text" id="pre" class="special-get-num border-0 outline-0" value="{{$country[0]->key}}" name="prefix" style="width: 55px;" readonly>
                            <input type="text" name="mobile" class="shadow-none border-0 w-100">
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="Password1" class="form-label">كلمه المرور</label>
                        <div class="form-control d-flex align-items-center justify-content-between">
                            <input type="password" name="password" class="border-0" id="Password1">
                            <span id="togglePassword" class="toggle-password">
                                <svg id="eyeVisible" class="w-[24px] h-[24px] text-secondary hidden" aria-hidden="true"
                                     xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                     viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-width="2"
                                          d="M21 12c0 1.2-4.03 6-9 6s-9-4.8-9-6c0-1.2 4.03-6 9-6s9 4.8 9 6Z" />
                                    <path stroke="currentColor" stroke-width="2"
                                          d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                                <svg id="eyeHidden" class="w-[24px] h-[24px] text-secondary" aria-hidden="true"
                                     xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                     viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                          stroke-width="2"
                                          d="M3.933 13.909A4.357 4.357 0 0 1 3 12c0-1 4-6 9-6m7.6 3.8A5.068 5.068 0 0 1 21 12c0 1-3 6-9 6-.314 0-.62-.014-.918-.04M5 19 19 5m-4 7a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                            </span>
                        </div>
                    </div>
                    <div class="mb-4 company-name">
                        <label class="form-label">اسم الشركة</label>
                        <input type="text" name="company_name" class="form-control shadow-none">
                    </div>

                    <!-- acc type  -->
                    <div class="mb-4">
                        <h4 class="text-light mb-3">نوع الحساب</h4>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="type" id="inlineRadio1"
                                   onclick="showInput(1)"  value="1">
                            <label class="form-check-label" for="inlineRadio1">حساب فرد</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="type" id="inlineRadio2"
                                   onclick="showInput(0)" value="2">
                            <label class="form-check-label" for="inlineRadio2">منشأه فرديه</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="type" id="inlineRadio2"
                                   onclick="showInput(1)"  value="3">
                            <label class="form-check-label" for="inlineRadio2">شركه توصيل</label>
                        </div>
                    </div>

                    <!-- agree  -->

                    <div class="form-check mb-5">
                        <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                        <label class="form-check-label" for="flexCheckDefault">
                            بمجرد انشاء الحساب . انا اوافق علي جميع الشروط والاحكام التابعه لسياسه الخصوصيه بالموقع
                        </label>
                    </div>

                    <button type="submit" class="border-0 yellow-btn w-100">تسجيل الدخول</button>
                </form>

                <!-- line -->
                <div class="line position-relative">
                    <span class="l"></span>
                    <span class="text">او تسجيل الدخول عبر</span>
                </div>

                <!-- log with  -->
                <div class="py-5 log-with">
                    <a href="#" class="w-100 d-block py-2 text-center text-secondary rounded-5 my-4">
                        <span class="me-2">انشاء حساب عبر الفيسبوك</span>
                        <img src="{{ asset('assets/images/face.png') }}" width="25" alt="">
                    </a>
                    <a href="#" class="w-100 d-block py-2 text-center text-secondary rounded-5 my-4">
                        <span class="me-2">انشاء حساب عبر جوجل</span>
                        <img src="{{ asset('assets/images/google.png') }}" width="25" alt="">
                    </a>
                    <a href="#" class="w-100 d-block py-2 text-center text-secondary rounded-5 my-4">
                        <span class="me-2">انشاء حساب عبر تويتر</span>
                        <img src="{{ asset('assets/images/twitter.png') }}" width="25" alt="">
                    </a>
                </div>
                <p class="create-account text-center mb-5"> لديك حساب ؟<a href="{{route('login')}}">تسجيل دخول</a></p>
            </div>

        </div>
    </section>

    @section('script')
        <script>
            $(document).ready(function(){
                // عند النقر على زر القائمة المنسدلة
                $('.special-drop-button').click(function(){
                    $('.special-drop-content').toggle();
                });

                // عند النقر على عنصر من القائمة المنسدلة
                $('.special-drop-item').click(function(){
                    var newText = $(this).data('text');
                    var newImg = $(this).data('img');

                    // تحديث الصورة والنص في الزر
                    $('#special-selected-image').attr('src', newImg);
                    $('#special-selected-text').text(newText);
                    console.log(newText)
                    $('#pre').val(newText)

                    // تحديث قيمة الحقل النصي المخفي
                    $('.special-get-num').val(newText);

                    // إخفاء القائمة المنسدلة بعد الاختيار
                    $('.special-drop-content').hide();
                });

                // إخفاء القائمة المنسدلة عند النقر في أي مكان آخر في الصفحة
                $(document).click(function(event) {
                    if (!$(event.target).closest('.special-drop-select').length) {
                        $('.special-drop-content').hide();
                    }
                });
            });
        </script>


    @endsection
    <!-- end login form  -->
@stop
