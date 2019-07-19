<h1><?= $this->escape($this->title) ?></h1>
<?= $this->partial('src/Views/components/validationErrorMessage.php') ?>
<table class="table" border="1">
	<thead>
		<tr>
			<th>編號</th>
			<th>名稱</th>
			<th>圖片</th>
			<th>狀態</th>
			<th>價格</th>
			<th>剩餘數量</th>
			<th>編輯</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($this->merchandisePaginate as $merchandise) : ?>
			<tr>
				<td><?= $merchandise->id ?></td>
				<td><?= $merchandise->name ?></td>
				<td><img src="<?= $merchandise->photo ?>" alt=""></td>
				<td><?= ($merchandise->status == 'C') ? "建立中" : "可販售" ?></td>
				<td><?= $merchandise->price ?></td>
				<td><?= $merchandise->remain_count ?></td>
				<td>
					<a href="<?= $this->escape($this->routerRoot) ?>/merchandise/<?= $this->escape($merchandise->id) ?>/edit">編輯</a>
				</td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>
<?= $this->partial('src/Views/components/pagination.php') ?>
