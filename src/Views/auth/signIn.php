<h1>
	<?= $this->escape($this->title) ?>
</h1>

<?= $this->partial('src/Views/components/validationErrorMessage.php') ?>

<form action="<?= $this->routerRoot ?>/user/auth/sign-in" method="post">
	<label>
		Email:
		<input type="text" name="email" placeholder="Email" value="<?= $this->escape($this->email); ?>">
	</label>
	<label>
		Password:
		<input type="password" name="password" placeholder="Password">
	</label>
	<button class="btn btn-info">登入</button>
</form>
