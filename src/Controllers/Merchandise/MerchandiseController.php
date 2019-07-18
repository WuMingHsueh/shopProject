<?php

namespace ShopProject\Controllers\Merchandise;

use Pimple\Container;
use Respect\Validation\Validator;
use ShopProject\IEnvironment;
use ShopProject\Models\DataCollection\Merchandise;
use stdClass;

class MerchandiseController
{
	private $session;
	private $page;

	public function __construct(Container $container)
	{
		$this->session = $container['session'];
		$this->page    = $container['page'];
	}

	public function merchandiseCreateProcess($request, $response)
	{
		$merchandiseData = [
			'status'          => 'C',
			'name'            => '',
			'name_en'         => '',
			'introduction'    => '',
			'introduction_en' => '',
			'photo'           => '',
			'price'           => 0,
			'remain_count'    => 0,
		];
		$merchandise = Merchandise::create($merchandiseData);
		return $response
			->redirect(IEnvironment::ROUTER_START . '/merchandise/' . $merchandise->id . '/edit')
			->send();
	}

	public function merchandiseItemEditPage($request, $response)
	{
		$merchandise = Merchandise::findOrFail($request->merchandiseId);
		// if (!is_null($merchandise->photo)) {
		// 	$merchandise->photo = urlencode($merchandise->photo);
		// }
		$this->page->routerRoot = IEnvironment::ROUTER_START;
		$this->page->session = $this->session->read(\session_id());
		$this->page->title = "修改商品";
		$this->page->csrfField = $this->generatorCsrfToken();
		$this->page->merchandise = $merchandise;
		$this->page->layout("src/Views/layouts/default.php");
		$this->page->render("src/Views/merchandise/editMerchandise.php", ['errors' => (array) $request->errors]);
	}

	public function merchandiseItemUpdateProcess($request, $response)
	{
		$merchandise = Merchandise::findOrFail($request->merchandiseId);
		$errorMsg = $this->validatorUpdate($request);
		if (count($errorMsg)) {
			$request->__set('errors', $errorMsg);
			return $this->merchandiseItemEditPage($request, $response);
		}
	}

	private function generatorCsrfToken(): string
	{
		$token = bin2hex(random_bytes(32));
		$this->session->open(IEnvironment::SESSION_PATH_NAME['CSRF']['PATH'], IEnvironment::SESSION_PATH_NAME['CSRF']['NAME']);
		$this->session->write(\session_id(), $token);
		return $token;
	}

	public function validatorUpdate($request)
	{
		$errorMsg = [];
		if (!Validator::notBlank()->validate($request->status)) {
			$errorMsg[] = "狀態 不能為空";
		}
		if (!Validator::in(['C', 'S'])->validate($request->status)) {
			$errorMsg[] = "狀態必須為 C 或是 S";
		}
		if (!Validator::notBlank()->validate($request->name)) {
			$errorMsg[] = "商品名稱 不能為空";
		}
		if (!Validator::stringType()->length(null, 80)->validate($request->name)) {
			$errorMsg[] = "商品名稱 不能多於 80 個字元數";
		}
		if (!Validator::notBlank()->validate($request->name_en)) {
			$errorMsg[] = "商品英文名稱 不能為空";
		}
		if (!Validator::stringType()->length(null, 80)->validate($request->name_en)) {
			$errorMsg[] = "商品英文名稱 不能多於 80 個字元數";
		}
		if (!Validator::notBlank()->validate($request->introduction)) {
			$errorMsg[] = "商品介紹 不能為空";
		}
		if (!Validator::stringType()->length(null, 2000)->validate($request->introduction)) {
			$errorMsg[] = "商品介紹 不能多於 2000 個字元數";
		}
		if (!Validator::notBlank()->validate($request->introduction_en)) {
			$errorMsg[] = "商品英文介紹 不能為空";
		}
		if (!Validator::stringType()->length(null, 2000)->validate($request->introduction_en)) {
			$errorMsg[] = "商品英文介紹 不能多於 2000 個字元數";
		}
		if (!Validator::notBlank()->validate($request->price) and !Validator::in(['0', '0.0', 0])->validate($request->price)) {
			$errorMsg[] = "價格 不能為空";
		}
		if (!Validator::intVal()->validate($request->price)) {
			$errorMsg[] = "價格 必須為整數";
		}
		if (!Validator::intVal()->min(0)->validate($request->price)) {
			$errorMsg[] = "價格 必須為大於等於0";
		}
		if (!Validator::notBlank()->validate($request->remain_count) and !Validator::in(['0', '0.0', 0])->validate($request->remain_count)) {
			$errorMsg[] = "商品剩餘數量 不能為空";
		}
		if (!Validator::intVal()->validate($request->remain_count)) {
			$errorMsg[] = "商品剩餘數量 必須為整數";
		}
		if (!Validator::intVal()->min(0)->validate($request->remain_count)) {
			$errorMsg[] = "商品剩餘數量 必須為大於等於0";
		}
		if (!Validator::image()->validate($request->files()['photo']['tmp_name'])) {
			$errorMsg[] = "必須上傳檔案";
		}
		if (!Validator::size(null, '10MB')->validate($request->files()['photo']['tmp_name'])) {
			$errorMsg[] = "照片大小不能超過10MB";
		}
		return $errorMsg;
	}
}
