<?= $this->extend('layouts/defaultLayout') ?>

<?php $user = session('user'); ?>

<?= $this->section('additionalhead') ?>
<!--Location picker dependencies-->
<script type="text/javascript" src='http://maps.google.com/maps/api/js?sensor=false&libraries=places'></script>
<script src="<?=base_url('js/locationpicker.jquery.min.js')?>"></script>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="container rounded bg-white mt-5 mb-5">
    <div class="row">
        <div class="col-md-auto border-right">
            <div class="d-flex flex-column align-items-center text-center m-3 my-5 w-300px">
                <div id="imgDiv">
                    <img src="<?php if (isset($user->profilnaSlika) && $user->profilnaSlika !== null) {echo 'data:image/jpeg;base64,' . base64_encode($user->profilnaSlika); } else {echo base_url('res/placeholder-user.jpg');} ?>" class="rounded-circle" width="300px" height="300px">
                </div>
                <span class="font-weight-bold fs-5 mt-3"><?= $user->ime ?>&nbsp;<?= $user->prezime ?></span>
                <span class="text-muted"><?= $user->email ?></span>
                <form method="POST" enctype="multipart/form-data" action="<?= base_url('UserController/OPchangeProfilePicture') ?>" class="d-flex mt-3">
                    <label for="profilePicture" class="btn btn-secondary"><?= lang('App.chooseProfilePicture') ?></label>
                    <input type="file" name="profilePicture" id="profilePicture" class="d-none" onchange="onChange_UploadPicture()">
                    <button class="btn btn-primary ms-2" type="submit" id="changePictureApply"><?= lang('App.apply') ?></button>
                </form>
            </div>
        </div>
        <div class="col-md border-right">
            <div class="p-3 py-5">
                <h4><?= lang('App.profileOverview') ?></h4>
                <form method="POST" action="<?= base_url('UserController/OPsaveChanges') ?>">
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label class="form-label"><?= lang('App.name') ?></label>
                            <input type="text" name="ime" class="form-control" placeholder="<?= lang('App.name') ?>" value="<?= empty(session('podaci')) || session('podaci')['ime'] == null ? $user->ime : session('podaci')['ime'] ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><?= lang('App.surname') ?></label>
                            <input type="text" name="prezime" class="form-control" placeholder="<?= lang('App.surname') ?>" value="<?= empty(session('podaci')) || session('podaci')['prezime'] == null ? $user->prezime : session('podaci')['prezime'] ?>">
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <label class="form-label"><?= lang('App.username') ?></label>
                            <input type="text" name="username" class="form-control" placeholder="<?= lang('App.username') ?>" value="<?= empty(session('podaci')) || session('podaci')['korisnickoIme'] == null ? $user->korisnickoIme : session('podaci')['korisnickoIme'] ?>">
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <label class="form-label"><?= lang('App.emailAddress') ?></label>
                            <input type="email" name="email" class="form-control" placeholder="<?= lang('App.regEmail') ?>" value="<?= empty(session('podaci')) || session('podaci')['email'] == null ? $user->email : session('podaci')['email'] ?>">
                        </div>
                    </div>
                        <?php
                            if ($user->role() == 'provider') 
                            {
                                echo
                                '<div class="row mt-3">
                                    <div class="col-md-12">
                                        <label class="form-label">'.lang('App.providerCategory').'</label>
                                        <select name="kategorija" class="form-select">';
                                        $idKategorije=empty(session('podaci')) || session('podaci')['idKategorije'] == null ? $user->kategorija->idKategorije : session('podaci')['idKategorije'];
                                        foreach ($categories as $category) 
                                        {
                                            echo '<option '.(($idKategorije == $category->idKategorije)?'selected':'').' value="' . esc($category->idKategorije) . '">' . esc(ucfirst($category->naziv)) . '</option>';
                                        }
                                        echo 
                                        '</select>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <label class="form-label">'.lang('App.providerAddress').'</label>
                                        <input type="text" class="form-control" name="adresa" value="'.(empty(session('podaci')) || session('podaci')['adresa'] == null ? $user->adresa : session('podaci')['adresa']).'">
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <input type="hidden" name="lat" id="latInput" value="'.(empty(session('podaci')) || session('podaci')['lat'] == null ? $user->lat : session('podaci')['lat']).'">
                                        <input type="hidden" name="lon" id="lonInput" value="'.(empty(session('podaci')) || session('podaci')['lon'] == null ? $user->lon : session('podaci')['lon']).'">
                                        <div id="locationPicker" style="width: 100%; height: 600px;"></div>
                                    </div>
                                </div>';
                            }
                        ?>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <label class="form-label"><?= lang('App.additionalInformation') ?></label>
                            <input type="text" name="dodatneInformacije" class="form-control" value="<?= empty(session('podaci')) || session('podaci')['opis'] == null ? $user->opis : session('podaci')['opis'] ?>">
                        </div>
                    </div>
                    <?php
                        if (session('errorText') !== null) 
                        {
                            echo '<p class="px-2 mt-3 mb-0 text-danger">'.esc(session('errorText')).'</p>';
                        }
                    ?>
                    <div class="mt-3 text-center">
                        <button class="btn btn-primary" type="submit"><?= lang('App.saveChanges') ?></button>
                    </div>
                </form>
                <form method="POST" action="<?= base_url('UserController/OPupdatePassword') ?>">
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <label class="form-label"><?= lang('App.oldPassword') ?></label>
                            <input type="password" name="oldPassword" class="form-control" placeholder="<?= lang('App.oldPassword') ?>" value="<?= empty(session('podacilozinka')) || session('podacilozinka')['staraLozinka'] == null ? "" : session('podacilozinka')['staraLozinka'] ?>">
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <label class="form-label"><?= lang('App.newPassword') ?></label>
                            <input type="password" name="newPassword" class="form-control" placeholder="<?= lang('App.newPassword') ?>" value="<?= empty(session('podacilozinka')) || session('podacilozinka')['lozinka'] == null ? "" : session('podacilozinka')['lozinka'] ?>">
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12"><label class="form-label">
                            <?= lang('App.newPasswordAgain') ?></label>
                            <input type="password" name="newPasswordAgain" class="form-control" placeholder="<?= lang('App.newPasswordAgain') ?>" value="<?= empty(session('podacilozinka')) || session('podacilozinka')['lozinka2'] == null ? "" : session('podacilozinka')['lozinka2'] ?>">
                        </div>
                    </div>
                    <?php 
                        if (session('errorTextNewPassword') !== null) 
                        {
                            echo '<p class="px-2 mt-3 mb-0 text-danger">'.esc(session('errorTextNewPassword')).'</p>';
                        }
                    ?>
                    <div class="mt-3 text-center">
                        <button class="btn btn-primary" type="submit"><?= lang('App.updateNewPassword')?> </button>
                    </div>
                </form>
            </div>
        </div>
        <?php
            if ($user->role() == 'user') 
            {
                echo
                '<div class="col-md-4">
                    <div class="p-3 py-5">
                        <h4>'.lang('App.profileUpdate').'</h4>
                        <form method="POST" action='.base_url('UserController/OPconvertProfile').'>   
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <label class="form-label">'.lang('App.providerCategory').'</label>
                                    <select name="kategorija" class="form-select">
                                        <option disabled selected>'.lang('App.choose').'</option>';
                                        foreach ($categories as $category) 
                                        {
                                            echo '<option value="'.esc($category->idKategorije).'">'.esc(ucfirst($category->naziv)).'</option>';
                                        }
                        echo 
                                    '</select>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <label class="form-label">'.lang('App.providerAddress').'</label>
                                    <input name="adresaPoslovanja" type="text" class="form-control" placeholder="'.lang('App.profileAddress').'" value="'
                                    .(empty(session('podaciKonverzija')) || session('podaciKonverzija')['adresa'] == null ? '' : session('podaciKonverzija')['adresa']).'">
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <input type="hidden" name="lat" id="latInput" value="'.(empty(session('podaciKonverzija')) || session('podaciKonverzija')['lat'] == null ? 44.816667 : session('podaciKonverzija')['lat']).'">
                                    <input type="hidden" name="lon" id="lonInput" value="'.(empty(session('podaciKonverzija')) || session('podaciKonverzija')['lon'] == null ? 20.466667 : session('podaciKonverzija')['lon']).'">
                                    <div id="locationPicker" style="width: 100%; height: 240px;"></div>
                                </div>
                            </div>';
                            if (session('errorTextConversion') !== null) 
                            {
                                echo '<p class="px-2 mt-3 mb-0 text-danger">'.esc(session('errorTextConversion')).'</p>';
                            }
                        echo 
                            '<div class="mt-3 text-center">
                                <button class="btn btn-primary" type="submit">' . lang('App.convertProfile') . '</button>
                            </div>
                        </form>
                    </div>
                </div>';
            }
        ?>
    </div>
</div>

<?php 
    if (session('alertErrorText') != null) 
    {
        echo '<script > window.onload = window.setTimeout(function(){alert("'.esc(session('alertErrorText')).'");}, 100);</script>';
    }
?>

<?= $this->endSection() ?>