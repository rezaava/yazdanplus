@extends('layout.master')
@section('title')
| پروفایل
@endsection
<style>
    .copy-text {
    padding: 5px;
	background: #fff;
	border: 1px solid #ddd;
	border-radius: 10px;
	display: flex;
}
.copy-text input.text {
	color: #555;
	border: none;
	outline: none;
}
.copy-text button {
	padding: 10px;
	background:#e30613;
	color: #fff;
	font-size: 18px;
	border: none;
	outline: none;
	border-radius: 10px;
	cursor: pointer;
}

.copy-text button:active {
	background: #e30613;
}
.copy-text button:before {
	content: "کپی شد!";
	position: absolute;
	top: -45px;
	left: 0px;
	background: #e30613;
	padding: 8px 10px;
	border-radius: 20px;
	font-size: 15px;
	display: none;
}
.copy-text button:after {
	content: "";
	position: absolute;
	top: -20px;
	left: 25px;
	width: 10px;
	height: 10px;
	background: #e30613;
	transform: rotate(45deg);
	display: none;
}
.copy-text.active button:before,
.copy-text.active button:after {
	display: block;
}
</style>
@section('content')
<div class="page-content-wrapper">
    <br><br>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">
                <!-- Profile Wrapper-->
                <div class="profile-wrapper-area py-3">
                    <!-- User Information-->
                    <div class="card user-info-card">
                        <div class="card-body p-4 d-flex align-items-center">
                            <div class="user-profile me-3"><img @if ($profile) src='{{ asset($profile->address) }}' @else src='{{ asset('user_profile/user.webp') }}' @endif  alt="profile"
                                    style="width: 80px;height:80px;">
                            </div>
                            <div class="user-info">
                                {{-- <p class="mb-0">@designing-world</p> --}}
                                @if ($user->name && $user->family)
                                <h5 class="mb-0">{{ $user->name }} {{ $user->family }}</h5>
                                @else
                                <h5 class="mb-0">بدون نام</h5>
                                @endif
                                <br>
                                <p>{{ $user->mobile }}</p>
                            </div>
                        </div>
                    </div>
                    <!-- User Meta Data-->
                    <div class="card user-data-card">
                        @if($errors == '[["success"]]')
                        <center>
                            <br>
                            <div class="text-success">با موفقیت پروفایل تکمیل شد!</div>
                        </center>
                        @endif
                        <div class="card-body">
                            <div class="single-profile-data d-flex align-items-center justify-content-between">
                                <div class="title d-flex align-items-center"><i class="fa-solid fa-user"></i><span> نام
                                    </span></div>
                                @if ($user->name)
                                <div class="data-content"> {{ $user->name }}</div>
                                @else
                                <div class="data-content"> </div>
                                @endif

                            </div>
                            <div class="single-profile-data d-flex align-items-center justify-content-between">
                                <div class="title d-flex align-items-center"><i class="fa-solid fa-user"></i><span>نام
                                        خانوادگی</span></div>
                                @if ($user->family)
                                <div class="data-content"> {{ $user->family }}</div>
                                @else
                                <div class="data-content"> </div>
                                @endif
                            </div>
                            <div class="single-profile-data d-flex align-items-center justify-content-between">
                                <div class="title d-flex align-items-center"><i class="fa-solid fa-address-card"></i><span>کدملی
                                    </span></div>
                                @if ($user->nationalcode)
                                <div class="data-content"> {{ $user->nationalcode }}</div>
                                @else
                                <div class="data-content"> </div>
                                @endif
                            </div>
                            <div class="single-profile-data d-flex align-items-center justify-content-between">
                                <div class="title d-flex align-items-center"><i class="fa-solid fa-calendar-alt"></i><span>تاریخ
                                        تولد
                                    </span></div>
                                @if ($user->date_of_birth)
                                <div class="data-content"> {{ $user->date_of_birth }}</div>
                                @else
                                <div class="data-content"> </div>
                                @endif
                            </div>
                            {{--  <div class="single-profile-data d-flex align-items-center justify-content-between">
                                <div class="title d-flex align-items-center"><i class="fa-solid fa-sitemap"></i><span>کدمعرف
                                    </span>
                                </div>
                                @if ($user->referrer_id)
                                <div class="data-content"> {{ $user['refferal'] }}</div>
                                @else
                                <div class="data-content"> 
                                    <a class="btn btn-theme w-100" style="font-size:12px;font-weight: 1000;" href="/referrer2">ثبت کاربر معرف</a>
                                </div>
                                @endif
                            </div>  --}}
                            
                            <!-- <div class="single-profile-data d-flex align-items-center justify-content-between">
                                <div class="title d-flex align-items-center"><i class="fa-solid fa-qrcode"></i><span>کدمعرفی
                                    </span></div>
                                    <div class="copy-text">
                                            <input type="text" class="text form-control" value='{{ asset("referrer_user/$user->referrer") }}' dir="ltr"/>
                                            <button><i class="fa fa-clone"></i></button>
                                        </div>
                            </div> -->
                            <!-- Edit Profile-->
                            
                           
                                <div class="edit-profile-btn mt-3"><a class="btn btn-theme w-100" href="/edit-profile"><i
                                    class="fa-solid fa-pen me-2"></i> تکمیل  پروفایل</a></div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>
<script>
    function card_collapse() {
        card_class = document.getElementById("card").classList;
        if (card_class.contains('collapse')) {
            card_class.remove('collapse');
        } else {
            card_class.add('collapse');
        }
    }
    let copyText = document.querySelector(".copy-text");
    copyText.querySelector("button").addEventListener("click", function () {
	let input = copyText.querySelector("input.text");
	input.select();
	document.execCommand("copy");
	copyText.classList.add("active");
	window.getSelection().removeAllRanges();
	setTimeout(function () {
		copyText.classList.remove("active");
	}, 2500);
});

</script>
@endsection