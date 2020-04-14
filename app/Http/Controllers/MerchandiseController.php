<?php 
// 檔案位置：app/Http/Controllers/MerchandiseController.php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Entity\Merchandise;     // Merchandise Eloquent
use App\Entity\User;            // User Eloquent
use App\Entity\Transaction;     // Transaction Eloquent
use Illuminate\Http\Request;
use Validator;  // 驗證器
use Image;
use DB;
use Exception;
class MerchandiseController extends Controller{
    // 新增商品
    public function merchandiseCreateProcess(){
        $merchandise_data = [
            "status" => "C",        // 建立中
            "name" => "",           // 商品名稱
            "name_en" => "",        // 商品英文名稱
            "intro" => "",          // 商品介紹
            "intro_en" => "",       // 商品英文介紹
            "photo" => null,        // 商品照片
            "price" => 0,           // 商品價格
            "count" => 0,           // 商品剩餘數量
        ];
        $Merchandise = Merchandise::create($merchandise_data);

        // 重新導向商品編輯頁面
        return redirect('/merchandise/'.$Merchandise->id.'/edit');
    }

    // 編輯商品
    public function merchandiseItemEditPage($m_id){
        // 撈取商品資料
        $merchandise = Merchandise::findOrfail($m_id);

        if(!is_null($merchandise->photo)){
            // 若有圖片路徑，將其轉換成http開頭
            $merchandise->photo = url($merchandise->photo);
        }

        $binding = [
            "title" => "編輯商品",
            "merchandise" => $merchandise,
        ];

        // 載入編輯頁面的view內容
        return view("merchandise.editMerchandise", $binding);
    }

    // 更新商品資訊
    public function merchandiseUpdateProcess($m_id){
        // 撈取資料庫欄位
        $merchandise = Merchandise::findOrFail($m_id);

        // 接收表單資料
        $input = request()->all();

        // 驗證資料
        $rules = [
            'status' => [
                'required',
                'in:C,S',
            ],
            'name' => [
                'required',
                'max:80',
            ],
            'name_en' => [
                'required',
                'max:80',
            ],
            'intro' => [
                'required',
                'max:2000',
            ],
            'intro_en' => [
                'required',
                'max:2000',
            ],
            'photo' => [
                'file',
                'image',
                'max:10250',    // 10 MB
            ],
            'price' => [
                'required',
                'integer',
                'min:0',
            ],
            'count' => [
                'required',
                'integer',
                'min:0',
            ],
        ];

        $validator = Validator::make($input, $rules);
        if($validator->fails()){
            // 資料驗證錯誤
            return redirect('/merchandise/'.$m_id.'/edit')
                    ->withError($validator)
                    ->withInput();
        }

        if(isset($input['photo'])){
            // 有上傳圖片
            $photo = $input['photo'];
            // 檔案副檔名
            $file_extension = $photo->getClientOriginalExtension();
            // 產生自訂隨機檔案名稱
            $file_name = uniqid().".".$file_extension;
            // 儲存檔案路徑
            $file_relative_path = "images/merchandise". $file_name;
            $file_path = public_path($file_relative_path);
            // 裁切圖片
            $image = Image::make($photo)->fit(450, 300)->save($file_path);
            // 資料庫儲存圖片路徑
            $input['photo'] = $file_relative_path;
        }

        $merchandise->update($input);

        // 重新導向到商品編輯頁
        return redirect('/merchandise/manage');
    }

    // 商品管理清單
    public function merchandiseManageListPage(){
        // 每頁資料量
        $row_per_page = 10;

        // 撈取分頁資料
        $merchandisePaginate = Merchandise::OrderBy("created_at", 'desc')
            ->paginate($row_per_page);
        // 轉換圖片網誌
        foreach($merchandisePaginate as $merchandise){
            if(!is_null($merchandise->photo)){
                $merchandise->photo = url($merchandise->photo);
            }
        }
        $binding = [
            'title' => '商品管理清單',
            'merchandisePaginate' => $merchandisePaginate,
        ];

        return view("merchandise.manageMerchandise", $binding);
    }
    public function merchandiseListPage(){
        // 每頁資料量
        $row_per_page = 10;

        // 撈取商品分頁資料
        $MerchandisePaginate = Merchandise::OrderBy('updated_at', 'desc')
            ->where('status', 'S')
            ->paginate($row_per_page);

        // 設定商品圖片路徑
        foreach($MerchandisePaginate as $Merchandise){
            if(!is_null($Merchandise->photo)){
                // 設定商品照片網址
                    $Merchandise->photo = url($Merchandise->photo);
            }
        }

        $binding = [
            'title' => "商品清單",
            'MerchandisePaginate' => $MerchandisePaginate,
        ];
        return view("merchandise.listMerchandise", $binding);
    }
    public function merchandiseItemPage($m_id){
        $Merchandise = Merchandise::findOrFail($m_id);
        if(!is_null($Merchandise->photo)){
            // 轉換圖片網址成url網址
            $Merchandise->photo = url($Merchandise->photo);
        }
        $binding = [
            "title" => "商品頁",
            "Merchandise" => $Merchandise,
        ];
        return view("merchandise.showMerchandise", $binding);
    }

    public function merchandiseItemBuyProcess(Request $request, $m_id){
        $input = $request->all();

        // 驗證規則
        $rules = [
            'buy_count' => [
                'required',
                'integer',
                'min:1',
            ]
        ];

        $validator = Validator::make($input, $rules);

        if($validator->fails()){
            // 資料驗證錯誤
            return redirect("/merchandise/".$m_id)
                ->withErrors($validator)
                ->withInput();
        }
        
        try{
            // 取得使用者資料
            $user_id = session()->get("user_id");
            $User = User::findOrFail($user_id);

            // 交易開始
            DB::beginTransaction();

            // 取得商品資料
            $Merchandise = Merchandise::findOrFail($m_id);

            // 購買數量
            $buy_count = $input['buy_count'];
            
            // 購買後剩餘數量
            $remain_count = ($Merchandise->count) - $buy_count;

            if($remain_count < 0){
                // 超出可購買數量
                throw new Exception("商品數量不足，無法購買");
            }

            // 儲存購買數量的變動
            $Merchandise->count = $remain_count;
            $Merchandise->save();

            // 總金額計算
            $total_cost = $buy_count * ($Merchandise->price);

            // 建立交易資料
            $transaction_data = [
                'user_id' => $User->id,
                'merchandise_id' => $Merchandise->id,
                'price' => ($Merchandise->price),
                'buy_count' => $buy_count,
                'total_price' => $total_cost,
            ];
            Transaction::create($transaction_data);

            // 交易結束
            DB::commit();

            // 回傳購物成功訊息
            $message = [
                'msg' => [
                    '購物成功',
                ],
            ];

            return redirect()
                ->to('/merchandise/'.$Merchandise->id)
                ->withErrors($message);

        }catch(Exception $exception){
            // 恢復先前交易狀態
            DB::rollBack();

            // 回傳錯誤訊息
            $error_message = [
                'msg' => [
                    $exception->getMessage(),
                ],
            ];
            return redirect()
                ->to('/merchandise')
                ->withErrors($error_message)
                ->withInput();
        }

    }
}
?>