<?php
    include_once './header.php';
?>

<div class="container max-vh-100"> <!-- container -->
    <div class="row justify-content-center align-items-center h-100"> <!-- row -->
        <div class="col-lg-8"> <!-- col-lg-8 -->
            <div class="card user-form"> <!-- card -->
                <div class="card-header"> <!-- card-header -->
                    <h1 class="card-title">Prijava</h1>
                </div> <!-- card-header -->
                <div class="card-body"> <!-- card-body -->

                    <form method="post" action="#">
                        <!-- Email -->
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label text-md-right" for="email">Elektronska pošta:</label>
                            <input type="email" class="form-control col-md-6" name="email" id="email" placeholder="Elektronska pošta">
                        </div>
 
                        <!-- Password -->
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label text-md-right" for="password">Geslo:</label>
                            <input type="text" class="form-control col-md-6" name="password" id="password" placeholder="Geslo">
                        </div>

                        <div class="form-group">
                            <button type="submit" name="Submit" class="btn btn-primary float-right btn-login col-sm-6" value="Vpiši se">Prijava</button>
                        </div>

                    </form>

                </div> <!-- card-body -->
                <div class="card-footer"> <!-- card-footer -->
                    Nimate računa? Ustvarite račun: <a href="./register.php">Registracija</a>
                </div> <!-- card-footer -->
            </div> <!-- card -->
        </div> <!-- col-lg-8 -->
    </div> <!-- row -->
</div> <!-- container -->

<?php
    include_once './footer.php';
 ?>

