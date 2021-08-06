
    <ul class="pagination">
<?php if ($totalPages > 1 && $page <= $totalPages): ?>
        <?php if ($page >= 2): ?>
            <li class="page-item">
                <a class="page-link" href="javascript:void(0)" data-page_number="<?=$page - 1?>">
                    <span>&laquo;</span>
                </a>
            </li>
        <?php endif; ?>

        <?php if ($page == 1): ?>
            <li class="page-item active">
                <a class="page-link" href="#">
                    <?=$page?>
                </a>
            </li>
        <?php else: ?>
            <li class="page-item">
                <a class="page-link" href="javascript:void(0)" data-page_number="1">
                    1
                </a>
            </li>
        <?php endif; 

        $i = max(2, $page-2);
        if ($i > 2): ?>
            <li class="page-item disabled">
                <a class="page-link" href="#">
                    ...
                </a>
            </li>
        <?php endif; ?>

        <?php for ($i; $i<min($page + 3, $totalPages); $i++): ?>
            <?php if ($i == $page): ?>
                <li class="page-item active">
                <a class="page-link" href="" data-page_number="<?=$i?>">
                    <?=$i?>
                </a>
            </li>
            <?php else: ?>
                <li class="page-item">
                <a class="page-link" href="javascript:void(0)" data-page_number="<?=$i?>">
                    <?=$i?>
                </a>
            </li>
            <?php endif; ?>
        <?php endfor; ?>

        <?php if ($i != $totalPages): ?>
            <li class="page-item disabled">
                <a class="page-link" href="#">
                    ...
                </a>
            </li>
        <?php endif; ?>

        <?php if ($page == $totalPages): ?>
            <li class="page-item active">
                <a class="page-link" href="javascript:void(0)" data-page_number="<?=$totalPages?>">
                    <?=$totalPages?>
                </a>
            </li>
        <?php else: ?>
            <li class="page-item">
                <a class="page-link" href="javascript:void(0)" data-page_number="<?=$totalPages?>">
                    <?=$totalPages?>
                </a>
            </li>
        <?php endif; ?>

        <?php if ($page < $totalPages): ?>
            <li class="page-item">
                <a class="page-link" href="javascript:void(0)" data-page_number="<?=$page + 1?>">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        <?php endif; ?>
    <?php endif; ?>
    </ul>

