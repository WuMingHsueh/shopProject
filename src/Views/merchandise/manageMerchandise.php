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
<nav aria-label="Page navigation">
	<ul class="pagination">
		<li class="page-item <?= ($this->currentPage != 1) ? '' : 'disabled' ?>">
			<a href="<?= $this->escape($this->routerRoot) ?>/merchandise/manage?page=<?= $this->currentPage - 1  ?>" class="page-link" aria-label="Previous">
				<span aria-hidden="true">&laquo;</span>
				<span class="sr-only">Previous</span>
			</a>
		</li>
		<?php foreach ($this->paginateLinks as $pageNumber) : ?>
			<li class="page-item <?= ($this->currentPage <> $pageNumber) ? '' : 'active' ?>">
				<a href="<?= $this->escape($this->routerRoot) ?>/merchandise/manage?page=<?= $pageNumber ?>" class="page-link">
					<?= $pageNumber ?>
				</a>
			</li>
		<?php endforeach; ?>
		<li class="page-item <?= ($this->currentPage != $this->totalPage) ? '' : 'disabled' ?>">
			<a href="<?= $this->escape($this->routerRoot) ?>/merchandise/manage?page=<?= $this->currentPage + 1  ?>" class="page-link" aria-label="Next">
				<span aria-hidden="true">&raquo;</span>
				<span class="sr-only">Next</span>
			</a>
		</li>
	</ul>
</nav>
