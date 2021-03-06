<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title><?= $this->escape($this->title); ?> - shop Project</title>
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="/<?= ShopProject\IEnvironment::PROJECT_NAME; ?>/src/Views/asset/css/font-awesome.min.css">

	<!-- <script src="/<?= ShopProject\IEnvironment::PROJECT_NAME; ?>/src/Views/asset/js/jquery-2.2.4.min.js" defer></script>
	<script src="/<?= ShopProject\IEnvironment::PROJECT_NAME; ?>/src/Views/asset/js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="/<?= ShopProject\IEnvironment::PROJECT_NAME; ?>/src/Views/asset/css/bootstrap.min.css">
	<link rel="stylesheet" href="/<?= ShopProject\IEnvironment::PROJECT_NAME; ?>/src/Views/asset/css/font-awesome.min.css"> -->
</head>

<body>
	<header>
		<?= $this->partial('src/Views/components/nav.php') ?>
	</header>

	<div class="container">
		<?= $this->yieldView(); ?>
	</div>

	<footer>
		<?= $this->partial('src/Views/components/socialButtons.php') ?>
		<a href="#">聯絡我們</a>
	</footer>
</body>

</html>
