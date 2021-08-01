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
                            </div>
                        </form>
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