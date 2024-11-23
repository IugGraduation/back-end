

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
                <h1 class="gray black text-center">تسجيل الدخول لحسابك</h1>
{{--                @include('web.errors')--}}
                <form class="my-5" method="post" action="{{ route('login') }}" >
                    @csrf
                    <div class="mb-4">
                        <label for="exampleInputEmail1" class="form-label">البريد الالكتروني</label>
                        <input type="email" name="email" class="form-control shadow-none" id="exampleInputEmail1">
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
                        <img src="{{asset('assets/images/face.png')}}" width="25" alt="">
                    </a>
                    <a href="{{route('authGoogle')}}" class="w-100 d-block py-2 text-center text-secondary rounded-5 my-4">
                        <span class="me-2">انشاء حساب عبر جوجل</span>
                        <img src="{{asset('assets/images/google.png')}}" width="25" alt="">
                    </a>
                    <a href="#" class="w-100 d-block py-2 text-center text-secondary rounded-5 my-4">
                        <span class="me-2">انشاء حساب عبر تويتر</span>
                        <img src="{{asset('assets/images/twitter.png')}}" width="25" alt="">
                    </a>
                </div>
            </div>
        </div>
    </section>


