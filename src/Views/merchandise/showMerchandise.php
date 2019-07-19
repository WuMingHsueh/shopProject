<h1><?= $this->escape($this->title) ?></h1>
<?= $this->partial('src/Views/components/validationErrorMessage.php') ?>
<table class="table">
	<tr>
		<th>名稱</th>
		<td><?= $this->escape($this->merchandise->name) ?></td>
	</tr>
	<tr>
		<th>照片</th>
		<td>
			<img src="<?= $this->merchandise->photo ?>" alt="">
		</td>
	</tr>
	<tr>
		<th>價格</th>
		<td><?= $this->escape($this->merchandise->price) ?></td>
	</tr>
	<tr>
		<th>剩餘數量</th>
		<td><?= $this->escape($this->merchandise->remain_count) ?></td>
	</tr>
	<tr>
		<th>介紹</th>
		<td><?= $this->escape($this->merchandise->introduction) ?></td>
	</tr>
	<tr>
		<th>購買數量</th>
		<td>
			<form action="<?= $this->escape($this->routerRoot) ?>/merchandise/<?= $this->escape($this->merchandise->id) ?>/buy" method="post">
				<div class="form-group">
					<select class="form-control" name="buyCount" id="buyCount">
						<?php foreach (range(0, $this->merchandise->remain_count) as $count) : ?>
							<option value="<?= $count ?>"><?= $count ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<button class="btn btn-info" type="submit">購買</button>
				<input type="hidden" name="token" value="<?= $this->escape($this->csrfField) ?>">
			</form>
		</td>
	</tr>
</table>
