@extends("layout.master")

@section("title", $title)

@section("content")
    <div class="container">
        <h1>{{ $title }}</h1>
        <!-- 錯誤訊息模板 -->
        @include("components.validationErrorMessage")

        <table class="table">
            <tr>
                <th>名稱</th>
                <td>{{ $Merchandise->name }}</td>
            </tr>
            <tr>
                <th>照片</th>
                <td><img src="{{ isset($Merchandise->photo) ? $Merchandise->photo : '/images/default-merchandise.png' }}"></td>
            </tr>
            <tr>
                <th>價格</th>
                <td>{{ $Merchandise->price }}</td>
            </tr>
            <tr>
                <th>剩餘數量</th>
                <td>{{ $Merchandise->count }}</td>
            </tr>
            <tr>
                <th>介紹</th>
                <td>{{ $Merchandise->intro }}</td>
            </tr>
            <tr>
                <td colspan="2">
                    <form 
                        action="/merchandise/{{ $Merchandise->id }}/buy" 
                        method="post"
                    >
                        <input type="number" name="buy_count" max="{{ $Merchandise->count }}">
                        <button type="submit">購買</button>
                        {{ csrf_field() }}
                    </form>
                </td>
            </tr>
        </table>
    </div>
@endsection