<?php 
// 檔案位置：app/Entity/Merchandise.php
namespace App\Entity;
use Illuminate\Database\Eloquent\Model;
class Merchandise extends Model{
    // 資料表名稱
    protected $table = "merchandise";

    // 主鍵
    protected $primaryKey = "id";

    // 可更改內容
    protected $fillable = [
        "status",
        "name",
        "name_en",
        "intro",
        "intro_en",
        "photo",
        "price",
        "count",
    ];
}
?>