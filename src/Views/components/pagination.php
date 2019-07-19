<nav aria-label="Page navigation">
	<ul class="pagination">
		<li class="page-item <?= ($this->currentPage != 1) ? '' : 'disabled' ?>">
			<a href="<?= $this->paginationHref ?><?= $this->currentPage - 1  ?>" class="page-link" aria-label="Previous">
				<span aria-hidden="true">&laquo;</span>
				<span class="sr-only">Previous</span>
			</a>
		</li>
		<?php foreach ($this->paginateLinks as $pageNumber) : ?>
			<li class="page-item <?= ($this->currentPage <> $pageNumber) ? '' : 'active' ?>">
				<a href="<?= $this->paginationHref ?><?= $pageNumber ?>" class="page-link">
					<?= $pageNumber ?>
				</a>
			</li>
		<?php endforeach; ?>
		<li class="page-item <?= ($this->currentPage != $this->totalPage) ? '' : 'disabled' ?>">
			<a href="<?= $this->paginationHref ?><?= $this->currentPage + 1  ?>" class="page-link" aria-label="Next">
				<span aria-hidden="true">&raquo;</span>
				<span class="sr-only">Next</span>
			</a>
		</li>
	</ul>
</nav>
