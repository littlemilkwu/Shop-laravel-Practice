<!-- 檔案位置：resources/views/transaction/listUserTransaction.blade.php -->
@extends("layout.master")

@section("title", $title)

@section("content")
    <div class="container">
        <h1>{{ $title }}</h1>
        {{-- 錯誤訊息模板 --}}
        @include("components.validationErrorMessage")

        <table class="table">
            <tr>
                <th>商品名稱</th>
                <th>圖片</th>
                <th>單價</th>
                <th>數量</th>
                <th>總金額</th>
                <th>購買時間</th>
            </tr>
            @foreach($TransactionPaginate as $Transaction)
                <tr>
                    <td>
                        <a href="/merchandise/{{ $Transaction->merchandise_id }}">
                            {{ $Transaction->Merchandise->name }}
                        </a>
                    </td>
                    <td>
                        <img src="{{ isset($Transaction->Merchandise->photo) ? $Transaction->Merchandise->photo : '/assets/images/default-merchandise.png' }}">
                    </td>
                    <td>{{ $Transaction->price }}</td>
                    <td>{{ $Transaction->buy_count }}</td>
                    <td>{{ $Transaction->total_price }}</td>
                    <td>{{ $Transaction->created_at }}</td>
                </tr>
            @endforeach
        </table>
        
        <!-- 換頁按鈕 -->
        <div class="row justify-content-center">
            <div class="col-6">
                {{ $TransactionPaginate->links() }}
            </div>
        </div>
    </div>
@endsection