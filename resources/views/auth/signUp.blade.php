<!--檔案位置：resources/views/auth/signUp.blade.php-->

@extends("layout.master")

@section("title", $title)

@section("content")
    <div class="container">
        <h1>{{ $title }}</h1>

        <!-- 錯誤訊息組件 -->
        @include('components.validationErrorMessage')

        <form action="/user/auth/sign-up" method="post">
            <label>
                暱稱：
                <input type="text" name="nick" placeholder="暱稱" value="{{ old('nick') }}">
            </label>

            <label>
                Email：
                <input type="email" name="email" placeholder="Email" value="{{ old('email') }}">
            </label>

            <label>
                密碼：
                <input type="password" name="password" placeholder="密碼">
            </label>

            <label>
                確認密碼：
                <input type="password" name="password_confirm" placeholder="確認密碼">
            </label>

            <label>
                帳號身份：
                <select name="type">
                    <option value="G" @if (old('type') == "G") selected @endif>General</option>
                    <option value="A" @if (old('type') == "A") selected @endif>Auth</option>
                </select>
            </label>
            {!! csrf_field() !!}
            <button type="submit">註冊</button>
        </form>
    </div>
@endsection