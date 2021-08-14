<?php
    include_once './header.php';
    if(!isLogin()) {
        exit("<script>window.location.href='index'</script>");
    }
?>
<div class="container">
    <div class="row my-2">
        <div class="filter-data">
        </div>
    </div>
    <div class="row my-2">
        <div class="card w-100" style="max-width: 450px;">
            <div class="card-body">
                <?php if(isset($_GET['status']) && $_GET['status'] == "success"): ?>
                    <div class="alert text-center alert-success">
                        Geslo ste uspešno posodobili!
                    </div>
                <?php elseif(isset($_GET['error']) && !empty($_GET['error'])):
                    include_once './src/inc/error.inc.php'; 
                        if (array_key_exists($_GET['error'], $errorList)): ?>
                            <div class="alert text-center alert-danger">
                                <?=$errorList[$_GET['error']]?>
                            </div>
                        <?php else: ?>
                            <div class="alert w-100 text-center alert-danger rounded">
                                Zgodila se je neznana napaka. Prosim, poskusite kasneje!
                            </div>
                        <?php endif; ?>
                <?php else: ?>
                    <div class="alert text-center alert-info">
                        Geslo mora vsebovati vsaj eno veliko črko, eno malo črko, številko in znak!
                    </div>
                <?php endif; ?>
                <form id="password-update" name="password-update" method="POST" action="./account.inc.php">
                    <div class="form-group">
                        <label for="password">Trenutno geslo<span class="asterisk">*</span></label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="form-group">
                        <label for="newPassword">Novo geslo<span class="asterisk">*</span></label>
                        <input type="password" class="form-control" id="newPassword" name="newPassword" required>
                    </div>
                    <div class="form-group">
                        <label for="confirmNewPassword">Ponovi novo geslo<span class="asterisk">*</span></label>
                        <input type="password" class="form-control" id="confirmNewPassword" name="confirmNewPassword" required>
                    </div>

                    <button type="submit" id="updatePass" name="updatePass" value="updatePassword" class="btn btn-primary mb-2 float-right">Posodobi geslo</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
    include_once './footer.php';
?>

<script src="./src/js/ajax-account.js" crossorigin="anonymous"></script>