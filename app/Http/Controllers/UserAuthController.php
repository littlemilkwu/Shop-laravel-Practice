<?php 
// 檔案位置：app/Http/Controllers/UserAuthController.php
// 命名空間
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Entity\User;    // User Eloquent
use Mail;       // 郵件
use Validator;  // 驗證器
use Hash;       // 雜湊
class UserAuthController extends Controller{
    // 註冊頁面
    public function signUpPage(){
        $binding = [
            "title" => "註冊"
        ];
        return view("auth.signUp", $binding);
    }

    // 註冊流程
    public function signUpProcess(){
        // 接收輸入資料
        $input = request()->all();
        
        // 驗證規則
        $rules = [
            // 暱稱
            'nick' => [
                'required',
                'max:30',
            ],
            // Email
            'email' => [
                'required',
                'max:100',
                'email',
            ],
            // 密碼
            'password' => [
                'required',
                'min:6',
                'same:password_confirm',
            ],
            // 密碼驗證
            'password_confirm' => [
                'required',
                'min:6',
            ],
            // 帳號類型
            'type' => [
                'required',
                'in:G,A',
            ],
        ];

        // 驗證資料
        $validator = Validator::make($input, $rules);

        if($validator->fails()){
            // 驗證錯誤
            return redirect('/user/auth/sign-up')->withErrors($validator)->withInput();
        }

        // 密碼加密
        $input['password'] = Hash::make($input['password']);

        // 資料庫新增
        $Users = User::create($input);

        // 寄送驗證信
        $mail_binding = [
            'nick' => $input['nick']
        ];
        Mail::send("email.signUpEmailNotification", $mail_binding, 
        function($mail) use ($input){
            // 收件人
            $mail->to($input['email']);
            // 寄件人
            $mail->from("Shop_laravel@laravel.com");
            // 內容主旨
            $mail->subject("恭喜註冊 Shop Laravel 成功");
        });

        // 重新導入登入頁面
        return redirect('/user/auth/sign-in');
    }

    // 登入頁面
    public function signInPage(){
        $binding = [
            "title" => "登入"
        ];
        return view("auth.signIn", $binding);
    }

    // 登入流程
    public function signInProcess(){
        $signInInput = request()->all();

        $rules = [
            'email' => [
                'required',
                'max:100',
                'email',
            ],
            'password' => [
                'required',
                'min:8',
            ],
        ];

        $validator = Validator::make($signInInput, $rules);
        if($validator->fails()){
            // 驗證錯誤
            return redirect('/user/auth/sign-in')->withErrors($validator)->withInput();
        }

        // 取得使用者資料
        $signInUser = User::where("email", "=", $signInInput['email'])->firstOrFail();

        // 確認密碼是否正確
        $is_password_correct = Hash::check($signInInput['password'], $signInUser->password);

        if(!$is_password_correct){
            // 密碼錯誤
            $error_message = [
                'msg' => "密碼錯誤",
            ];
            return redirect("/user/auth/sign-in")->withErrors($error_message)->withInput();
        }

        // 將使用者編號存進session
        session()->put('user_id', $signInUser->id);

        // 重新導向使用者瀏覽的上一頁，若無，則導向首頁
        return redirect()->intended("/");
    }

    public function signOut(){
        // 清除session中的登入狀態
        session()->forget("user_id");

        // 重新導向首頁
        return redirect("/");
    }
}
?>