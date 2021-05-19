
<div class="container max-vh-100"> <!-- container -->
    <div class="row justify-content-center align-items-center h-100"> <!-- row -->
        <div class="col-lg-8"> <!-- col-lg-8 -->
            <div class="card user-form"> <!-- card -->
                <div class="card-header"> <!-- card-header -->
    	            <h1 class="card-title">Registracija</h1>
                </div> <!-- card-header -->
                
                <div class="card-body"> <!-- card-body -->

                    <form method="post" action="#">
                        
                        <!-- Email/password -->
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label text-md-right" for="email">Elektronska pošta:</label>
                            <input type="email" class="form-control col-md-6" name="email" id="email" placeholder="Elektronska pošta...">
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label text-md-right" for="password">Geslo:</label>
                            <input type="password" class="form-control col-md-6" name="password" id="password" placeholder="Geslo...">
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label text-md-right" for="confirm_password">Potrdite geslo:</label>
                            <input type="password" class="form-control col-md-6" name="confirm_password" id="confirm_password" placeholder="Potrdite geslo...">
                        </div>

                        <!-- Firstname/Lastname -->
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label text-md-right" for="firstName">Ime:</label>
                            <input type="text" class="form-control col-md-6" name="firstName" id="firstName" placeholder="Ime...">
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label text-md-right" for="lastName">Priimek:</label>
                            <input type="text" class="form-control col-md-6" name="lastName" id="lastName" placeholder="Priimek...">
                        </div>

                        <!-- Phone number -->
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label text-md-right" for="phoneNumber">Telefon:</label>
                            <input type="text" class="form-control col-md-6" name="phoneNumber" id="phoneNumber" placeholder="Telefonska stevilka...">
                        </div>

                        <!-- Address -->
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label text-md-right" for="addres_one">Naslov 1:</label>
                            <input type="text" class="form-control col-md-6" name="addres_one" id="addres_one" placeholder="Naslov...">
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label text-md-right" for="address_two">Naslov 2:</label>
                            <input type="text" class="form-control col-md-6" name="address_two" id="address_two" placeholder="Naslov...">
                        </div>
                        <!-- Postal code -->
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label text-md-right" for="postalCode">Poštna številka:</label>
                            <input type="text" class="form-control col-md-6" name="address_two" id="address_two" placeholder="Naslov...">
                        </div>
                        <!-- City -->
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label text-md-right" for="city">Mesto:</label>
                            <input type="text" class="form-control col-md-6" name="city" id="city" placeholder="Mesto...">
                        </div>
                        <!-- Country -->
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label text-md-right" for="country">Država:</label>
                                <select name="country" id="country" class="form-control col-md-6" required>
                                    <option hidden disabled selected value>--N/A--</option>
                                    <?php foreach(countries() as $item): ?>
                                        <option value="<?=$item['id']?>"><?=$item['country']?></option>
                                    <?php endforeach; ?>
                                </select>
                        </div>

                        <!-- Register button -->
                        <div class="form-group">
                            <button type="submit" name="Submit" class="btn btn-primary float-right btn-login col-sm-6" value="Registracija">Registracija</button>
                        </div>

                    </form>
                </div> <!-- card-body -->
                <div class="card-footer"> <!-- card-footer -->
                    Že imate račun? Prijavite se: <a href="./index.php?page=login">Prijava</a>
                </div> <!-- card-footer -->

            </div> <!-- card -->
        </div> <!-- col-lg-8 -->
    </div> <!-- row -->
</div> <!-- container -->