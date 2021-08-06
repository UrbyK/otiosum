<?php

function pagination($page, $total_pages, $current_page) { ?>

<div class="row justify-content-center text-center mt-3">
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
            <?php if($total_pages>1 && $current_page <= $total_pages):

                if($current_page>=2):?>
                    <li class="page-item">
                        <a class="page-link" href="<?=$page?><?=$current_page-1?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                <?php endif;

                if($current_page == 1): ?>
                    <li class="page-item active">
                        <a class="page-link" href="<?=$page?><?=1?>"> 1 </a>
                    </li>
                <?php else: ?>
                    <li class="page-item"><a class="page-link" href="<?=$page?><?=1?>"> 1 </a></li>
                <?php endif;

                $i = max(2, $current_page-2);
                if($i > 2): ?>
                    <li class="page-item disabled"><a class="page-link" href="#"> ... </a></li>
                <?php endif;

                for($i; $i<min($current_page+3, $total_pages); $i++): 
                    if($i == $current_page): ?>
                        <li class="page-item active">
                            <a class="page-link" href="<?=$page?><?=$i?>"><?=$i?></a>
                        </li>
                    <?php else: ?>
                        <li class="page-item">
                            <a class="page-link" href="<?=$page?><?=$i?>"><?=$i?></a>
                        </li>
                    <?php endif; 
                endfor;

                if($i != $total_pages): ?>
                    <li class="page-item disabled">
                        <a class="page-link" href="#"> ... </a>
                    </li>
                <?php endif;

                if($current_page == $total_pages): ?>
                    <li class="page-item active">
                        <a class="page-link" href="<?=$page?><?=$total_pages?>"><?=$total_pages?></a>
                    </li>
                <?php else: ?>
                    <li class="page-item">
                        <a class="page-link" href="<?=$page?><?=$total_pages?>"><?=$total_pages?></a>
                    </li>
                <?php endif;
                
                if($current_page < $total_pages): ?>
                    <li class="page-item">
                        <a class="page-link" href="<?=$page?><?=$current_page+1?>" aria-label="Next"><span aria-hidden="true">&raquo;</span></a>
                    </li>
                <?php endif; ?>
            <?php endif; ?>   
        </ul>
    </nav>
</div>
<?php } ?>