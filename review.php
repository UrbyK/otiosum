
    <div class="review">
        <div class="review-body">
            <p class="text-right"><small><?=date('j.n.Y', strtotime($review['date_created']))?></small></p>
            <?php for($i=0; $i<5; $i++):
                if($i<$review['rating']): ?>
                    <span class="fa fa-star checked text-success"></span>
                <?php else: ?>
                    <span class="fa fa-star"></span>
                <?php endif;
            endfor; ?>
            <h4 class="review-heading user-name"><?=$user['username']?></h4>
            <?=$review['comment']?>
        </div>
        <?php if(isLogin() && isAdmin() || isMod()): ?>
        <!-- put buttons in line -->
        <div class="text-right">
            <button id="delete" class="btn btn-danger" value="<?=$review['id']?>" name="rid">
                <i class="fa fa-minus"></i>
            </button>
        </div>
        <?php endif; ?>
    </div>