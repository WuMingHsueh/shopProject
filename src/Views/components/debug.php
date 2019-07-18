<h2>
	您現在在debug頁面
</h2>
<hr>
<div>
	<?= $this->escape($this->message); ?>
</div>
<div>
	<?= session_id(); ?>
</div>
<div>
	<?php var_dump($this->data) ?>
</div>
