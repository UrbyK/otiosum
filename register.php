<?php 
    include_once './header.php';
    if (!isLogin()) {
        exit("<script>window.location.href='index'</script>");
    }
?>

<div class="container max-vh-100"> <!-- container -->
    <div class="row justify-content-center align-items-center h-100"> <!-- row -->
        <div class="col-lg-8"> <!-- col-lg-8 -->
            <div class="card user-form"> <!-- card -->
                <div class="card-header"> <!-- card-header -->
    	            <h1 class="card-title">Registracija</h1>
                </div> <!-- card-header -->
                
                <div class="card-body"> <!-- card-body -->
                    
                    <?php if (isset($_GET['error'])):
                        include_once './src/inc/error.inc.php'; 
                        if (array_key_exists($_GET['error'], $errorList)): ?>
                            <div class="error w-100 text-center alert-danger rounded">
                                <p><?=$errorList[$_GET['error']]?></p>
                            </div>
                        <?php else: ?>
                            <div class="error w-100 text-center alert-danger rounded">
                                <p>Zgodila se je neznana napaka. Prosim poskusite kasneje!</p>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>

                    <div class="progress">
                        <div aria-valuemax="100" aria-valuemin="0" aria-valuenow="50" class="progress-bar progress-bar-striped progress-bar-animated bg-primary" role="progressbar" style="width: 0%"></div>
                    </div>

                    <div id="qbox-container">
                        <form method="post" action="./src/inc/register.inc.php" autocomplete="off">

                            <div id="steps-container">

                                <div class="step">
                                    <!-- Credentils information -->
                                    <div class="form-group row">
                                        <label class="col-md-4 col-form-label text-md-right" for="username">Uporabniško ime:*</label>
                                        <input type="text" class="form-control col-md-6" name="username" id="username" placeholder="Uporabniško ime..." minlenght="4" autocomplete="off" required data-bs-toggle="tooltip" data-placement="top" title="Uporabniško ime mora biti dolgo vsaj 4 znake!" <?php if (isset($_GET['error'], $_GET['user'])): ?> value="<?=$_GET['user']?>" <?php endif; ?>>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-4 col-form-label text-md-right" for="email">Elektronska pošta:*</label>
                                        <input type="email" class="form-control col-md-6" name="email" id="email" placeholder="Elektronska pošta..." autocomplete="off" required <?php if (isset($_GET['error'], $_GET['email'])): ?> value="<?=$_GET['email']?>" <?php endif; ?>>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-4 col-form-label text-md-right" for="password">Geslo:*</label>
                                        <input type="password" class="form-control col-md-6" name="password" id="password" placeholder="Geslo..." minlenght="8" autocomplete="off" required data-bs-toggle="tooltip" data-placement="top" title="Vsebovati mora eno veliko črko, eno malo črko, številko in znak!">
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-4 col-form-label text-md-right" for="confirmPassword">Potrdite geslo:*</label>
                                        <input type="password" class="form-control col-md-6" name="confirmPassword" id="confirmPassword" placeholder="Potrdite geslo..." autocomplete="off" required>
                                    </div>
                                </div> <!-- step -->

                                <div class="step">
                                    <!-- Firstname/Lastname -->
                                    <div class="form-group row">
                                        <label class="col-md-4 col-form-label text-md-right" for="firstName">Ime:*</label>
                                        <input type="text" class="form-control col-md-6" name="firstName" id="firstName" placeholder="Ime..." autocomplete="off" required <?php if (isset($_GET['error'], $_GET['fname'])): ?> value="<?=$_GET['fname']?>" <?php endif; ?>>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-4 col-form-label text-md-right" for="lastName">Priimek:*</label>
                                        <input type="text" class="form-control col-md-6" name="lastName" id="lastName" placeholder="Priimek..." autocomplete="off" required <?php if (isset($_GET['error'], $_GET['lname'])): ?> value="<?=$_GET['lname']?>" <?php endif; ?>>
                                    </div>
                                
                                    <!-- Phone number -->
                                    <div class="form-group row">
                                        <label class="col-md-4 col-form-label text-md-right" for="phoneNumber">Telefon:</label>
                                        <input type="text" class="form-control col-md-6" name="phoneNumber" id="phoneNumber" placeholder="Telefonska stevilka..." autocomplete="off" <?php if (isset($_GET['error'], $_GET['phone'])): ?> value="<?=$_GET['phone']?>" <?php endif; ?>>
                                    </div>
                                </div> <!-- step -->

                                <div class="step">
                                    <!-- Address -->
                                    <div class="form-group row">
                                        <label class="col-md-4 col-form-label text-md-right" for="addressOne">Naslov 1:*</label>
                                        <input type="text" class="form-control col-md-6" name="addressOne" id="addressOne" placeholder="Naslov..." autocomplete="off" required <?php if (isset($_GET['error'], $_GET['addr'])): ?> value="<?=$_GET['addr']?>" <?php endif; ?>>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-4 col-form-label text-md-right" for="addressTwo">Naslov 2:</label>
                                        <input type="text" class="form-control col-md-6" name="addressTwo" id="addressTwo" placeholder="Naslov..." autocomplete="off" <?php if (isset($_GET['error'], $_GET['addrsec'])): ?> value="<?=$_GET['addrsec']?>" <?php endif; ?>>
                                    </div>
                                    <!-- Postal code -->
                                    <div class="form-group row">
                                        <label class="col-md-4 col-form-label text-md-right" for="postalCode">Poštna številka:*</label>
                                        <input type="text" class="form-control col-md-6" name="postalCode" id="postalCode" placeholder="Postna stevilka..." autocomplete="off" required <?php if (isset($_GET['error'], $_GET['postalCode'])): ?> value="<?=$_GET['postalCode']?>" <?php endif; ?>>
                                    </div>
                                    <!-- City -->
                                    <div class="form-group row">
                                        <label class="col-md-4 col-form-label text-md-right" for="city">Mesto:*</label>
                                        <input type="text" class="form-control col-md-6" name="city" id="city" placeholder="Mesto..." autocomplete="off" required <?php if (isset($_GET['error'], $_GET['city'])): ?> value="<?=$_GET['city']?>" <?php endif; ?>>
                                    </div>
                                    <!-- Country -->
                                    <div class="form-group row">
                                        <label class="col-md-4 col-form-label text-md-right" for="country">Država:*</label>
                                            <select name="country" id="country" class="form-control col-md-6" required>
                                                <?php if (!isset($_GET['error'], $_GET['country'])): ?>
                                                    <option hidden disabled selected value>--N/A--</option>
                                                    <?php foreach(countries() as $item): ?>
                                                        <option value="<?=$item['id']?>"><?=$item['country']?></option>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <?php foreach(countries() as $item): ?>
                                                        <option value="<?=$item['id']?>" <?php if ($item['id'] == $_GET['country']): ?> selected <?php endif;?>><?=$item['country']?></option>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </select>
                                    </div>
                                </div> <!-- step -->

                            </div> <!-- step-container -->

                            <!-- Nav buttons, submit button -->
                            <div id="q-box__buttons">
                                <button id="prev-btn" type="button" class="btn btn-secondary" >Nazaj</button> 
                                <button id="next-btn" type="button" class="btn btn-secondary">Naprej</button> 

                            </div>
                            <button id="submit-btn" type="submit" name="submit" class="btn btn-primary float-right btn-login col-sm-6">Registracija</button>                            
                        </form>
                    </div><!-- qbox-container -->
                </div> <!-- card-body -->
                <div class="card-footer"> <!-- card-footer -->
                    Že imate račun? Prijavite se: <a href="./login.php">Prijava</a>
                </div> <!-- card-footer -->

            </div> <!-- card -->
        </div> <!-- col-lg-8 -->
    </div> <!-- row -->
</div> <!-- container -->

<div id="preloader-wrapper">
    <div id="preloader"></div>
    <div class="preloader-section section-left"></div>
    <div class="preloader-section section-right"></div>
</div>

<script src="./src/js/multistep-form.js" crossorigin="anonymous"></script>
<!-- script to enable boostrap tooltip -->
<script>
$(document).ready(function(){
  $('[data-bs-toggle="tooltip"]').tooltip();
});
</script>

<?php 
    include_once './footer.php';
?>