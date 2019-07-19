<?php

namespace ShopProject\Controllers\Merchandise;

use Exception;
use Pimple\Container;
use Respect\Validation\Validator;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Database\Capsule\Manager as DB;
use ShopProject\IEnvironment;
use ShopProject\Models\DataCollection\Merchandise;
use ShopProject\Models\DataCollection\User;
use ShopProject\Models\DataCollection\Transaction;

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

	public function merchandiseItemBuyProcess($request, $response)
	{
		$errorMsg = $this->validatorItemBuyProcess($request);
		if (count($errorMsg)) {
			$request->__set('errors', $errorMsg);
			return $this->merchandiseItemPage($request, $response);
		}
		try {
			$buyCount = $request->buyCount;
			$this->session->open(IEnvironment::SESSION_PATH_NAME['LOGGIN']['PATH'], IEnvironment::SESSION_PATH_NAME['LOGGIN']['NAME']);
			$emailUserSession = $this->session->read(\session_id())['email'];
			$user = User::where('email', $emailUserSession)->get()[0];
			// 交易開始
			DB::beginTransaction();
			$merchandise = Merchandise::findOrFail($request->merchandiseId);
			$remainCountAfterBuy = $merchandise->remain_count - $buyCount;
			if ($remainCountAfterBuy < 0) {
				throw new Exception("商品數量不足 無法購買");
			}
			$merchandise->remain_count = $remainCountAfterBuy;
			$merchandise->save();

			$totalPrice = $buyCount * $merchandise->price;
			Transaction::create([
				'user_id'        => $user->id,
				'merchandise_id' => $merchandise->id,
				'price'          => $merchandise->price,
				'buy_count'      => $buyCount,
				'total_price'    => $totalPrice
			]);
			DB::commit();
			// 交易結束
			$request->__set('errors', ['購買成功']);
			return $this->merchandiseItemPage($request, $response);
		} catch (\Exception $e) {
			DB::rollBack();
			$request->__set('errors', [$e->getMessage()]);
			return $this->merchandiseItemPage($request, $response);
		}
	}

	public function merchandiseItemEditPage($request, $response)
	{
		$merchandise = Merchandise::findOrFail($request->merchandiseId);
		$merchandise->photo = $this->imageDataUrlShow($merchandise->photo);
		$this->page->routerRoot = IEnvironment::ROUTER_START;
		$this->session->open(IEnvironment::SESSION_PATH_NAME['LOGGIN']['PATH'], IEnvironment::SESSION_PATH_NAME['LOGGIN']['NAME']);
		$this->page->session = $this->session->read(\session_id());
		$this->page->title = "修改商品";
		$this->page->csrfField = $this->generatorCsrfToken();
		$this->page->merchandise = $merchandise;
		$this->page->layout("src/Views/layouts/default.php");
		$this->page->render("src/Views/merchandise/editMerchandise.php", ['errors' => (array) $request->errors]);
	}

	public function merchandiseItemPage($request, $response)
	{
		$merchandiseId = $request->merchandiseId;
		$merchandise = Merchandise::findOrFail($merchandiseId);
		$merchandise->photo = $this->imageDataUrlShow($merchandise->photo);
		$this->page->routerRoot = IEnvironment::ROUTER_START;
		$this->page->title = "商品頁";
		$this->session->open(IEnvironment::SESSION_PATH_NAME['LOGGIN']['PATH'], IEnvironment::SESSION_PATH_NAME['LOGGIN']['NAME']);
		$this->page->session = $this->session->read(\session_id());
		$this->page->layout("src/Views/layouts/default.php");
		$this->page->render("src/Views/merchandise/showMerchandise.php", [
			'merchandise' => $merchandise,
			'csrfField'   => $this->generatorCsrfToken(),
			'errors'      => $request->errors ?? []
		]);
	}

	public function merchandiseItemUpdateProcess($request, $response)
	{
		$merchandise = Merchandise::findOrFail($request->merchandiseId);
		$errorMsg = $this->validatorItemUpdateProcess($request);
		if (count($errorMsg)) {
			$request->__set('errors', $errorMsg);
			return $this->merchandiseItemEditPage($request, $response);
		}
		$fileExtension = pathinfo($request->files()['photo']['name'], PATHINFO_EXTENSION);
		$fileName = \bin2hex(\random_bytes(8)) . '.' . $fileExtension;
		$uploadPath = dirname(empty($_SERVER['DOCUMENT_ROOT']) ? (IEnvironment::DOCUMENT_ROOT) : $_SERVER['DOCUMENT_ROOT']) . "/phpWarehouse/" . IEnvironment::PROJECT_NAME . "/upload/";
		$uploadPath .= $fileName;
		Image::make($request->files()['photo']['tmp_name'])->fit(450, 300)->save($uploadPath);
		$merchandise->update([
			'status'          => $request->status,
			'name'            => $request->name,
			'name_en'         => $request->name_en,
			'introduction'    => $request->introduction,
			'introduction_en' => $request->introduction_en,
			'photo'           => $fileName,
			'price'           => $request->price,
			'remain_count'    => $request->remain_count
		]);
		return $response->redirect(IEnvironment::ROUTER_START . '/merchandise/' . $merchandise->id . '/edit')->send();
	}

	public function merchandiseManageListPage($request, $response)
	{
		$currentPage = $request->paramsGet()['page'] ?? 1;
		$rowPerPage = 3;
		$startRecord = ($currentPage - 1) * $rowPerPage;
		$totalCount = Merchandise::count();
		$totalPage = ceil($totalCount / $rowPerPage);
		$merchandisePaginate = Merchandise::OrderBy('created_at', 'desc')
			->offset($startRecord)
			->limit($rowPerPage)
			->get();
		foreach ($merchandisePaginate as &$merchandise) {
			$merchandise->photo = $this->imageDataUrlShow($merchandise->photo);
		}
		$this->page->routerRoot = IEnvironment::ROUTER_START;
		$this->page->title = "管理商品";
		$this->session->open(IEnvironment::SESSION_PATH_NAME['LOGGIN']['PATH'], IEnvironment::SESSION_PATH_NAME['LOGGIN']['NAME']);
		$this->page->session = $this->session->read(\session_id());
		$this->page->layout("src/Views/layouts/default.php");
		$this->page->render("src/Views/merchandise/manageMerchandise.php", [
			'merchandisePaginate' => $merchandisePaginate,
			'paginateLinks'       => range(1, $totalPage),
			'currentPage'         => $currentPage,
			'totalPage'           => $totalPage,
			'paginationHref'      => IEnvironment::ROUTER_START . "/merchandise/manage?page="
		]);
	}

	public function merchandiseListPage($request, $response)
	{
		$currentPage = $request->paramsGet()['page'] ?? 1;
		$rowPerPage = 3;
		$startRecord = ($currentPage - 1) * $rowPerPage;
		$merchandisePaginate = Merchandise::OrderBy('created_at', 'desc')
			->where('status', 'S')
			->offset($startRecord)
			->limit($rowPerPage)
			->get();
		foreach ($merchandisePaginate as &$merchandise) {
			$merchandise->photo = $this->imageDataUrlShow($merchandise->photo);
		}
		$totalCount = count($merchandisePaginate);
		$totalPage = ceil($totalCount / $rowPerPage);
		$this->page->routerRoot = IEnvironment::ROUTER_START;
		$this->page->title = "商品列表";
		$this->session->open(IEnvironment::SESSION_PATH_NAME['LOGGIN']['PATH'], IEnvironment::SESSION_PATH_NAME['LOGGIN']['NAME']);
		$this->page->session = $this->session->read(\session_id());
		$this->page->layout("src/Views/layouts/default.php");
		$this->page->render("src/Views/merchandise/listMerchandise.php", [
			'merchandisePaginate' => $merchandisePaginate,
			'paginateLinks'       => range(1, $totalPage),
			'currentPage'         => $currentPage,
			'totalPage'           => $totalPage,
			'paginationHref'      => IEnvironment::ROUTER_START . "/merchandise?page=",
		]);
	}

	private function generatorCsrfToken(): string
	{
		$token = \bin2hex(random_bytes(32));
		$this->session->open(IEnvironment::SESSION_PATH_NAME['CSRF']['PATH'], IEnvironment::SESSION_PATH_NAME['CSRF']['NAME']);
		$this->session->write(\session_id(), $token);
		return $token;
	}

	private function imageDataUrlShow(string $imageName)
	{
		$imagePath = 'src/Views/asset/images/default-merchandise.jpg';
		if (!is_null($imageName) and $imageName <> '') {
			$uploadPath = dirname(empty($_SERVER['DOCUMENT_ROOT']) ? (IEnvironment::DOCUMENT_ROOT) : $_SERVER['DOCUMENT_ROOT']) . "/phpWarehouse/" . IEnvironment::PROJECT_NAME . "/upload/";
			$imagePath = $uploadPath . $imageName;
		}
		return Image::make($imagePath)->encode('data-url');
	}

	private function validatorItemBuyProcess($request)
	{
		$errorMsg = [];
		if (!Validator::notBlank()->validate($request->buyCount) and !Validator::in(['0', '0.0', 0])->validate($request->buyCount)) {
			$errorMsg[] = "購買數量 不能為空";
		}
		if (!Validator::intVal()->validate($request->buyCount)) {
			$errorMsg[] = "購買數量 必須為整數";
		}
		if (!Validator::intVal()->min(1)->validate($request->buyCount)) {
			$errorMsg[] = "購買數量 必須為大於等於1";
		}
		return $errorMsg;
	}

	private function validatorItemUpdateProcess($request)
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
