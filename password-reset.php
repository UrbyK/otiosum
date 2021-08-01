<?php
    include './header.php';
?>

<div class="container h-100">
    <div class="row justify-content-center align-items-center h-100"> 
        <div class="col-md-4">
            <?php if (isset($_GET['error'])):
                include_once './src/inc/error.inc.php';

                // check if given error exists in the error array  
                if (array_key_exists($_GET['error'], $errorList)): ?>
                    <div class="error w-100 text-center alert-danger">
                        <h3><?=$errorList[$_GET['error']]?></h3>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
            <div class="card " style="box-shadow: 4px 2px 5px #212529;">
                <div class="card-body">
                    <div class="text-center">
                        <h1 class="icon"><i class="fas fa-lock"></i></h1>
                        <!-- display email form if the status is not to check email -->
                        <?php if(!isset($_GET['status'])): ?>
                        <h4>Ste pozabili geslo?</h4>
                        <p>Tu lahko ponastavite vaše geslo.</p>
                        <form autocomplete="off" method="POST" action="./src/inc/password-reset.inc.php">
                            <div class="form-group">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="envelope"><i class="fas fa-envelope"></i></span>
                                    </div>
                                    <input type="email" class="form-control" name="email" placeholder="email" aria-label="email" aria-describedby="envelope" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <button type="submit" name="submit" class="btn btn-primary btn-block">Ponastavitev gesla</button>
                                <input type="hidden" value="0" name="reset" id="reset">
                            </div>
                        </form>
                        <?php elseif (isset($_GET['status']) && !empty($_GET['status']) && $_GET['status'] == "check"): ?>
                            <div class="message">
                                Prosim preverite svoj email za ponastavitev gesla!
                            </div>
                        <?php elseif (isset($_GET['status']) && $_GET['status'] == "verify" && isset($_GET['email']) && isset($_GET['token'])): 
                            $email = $_GET['email'];
                            $token = $_GET['token']; ?>
                            <form id="password-reset" autocomplete="off" method="POST" action="./src/inc/password-reset.inc.php">
                                <div class="form-group text-left">
                                    <label for="password">Novo geslo:</label>
                                    <input type="password" class="form-control" name="password" id="password" placeholder="Novo geslo..." minlenght="8" autocomplete="off" required data-bs-toggle="tooltip" data-placement="top" title="Vsebovati mora eno veliko črko, eno malo črko, številko in znak!">
                                </div>
                                <div class="form-group text-left">
                                    <label for="confirmPassword">Potrdite geslo:</label>
                                    <input type="password" class="form-control" name="confirmPassword" id="confirmPassword" placeholder="Potrdite geslo..." autocomplete="off" required>
                                </div>
                                <div class="form-group">
                                    <button type="submit" name="submit" class="btn btn-primary btn-block">Ponastavitev gesla</button>
                                    <input type="hidden" value="1" name="reset" id="reset">
                                    <input type="hidden" value="<?=$email?>" name="email" id="email">
                                    <input type="hidden" value="<?=$token?>" name="token" id="token">
                                </div>
                        </form>
                        <?php endif; ?>
                    </div>
                </div>
                <p class="pl-3">Prijava v račun: <a href="./login">Prijava</a></p>
            </div>
        </div>
    </div>
</div>


<?php
    include './footer.php';
?>
