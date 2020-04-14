<?php 
// 檔案位置：app/Http/Controllers/TransactionController.php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Entity\User;            // User Eloquent
use App\Entity\Transaction;     // Transaction Eloquent

class TransactionController extends Controller{
    public function transactionListPage(){
        // 取得用戶資料
        $user_id = session()->get("user_id");
        $User = User::findOrFail($user_id);

        // 每頁資料量
        $row_per_page = 10;

        // 查詢用戶交易資料
        $TransactionPaginate = Transaction::where("user_id", "=", $User->id)
                                    ->orderBy('created_at', "desc")
                                    ->with("Merchandise")
                                    ->paginate($row_per_page);

        foreach($TransactionPaginate as $Transaction){
            if(!is_null($Transaction->Merchandise->photo)){
                // 轉換圖片網址
                $Transaction->Merchandise->photo = url($Transaction->Merchandise->photo);
            }
        }

        $binding = [
            'title' => "交易資料",
            'TransactionPaginate' => $TransactionPaginate,
        ];

        return view("transaction.listUserTransaction", $binding);
    }
}
?>