<?php

namespace ShopProject\Controllers\User;

use Pimple\Container;
use ShopProject\IEnvironment;
use Respect\Validation\Validator;
use ParagonIE\Halite\KeyFactory;
use ParagonIE\Halite\Password;
use ParagonIE\Halite\HiddenString;
use ShopProject\Models\DataCollection\User;
use ShopProject\Service\MailHelper;

class UserAuthController
{
	private $session;
	private $page;

	public function __construct(Container $container)
	{
		$this->session = $container['session'];
		$this->page = $container['page'];
	}

	public function signInPage($request, $response)
	{
		$this->page->routerRoot = IEnvironment::ROUTER_START;
		$this->page->session = $this->session->read(\session_id());
		$this->page->title = "登入";
		$this->page->layout("src/Views/layouts/default.php");
		$this->page->render("src/Views/auth/signIn.php");
	}

	public function signInProcess($request, $response)
	{
		$errorMsgPageForm = $this->validateSignIn($request);
		$errorMsgAccountPassword = $this->validateSignInPassword($request->email, $request->password);
		$errorMsg = \array_merge($errorMsgPageForm, $errorMsgAccountPassword);
		if (count($errorMsg)) {
			$this->page->routerRoot = IEnvironment::ROUTER_START;
			$this->page->session = $this->session->read(\session_id());
			$this->page->title = "登入";
			$this->page->layout("src/Views/layouts/default.php");
			$this->page->render(
				"src/Views/auth/signIn.php",
				[
					"errors" => $errorMsg,
					"email"  => $request->email,
				]
			);
			return $this->page;
		}
		// $this->session->setOptions(['ttl' => 40]) //設定session 存活40秒
		$this->session->open(IEnvironment::SESSION_PATH_NAME['LOGGIN']['PATH'], IEnvironment::SESSION_PATH_NAME['LOGGIN']['NAME']);
		$this->session->write(\session_id(), [
			'email' => $request->email,
			'token' => $this->generatorToken()
		]);
		$this->page->routerRoot = IEnvironment::ROUTER_START;
		$this->page->session = $this->session->read(\session_id());
		$this->page->title = "已登入";
		$this->page->layout("src/Views/layouts/default.php");
		$this->page->render("src/Views/auth/needLogin.php");
	}

	public function signOutProcess($request, $response)
	{
		$this->session->open(IEnvironment::SESSION_PATH_NAME['LOGGIN']['PATH'], IEnvironment::SESSION_PATH_NAME['LOGGIN']['NAME']);
		$this->session->destroy(\session_id());
		$this->page->routerRoot = IEnvironment::ROUTER_START;
		$this->page->session = $this->session->read(\session_id());
		$this->page->title = "登入";
		$this->page->layout("src/Views/layouts/default.php");
		$this->page->render("src/Views/auth/signIn.php");
	}

	public function signUpPage($request, $response)
	{
		$this->page->routerRoot = IEnvironment::ROUTER_START;
		$this->page->session = $this->session->read(\session_id());
		$this->page->title = "註冊";
		$this->page->layout("src/Views/layouts/default.php");
		$this->page->render("src/Views/auth/signUp.php");
	}

	public function signUpProcess($request, $response)
	{
		$errorMsg = $this->validateSignUp($request);
		if (count($errorMsg)) {
			$this->page->routerRoot = IEnvironment::ROUTER_START;
			$this->page->session = $this->session->read(\session_id());
			$this->page->title = "註冊";
			$this->page->layout("src/Views/layouts/default.php");
			$this->page->render(
				"src/Views/auth/signUp.php",
				[
					'errors'                => $errorMsg,
					'nickname'              => $request->nickname,
					'email'                 => $request->email,
					'password'              => $request->password,
					'password_confirmation' => $request->password_confirmation,
					'type'                  => $request->type
				]
			);
			return $this->page;
		}

		try {
			$keyConfig = dirname($_SERVER['DOCUMENT_ROOT']) . "/phpWarehouse/" . IEnvironment::PROJECT_NAME . "/config/key.ini";
			$key = KeyFactory::loadEncryptionKey($keyConfig);
			$input = $request->params();
			$input['password'] = Password::hash(new HiddenString($input['password']), $key);
			User::create($input);

			$mailer = new MailHelper;
			$content = $mailer->customizedContent(
				"src/Views/email/signUpEmailNotification.php",
				[
					"nickname" => $request->nickname,
				]
			);
			$mailer->send("rick1870@ares.com.tw", $request->email, "註冊確認", $content);
		} catch (\Exception $e) {
			$error[] = $e->getCode();
			$error[] = $e->getMessage();
			$this->page->routerRoot = IEnvironment::ROUTER_START;
			$this->page->session = $this->session->read(\session_id());
			$this->page->title = "註冊";
			$this->page->layout("src/Views/layouts/default.php");
			$this->page->render(
				"src/Views/auth/signUp.php",
				[
					'errors'                => $error,
					'nickname'              => $request->nickname,
					'email'                 => $request->email,
					'password'              => $request->password,
					'password_confirmation' => $request->password_confirmation,
					'type'                  => $request->type
				]
			);
			return $this->page;
		}

		$this->page->routerRoot = IEnvironment::ROUTER_START;
		$this->page->session = $this->session->read(\session_id());
		$this->page->title = "登入";
		$this->page->layout("src/Views/layouts/default.php");
		$this->page->render("src/Views/auth/signIn.php", []);
		return $this->page;
	}

	public function signStatus($reqeust, $response)
	{
		$this->session->open(IEnvironment::SESSION_PATH_NAME['LOGGIN']['PATH'], IEnvironment::SESSION_PATH_NAME['LOGGIN']['NAME']);
		$userId = $this->session->read(\session_id());
		$this->page->routerRoot = IEnvironment::ROUTER_START;
		$this->page->session = $this->session->read(\session_id());
		$this->page->title = "除錯";
		$this->page->data = $userId;
		$this->page->layout("src/Views/layouts/default.php");
		$this->page->render("src/Views/components/debug.php");
		return $this->page;
	}

	private function generatorToken(): string
	{
		$dataSet = array_merge(range('a', 'z'), range('a', 'z'), range('A', 'Z'), range('A', 'Z'), range(0, 9), range(0, 9), array_fill(0, 4, '_'));
		shuffle($dataSet);
		return implode('', array_slice($dataSet, 0, 20));
	}

	private function validateSignIn($request)
	{
		$errorMsg = [];
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
		if (!Validator::stringType()->length(6, null)->validate($request->password)) {
			$errorMsg[] = "密碼 最少需要 6 個字元數";
		}
		return $errorMsg;
	}

	public function validateSignInPassword($id, $password)
	{
		$keyConfig = dirname($_SERVER['DOCUMENT_ROOT']) . "/phpWarehouse/" . IEnvironment::PROJECT_NAME . "/config/key.ini";
		$key = KeyFactory::loadEncryptionKey($keyConfig);
		$dbResponse = User::where('email', $id)->select('email', 'password')->first();

		$errorMsg = [];
		if (
			empty($dbResponse) or
			!Password::verify(new HiddenString($password), $dbResponse->password, $key)
		) {
			$errorMsg[] = "帳號 密碼錯誤";
		}
		return $errorMsg;
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
		if (!empty(User::where('email', $request->email)->first())) {
			$errorMsg[] = "電子信箱 已被註冊";
		}
		return $errorMsg;
	}
}
