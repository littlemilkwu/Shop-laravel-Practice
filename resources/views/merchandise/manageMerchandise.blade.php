<!-- 檔案位置：resources/view/merchandise/manageMerchandise.blade.php -->
@extends("layout.master")
@section("title", $title)
@section("content")
    <div class="container">
        <h1>{{ $title }}</h1>

        <!-- 錯誤訊息模板 -->
        @include("components.validationErrorMessage")
        <div class="row justify-content-start">
            <div class="col-3">
                <a href="/merchandise/create">新增</a>
            </div>
        </div>

        <table class="table">
            <tr>
                <th>編號</th>
                <th>名稱</th>
                <th>圖片</th>
                <th>狀態</th>
                <th>價格</th>
                <th>剩餘數量</th>
                <th>編輯</th>
            </tr>
            @foreach($merchandisePaginate as $merchandise)
                <tr>
                    <td>{{ $merchandise->id }}</td>
                    <td>{{ $merchandise->name }}</td>
                    <td>
                        <img src="{{ isset($merchandise->photo) ? $merchandise->photo : '/images/default-merchandise.png' }}">
                    </td>
                    <td>{{ $merchandise->status }}</td>
                    <td>{{ $merchandise->price }}</td>
                    <td>{{ $merchandise->count }}</td>
                    <td>
                        <a href="/merchandise/{{ $merchandise->id }}/edit">編輯</a>
                    </td>
                </tr>
            @endforeach
        </table>
        

        <div class="row justify-content-center">
            <div class="col-6 ">{{ $merchandisePaginate->links() }}</div>
        </div>
    </div>
@endsection