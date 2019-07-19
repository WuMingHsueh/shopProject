<h1><?= $this->escape($this->title) ?></h1>
<?= $this->partial('src/Views/components/validationErrorMessage.php') ?>
<table border="1">
	<thead>
		<tr>
			<th>名稱</th>
			<th>照片</th>
			<th>價格</th>
			<th>剩餘數量</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($this->merchandisePaginate as $merchandise) : ?>
			<tr>
				<td>
					<a href="<?= $this->escape($this->routerRoot) ?>/merchandise/<?= $this->escape($merchandise->id) ?>">
						<?= $merchandise->name ?>
					</a>
				</td>
				<td>
					<a href="<?= $this->escape($this->routerRoot) ?>/merchandise/<?= $this->escape($merchandise->id) ?>">
						<img src="<?= $merchandise->photo ?>" alt="">
					</a>
				</td>
				<td><?= $merchandise->price ?></td>
				<td><?= $merchandise->remain_count ?></td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>
<?= $this->partial('src/Views/components/pagination.php') ?>
