<?php if (isset($this->errors) and count($this->errors)) : ?>
	<ul>
		<?php foreach ($this->errors as $err) : ?>
			<li><?= $err ?></li>
		<?php endforeach; ?>
	</ul>
<?php endif; ?>
