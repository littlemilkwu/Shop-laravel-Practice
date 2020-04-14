<?php 
// 檔案位置：app/Entity/Transaction.php
namespace App\Entity;
use Illuminate\Database\Eloquent\Model;
class Transaction extends Model{
    // 資料表名稱
    protected $table = "transaction";

    // 主鍵
    protected $primaryKey = "id";

    // 可變更名稱
    protected $fillable = [
        "user_id",
        "merchandise_id",
        "price",
        "buy_count",
        "total_price",
    ];

    // 設定與 Merchandise 資料表的關聯
    public function Merchandise(){
        return $this->hasOne("App\Entity\Merchandise", "id", "merchandise_id");
    }
}
?>