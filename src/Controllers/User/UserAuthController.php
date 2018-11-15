<?php 
namespace ShopProject\Controllers\User;

use ShopProject\IEnvironment;
use Respect\Validation\Validator;
use ParagonIE\Halite\KeyFactory;
use ParagonIE\Halite\Symmetric\Crypto;
use ParagonIE\Halite\HiddenString;
use ShopProject\Models\DataCollection\User;

class UserAuthController
{
    public function signUpPage($request, $service)
    {
        $service->routerRoot = IEnvironment::ROUTER_START;
        $service->title = "註冊";
        $service->layout("src/Views/layouts/default.php");
        $service->render("src/Views/auth/signUp.php");
        return $service;
    }
    
    public function signUpProcess($request, $service)
    {
        $errorMsg = $this->validateSignUp($request);
        if (count($errorMsg)) {
            $service->routerRoot = IEnvironment::ROUTER_START;
            $service->title = "註冊";
            $service->layout("src/Views/layouts/default.php");
            $service->render(
                "src/Views/auth/signUp.php",
                             ['errors'                => $errorMsg,
                              'nickname'              => $request->nickname,
                              'email'                 => $request->email,
                              'password'              => $request->password,
                              'password_confirmation' => $request->password_confirmation,
                              'type'                  => $request->type
                            ]
            );
            return $service;
            exit;
        }
        
        $keyConfig = dirname($_SERVER['DOCUMENT_ROOT']) . "/phpWarehouse/" . IEnvironment::PROJECT_NAME . "/config/key.ini";
        $key = KeyFactory::loadEncryptionKey($keyConfig);
        $input = $request->params();
        $input['password'] = Crypto::encrypt(new HiddenString($input['password']), $key);
        User::create($input);
        
        
        
        return $service;
    }

    private function validateSignUp($request)
    {
        $errorMsg = [];
        if (!Validator::notBlank()->validate($request->nickname)) {
            $errorMsg[] = "暱稱 不能為空";
        }
        if (!Validator::stringType()->length(null, 50)->validate($request->nickname)) {
            $errorMsg[] = "暱稱 不能多於 50 個字元數";
        }
        if (!Validator::notBlank()->validate($request->email)) {
            $errorMsg[] = "電子信箱 不能為空";
        }
        if (!Validator::email()->validate($request->email) and Validator::notBlank()->validate($request->email)) {
            $errorMsg[] = "電子信箱 格式不正確";
        }
        if (!Validator::stringType()->length(null, 150)->validate($request->email)) {
            $errorMsg[] = "電子信箱 不能多於 150 個字元數";
        }
        if (!Validator::notBlank()->validate($request->password)) {
            $errorMsg[] = "密碼 不能為空";
        }
        if (!Validator::notBlank()->validate($request->password_confirmation)) {
            $errorMsg[] = "確認密碼 不能為空";
        }
        if (!Validator::stringType()->length(6, null)->validate($request->password)) {
            $errorMsg[] = "密碼 最少需要 6 個字元數";
        }
        if (!Validator::stringType()->length(6, null)->validate($request->password_confirmation)) {
            $errorMsg[] = "確認密碼 最少需要 6 個字元數";
        }
        if ($request->password !== $request->password_confirmation) {
            $errorMsg[] = "密碼 與 確認密碼 須相同";
        }
        if (!Validator::notBlank()->validate($request->type)) {
            $errorMsg[] = "會員類型 不能為空";
        }
        if (!Validator::in(['G', 'A'])->validate($request->type)) {
            $errorMsg[] = "會員類型 必須是 G 或是 A";
        }
        return $errorMsg;
    }
}
