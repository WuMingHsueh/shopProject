<h1><?= $this->escape($this->title) ?></h1>
<?= $this->partial('src/Views/components/validationErrorMessage.php') ?>
<form action="<?= $this->escape($this->routerRoot) ?>/merchandise/<?= $this->escape($this->merchandise->id) ?>" method="post" enctype="multipart/form-data">
	<input type="hidden" name="_method" value="put" />
	<input type="hidden" name="token" value="<?= $this->escape($this->csrfField) ?>">
	<div>
		<label>
			商品狀態：
			<select name="status">
				<option value="C" <?php if ($this->merchandise->status == 'C') : ?>selected<?php endif; ?>>
					建立中
				</option>
				<option value="S" <?php if ($this->merchandise->status == 'S') : ?>selected<?php endif; ?>>
					可販售
				</option>
			</select>
		</label>
	</div>
	<div>
		<label>
			商品名稱：
			<input type="text" name="name" placeholder="商品名稱" value="<?= $this->escape($this->merchandise->name) ?? ''; ?>">
		</label>
	</div>
	<div>
		<label>
			商品英文名稱：
			<input type="text" name="name_en" placeholder="商品英文名稱" value="<?= $this->escape($this->merchandise->name_en) ?? ''; ?>">
		</label>
	</div>
	<div>
		<label>
			商品介紹：
			<input type="text" name="introduction" placeholder="商品介紹" value="<?= $this->escape($this->merchandise->introduction) ?? ''; ?>">
		</label>
	</div>
	<div>
		<label>
			商品英文介紹：
			<input type="text" name="introduction_en" placeholder="商品英文介紹" value="<?= $this->escape($this->merchandise->introduction_en) ?? ''; ?>">
		</label>
	</div>
	<div>
		<label>
			商品照片：
			<input type="file" name="photo" placeholder="商品照片">
			<img src="<?= $this->escape($this->merchandise->photo) ?>" alt="">
		</label>
	</div>
	<div>
		<label>
			商品價格：
			<input type="text" name="price" placeholder="商品價格" value="<?= $this->escape($this->merchandise->price) ?? ''; ?>">
		</label>
	</div>
	<div>
		<label>
			商品剩餘數量：
			<input type="text" name="remain_count" placeholder="商品剩餘數量" value="<?= $this->escape($this->merchandise->remain_count) ?? ''; ?>">
		</label>
	</div>
	<button type="submit" class="btn btn-info">更新商品資訊</button>
</form>
