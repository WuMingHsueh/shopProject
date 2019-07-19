<h1><?= $this->escape($this->title) ?></h1>
<?= $this->partial('src/Views/components/validationErrorMessage.php') ?>
<table class="table">
	<thead>
		<tr>
			<th>商品名稱</th>
			<th>圖片</th>
			<th>單價</th>
			<th>數量</th>
			<th>總金額</th>
			<th>購買時間</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($this->transactionPaginate as $transaction) : ?>
			<tr>
				<td>
					<a href="<?= $this->escape($this->routerRoot) ?>/merchandise/<?= $this->escape($transaction->merchandise->id) ?>">
						<?= $transaction->merchandise->name ?>
					</a>
				</td>
				<td>
					<a href="<?= $this->escape($this->routerRoot) ?>/merchandise/<?= $this->escape($transaction->merchandise->id) ?>">
						<img src="<?= $transaction->merchandise->photo ?>" alt="">
					</a>
				</td>
				<td><?= $transaction->price ?></td>
				<td><?= $transaction->buy_count ?></td>
				<td><?= $transaction->total_price ?></td>
				<td><?= $transaction->created_at ?></td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>
<?= $this->partial('src/Views/components/pagination.php') ?>
