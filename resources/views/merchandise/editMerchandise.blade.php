<!-- 檔案位置：resources/views/merchandise/editMerchandise.blade.php -->
@extends("layout.master")
@section("title", $title)
@section("content")
    <div class="container">
        <h1>{{ $title }}</h1>
        <!-- 錯誤訊息模板 -->
        @include("components.validationErrorMessage")

        <form 
            action="/merchandise/{{ $merchandise->id }}"
            method="post"
            enctype="multipart/form-data"
        >
            {{-- 隱藏方法欄位 --}}
            {{ method_field('PUT') }}
            <label>
                商品狀態：
                <select name="status">
                    <option value="C" @if(old('status', $merchandise->status)=="C") selected @endif>
                        建立中
                    </option>
                    <option value="S" @if(old('status', $merchandise->status)=="S") selected @endif>
                        販賣中
                    </option>
                </select>
            </label>

            <label>
                商品名稱：
                <input 
                    type="text" 
                    name="name"
                    placeholder="商品名稱"
                    value="{{ old('name', $merchandise->name) }}"
                >
            </label>

            <label>
                商品英文名稱：
                <input 
                    type="text" 
                    name="name_en"
                    placeholder="商品英文名稱"
                    value="{{ old('name_en', $merchandise->name_en) }}"
                >
            </label>

            <label>
                商品介紹：
                <textarea name="intro" cols="30" rows="10">{{ old('intro', $merchandise->intro) }}</textarea>
            </label>

            <label>
                商品英文介紹：
                <textarea name="intro_en" cols="30" rows="10">{{ old('intro_en', $merchandise->intro_en) }}</textarea>
            </label>

            <label>
                商品照片：
                <input 
                    type="file"
                    name="photo"
                    placeholder="商品照片"
                >
                <!-- 若有照片載入，若無，則載入預設照片 -->
                <img src="{{ isset($merchandise->photo) ? $merchandise->photo : '/images/default-merchandise.png' }}">
            </label>
            
            <label>
                商品價格：
                <input 
                    type="text"
                    name="price"
                    value="{{ old('price', $merchandise->price) }}"
                    placeholder="商品價格"
                >
            </label>

            <label>
                商品剩餘數量：
                <input 
                    type="text"
                    name="count"
                    value="{{ old('count', $merchandise->count) }}"
                    placeholder="商品剩餘數量"
                >
            </label>
            <button type="submit" class="btn btn-default">更新商品資訊</button>
            {{ csrf_field() }}
        </form>
    </div>
@endsection