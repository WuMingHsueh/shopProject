<ul class="nav">
	<?php if (empty($this->session)) : ?>
		<li><a href="<?= $this->routerRoot ?>/user/auth/sign-in">登入</a></li>
		<li><a href="<?= $this->routerRoot ?>/user/auth/sign-up">註冊</a></li>
	<?php else : ?>
		<li><a href="<?= $this->routerRoot ?>/user/auth/sign-out">登出</a></li>
	<?php endif; ?>
</ul>
