<?php
    include_once './header.php';

    if (isLogin()) {
        exit("<script>window.location.href='index'</script>");
    }
?>

<div class="container max-vh-100"> <!-- container -->
    <div class="row justify-content-center align-items-center h-100"> <!-- row -->
        <div class="col-lg-8"> <!-- col-lg-8 -->
            <div class="card user-form"> <!-- card -->
                <div class="card-header"> <!-- card-header -->
                    <h1 class="card-title">Prijava</h1>
                </div> <!-- card-header -->
                <div class="card-body"> <!-- card-body -->


                    <?php if (isset($_GET['error'])):
                        if (isset($_GET['error']) && $_GET['error'] == "pass"): ?>
                            <div class="alert w-100 mt-2 text-center alert-danger rounded">
                                <h4>Napačno geslo!</h4>
                            </div>
                        <?php elseif (isset($_GET['error']) && $_GET['error'] == "user"): ?>
                            <div class="alert w-100 mt-2 text-center alert-danger rounded">
                                <h4>Uporabnik ne obstaja!</h4>
                            </div>
                        <?php elseif (isset($_GET['error']) && $_GET['error'] == "err"): ?>
                            <div class="alert w-100 mt-2 text-center alert-danger rounded">
                                <h4>Prišlo je do napake, prosim, preverite vnesene podatke. Če še zmeraj prihaja do napake, prosimo, da počakate ali pa kontaktirate podporo!</h4>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>

                    <?php if (isset($_GET['status']) && $_GET['status'] == "pass-change"): ?>
                        <div class="alert w-100 text-center alert-success">
                            <h3>Uspešno ste ponastavili geslo!</h3>
                        </div>
                        <?php endif; ?>
                    <form method="post" action="./src/inc/login.inc.php">
                        <!-- Email -->
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label text-md-right" for="email">Elektronska pošta:</label>
                            <input type="email" class="form-control col-md-6" name="email" id="email" placeholder="Elektronska pošta..." required <?php if(isset($_GET['error']) && isset($_GET['email'])): ?> value="<?=$_GET['email']?>" <?php endif; ?>>
                        </div>
 
                        <!-- Password -->
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label text-md-right" for="password">Geslo:</label>
                            <input type="password" class="form-control col-md-6" name="password" id="password" placeholder="Geslo..." required>
                        </div>

                        <div class="form-group">
                            <button type="submit" name="Submit" class="btn btn-primary float-right btn-login col-sm-6" value="Prijava">Prijava</button>
                        </div>

                    </form>

                </div> <!-- card-body -->
                <div class="card-footer"> <!-- card-footer -->
                    <p>Nimate računa? Ustvarite račun: <a href="./register">Registracija</a></p>
                    <p>Pozabili geslo? Ponastavitev gesla: <a href="./password-reset">Ponastavitev gesla</a></p>
                </div> <!-- card-footer -->
            </div> <!-- card -->
        </div> <!-- col-lg-8 -->
    </div> <!-- row -->
</div> <!-- container -->

<?php
    include_once './footer.php';
 ?>

