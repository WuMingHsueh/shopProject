<h1>
	<?= $this->escape($this->title) ?>
</h1>

<?= $this->partial('src/Views/components/validationErrorMessage.php') ?>

<form action="<?= $this->routerRoot ?>/user/auth/sign-up" method="post">
	<label>
		暱稱:<input type="text" name="nickname" placeholder="暱稱" value="<?= $this->nickname ?? ''; ?>">
	</label>
	<label>
		Email: <input type="text" name="email" placeholder="Email" value="<?= $this->email ?? ''; ?>">
	</label>
	<label>
		密碼: <input type="password" name="password" id="" placeholder="密碼" value="<?= $this->password ?? ''; ?>">
	</label>
	<label>
		確認密碼: <input type="password" name="password_confirmation" placeholder="確認密碼" value="<?= $this->password_confirmation ?? ''; ?>">
	</label>
	<label>
		帳號類型:
		<select name="type">
			<option value="G" <?= (@$this->type == 'G') ? 'selected' : ''; ?>>一般會員</option>
			<option value="A" <?= (@$this->type == 'A') ? 'selected' : ''; ?>>管理者</option>
		</select>
	</label>
	<button>註冊</button>
</form>
