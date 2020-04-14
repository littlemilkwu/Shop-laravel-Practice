<!-- 檔案位置：resource/views/merchandise/listMerchandise.blade.php -->
@extends("layout.master")

@section("title", $title)

@section("content")
    <div class="container">
        <h1>{{ $title }}</h1>

        {{-- 錯誤訊息模板元件 --}}
        @include("components.validationErrorMessage")

        <table class="table">
            <tr>
                <th>名稱</th>
                <th>圖片</th>
                <th>介紹</th>
                <th>價格</th>
            </tr>
            @foreach($MerchandisePaginate as $Merchandise)
                <tr>
                    <td><a href="/merchandise/{{ $Merchandise->id }}">{{ $Merchandise->name }}</a></td>
                    <td><a href="/merchandise/{{ $Merchandise->id }}"><img src="{{ $Merchandise->photo }}" alt=""></a></td>
                    <td>{{ $Merchandise->intro }}</td>
                    <td>{{ $Merchandise->price }}</td>
                </tr>
            @endforeach
        </table>
        {{-- 分頁按鈕 --}}
        <div class="row justify-content-center">
            <div class="col-6">{{ $MerchandisePaginate->links() }}</div>
        </div>
    </div>
@endsection