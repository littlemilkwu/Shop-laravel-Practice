<?php 
// 檔案位置：app/Entity/User.php
namespace App\Entity;
use Illuminate\Database\Eloquent\Model;
class User extends Model{
    protected $table = "users";

    // 主鍵名稱
    protected $primaryKey = "id";

    // 可更改欄位
    protected $fillable = [
        "email",
        "password",
        "type",
        "nick",
    ];
}
?>