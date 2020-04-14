<!--檔案位置：resources/views/auth/signIn.blade.php-->
@extends("layout.master")

@section("title", $title)

@section("content")
<div>
    <h1>{{ $title }}</h1>
    
    <!-- 錯誤訊息 -->
    @include("components.validationErrorMessage")

    <form action="/user/auth/sign-in" method="post">
            <label>
                Eamil：
                <input type="email" name="email" placeholder="Email" value="{{ old('email') }}">
            </label>

            <label>
                密碼：
                <input type="password" name="password" placeholder="密碼">
            </label>

            {{ csrf_field() }}
            <button type="submit">登入</button>
    </form>
</div>
@endsection