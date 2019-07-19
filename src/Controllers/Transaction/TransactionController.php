<?php

namespace ShopProject\Controllers\Transaction;

use Pimple\Container;
use Intervention\Image\ImageManagerStatic as Image;
use ShopProject\IEnvironment;
use ShopProject\Models\DataCollection\Transaction;
use ShopProject\Models\DataCollection\User;

class TransactionController
{
	private $session;
	private $page;

	public function __construct(Container $container)
	{
		$this->session = $container['session'];
		$this->page = $container['page'];
	}

	public function transactionListPage($request, $response)
	{
		$this->session->open(IEnvironment::SESSION_PATH_NAME['LOGGIN']['PATH'], IEnvironment::SESSION_PATH_NAME['LOGGIN']['NAME']);
		$emailUserSession = $this->session->read(\session_id())['email'];

		$currentPage = $request->paramsGet()['page'] ?? 1;
		$rowPerPage = 3;
		$startRecord = ($currentPage - 1) * $rowPerPage;

		$userId = User::where('email', $emailUserSession)->get()[0]->id;
		$transactionPaginate = Transaction::where('user_id', $userId)
			->OrderBy('created_at', 'desc')
			->offset($startRecord)
			->limit($rowPerPage)
			->with('Merchandise')
			->get();
		$totalCount = count($transactionPaginate);
		$totalPage = ceil($totalCount / $rowPerPage);

		foreach ($transactionPaginate as &$transaction) {
			$transaction->merchandise->photo = $this->imageDataUrlShow($transaction->merchandise->photo);
		}

		$this->page->routerRoot = IEnvironment::ROUTER_START;
		$this->page->title = "交易紀錄";
		$this->session->open(IEnvironment::SESSION_PATH_NAME['LOGGIN']['PATH'], IEnvironment::SESSION_PATH_NAME['LOGGIN']['NAME']);
		$this->page->session = $this->session->read(\session_id());
		$this->page->layout("src/Views/layouts/default.php");
		$this->page->render("src/Views/transaction/listUserTransaction.php", [
			'transactionPaginate' => $transactionPaginate,
			'paginateLinks'       => range(1, $totalPage),
			'currentPage'         => $currentPage,
			'totalPage'           => $totalPage,
			'paginationHref'      => IEnvironment::ROUTER_START . "/transaction?page=",
		]);
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
}
