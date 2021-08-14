<div class="card">
    <div class="card-body">
        <form id="account-form" name="account-form">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="fname">Ime<span class="asterisk">*</span></label>
                    <input type="text" class="form-control" id="fname" name="fname" value="<?=$user['first_name']?>" data-original-value="<?=$user['first_name']?>" disabled required>
                </div>
                <div class="form-group col-md-6">
                    <label for="lname">Priimek<span class="asterisk">*</span></label>
                    <input type="text" class="form-control" id="lname" name="lname" value="<?=$user['last_name']?>" data-original-value="<?=$user['last_name']?>" disabled required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="phoneNumber">Telefon</label>
                    <input type="text" class="form-control" id="phoneNumber" name="phoneNumber" maxlength="20" value="<?=$user['phone_number']?>" data-original-value="<?=$user['phone_number']?>" disabled>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="address">Naslov<span class="asterisk">*</span></label>
                    <input type="text" class="form-control" id="address" name="address" value="<?=$user['address']?>" data-original-value="<?=$user['address']?>" disabled required>
                </div>
                <div class="form-group col-md-6">
                    <label for="addressTwo">Naslov 2</label>
                    <input type="text" class="form-control" id="addressTwo" name="addressTwo" value="<?=$user['address2']?>" data-original-value="<?=$user['address2']?>" disabled>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-5">
                    <label for="city">City<span class="asterisk">*</span></label>
                    <input type="text" class="form-control" id="city" name="city" value="<?=$city['city']?>" data-original-value="<?=$city['city']?>" disabled required>
                </div>
                <div class="form-group col-md-4">
                    <label for="postalCode">Poštna številka<span class="asterisk">*</span></label>
                    <input type="text" class="form-control" id="postalCode" name="postalCode" maxlength="10" value="<?=$city['postal_code']?>" minlength="4" data-original-value="<?=$city['postal_code']?>" disabled required>
                </div>
                <div class="form-group col-md-3">
                    <label for="country">Država<span class="asterisk">*</span></label>
                    <select id="country" class="form-control" name="country" disabled required>
                        <?php foreach($countries as $country): ?>
                            <?php if($country['id'] == $city['country_id']): ?>
                                <option value="<?=$country['id']?>" selected><?=$country['country']?></option>
                            <?php else: ?>
                                <option value="<?=$country['id']?>"><?=$country['country']?></option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="btn_group edit-button float-right">
                <button id="modify" class="btn btn-info btn-next" name="modify">
                        <i class="fas fa-wrench"></i>
                </button>
                <button id="save" class="btn btn-success btn-next" name="save" hidden disabled>
                    <i class="fas fa-save"></i>
                </button>
                <button id="cancel" class="btn btn-danger btn-next" name="cancel" hidden disabled>
                    Prekliči
                </button>
            </div>
        </form>

    </div>
</div>