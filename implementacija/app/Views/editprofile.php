<?= $this->extend('layouts/defaultLayout') ?>

<?= $this->section('additionalhead') ?>
<link rel="stylesheet" type="text/css" href="<?= base_url('css/profile.css') ?>" />
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php $user = session('user'); ?>

<div class="container rounded bg-white mt-5 mb-5">
    <div class="row">
        <div class="col-md-3 border-right">
            <div class="d-flex flex-column align-items-center text-center p-3 py-5">
                <img width="150px" src="<?php if (isset($user->profilnaSlika) && $user->profilnaSlika !== null) {echo 'data:image/jpeg;base64,' . base64_encode($user->profilnaSlika); } else {echo base_url('res/placeholder-user.jpg');} ?>" class="rounded-circle">
                <span class="font-weight-bold" style="font-size:larger"><?= $user->ime ?>&nbsp;<?= $user->prezime ?></span>
                <span class="text-black-50"><?= $user->email ?></span><br>
                <form method="POST" enctype="multipart/form-data" action="<?= base_url('UserController/OPchangeProfilePicture') ?>">
                    <label for="profilePicture" class="btn btn-secondary"><?= lang('App.chooseProfilePicture') ?></label>
                    <input type="file" style="visibility:hidden;" name="profilePicture" id="profilePicture">
                    <button class="btn btn-primary profile-button" type="submit"><?= lang('App.changeProfilePicture') ?></button>
                </form>
            </div>
        </div>
        <?php
        if ($user->role() == 'provider') {
            echo '<div class="col-md-6 border-right">';
        } else {
            echo '<div class="col-md-5 border-right">';
        }
        ?>
        <div class="p-3 py-5">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="text-right"><?= lang('App.profileOverview') ?></h4>
            </div>
            <form method="POST" action="<?= base_url('UserController/OPsaveChanges') ?>">
                <div class="row mt-2">
                    <div class="col-md-6">
                        <label class="labels"><?= lang('App.name') ?></label>
                        <input type="text" name="ime" class="form-control input" placeholder="<?= lang('App.name') ?>" value="<?= empty(session('podaci')) || session('podaci')['ime'] == null ? $user->ime : session('podaci')['ime'] ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="labels"><?= lang('App.surname') ?></label>
                        <input type="text" name="prezime" class="form-control" placeholder="<?= lang('App.surname') ?>" value="<?= empty(session('podaci')) || session('podaci')['prezime'] == null ? $user->prezime : session('podaci')['prezime'] ?>">
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-12">
                        <label class="labels"><?= lang('App.username') ?></label>
                        <input type="text" name="username" class="form-control input" placeholder="<?= lang('App.username') ?>" value="<?= empty(session('podaci')) || session('podaci')['korisnickoIme'] == null ? $user->korisnickoIme : session('podaci')['korisnickoIme'] ?>">
                    </div>
                    <div class="col-md-12">
                        <label class="labels"><?= lang('App.emailAddress') ?></label>
                        <input type="email" name="email" class="form-control input" placeholder="<?= lang('App.regEmail') ?>" value="<?= empty(session('podaci')) || session('podaci')['email'] == null ? $user->email : session('podaci')['email'] ?>">
                    </div>
                <?php
                if ($user->role() == 'provider') {
                    echo
                    '<div class="col-md-12">
                        <label class="labels">' . lang('App.providerCategory') . '</label>
                        <select name="kategorija" class="form-select form-select-md mb-3" aria-label=".form-select-lg example">
                            <option selected>' . esc(ucfirst($user->kategorija->naziv)) . '</option>';
                    foreach ($categories as $category) 
                    {
                        if ($user->kategorija->idKategorije != $category->idKategorije)
                        {
                            echo '<option value="' . esc($category->idKategorije) . '">' . esc(ucfirst($category->naziv)) . '</option>';
                        }
                    }
                    echo 
                        '</select>
                    </div>
                    <div class="col-md-12">
                        <label class="labels-sel">' . lang('App.providerAddress') . '</label>
                        <input type="text" class="form-control" name="adresa" value="' . $user->adresa . '">
                    </div>';
                }
                ?>
                    <div class="col-md-12">
                        <label class="labels"><?= lang('App.additionalInformation') ?></label>
                        <input type="text" name="dodatneInformacije" class="form-control" value="<?= $user->opis ?>">
                    </div>
                </div>
            <?php
            if (session('errorText') !== null) 
            {
                echo '<p class="px-2 mt-2 mb-0 text-danger">' . esc(session('errorText')) . '</p></br>';
            }
            ?>
                <div class="mt-5 text-center">
                    <button class="btn btn-primary profile-button" type="submit"><?= lang('App.saveChanges') ?></button>
                </div>
            </form>
            <form method="POST" action="<?= base_url('UserController/OPudpatePassword') ?>">
                <div class="row mt-3">
                    <div class="col-md-12">
                        <label class="labels"><?= lang('App.newPassword') ?></label>
                        <input type="text" name="newPassword" class="form-control input" placeholder="<?= lang('App.newPassword') ?>" value="<?= empty(session('podaciNovaLozinka')) || session('podaciNovaLozinka')['lozinka'] == null ? "" : session('podaciNovaLozinka')['lozinka'] ?>">
                    </div>
                    <div class="col-md-12"><label class="labels">
                        <?= lang('App.newPasswordAgain') ?></label>
                        <input type="text" name="newPasswordAgain" class="form-control input" placeholder="<?= lang('App.newPasswordAgain') ?>" value="<?= empty(session('podaciNovaLozinka')) || session('podaciNovaLozinka')['lozinka2'] == null ? "" : session('podaciNovaLozinka')['lozinka2'] ?>">
                    </div>
                </div>
                <?php 
                if (session('errorTextNewPassword') !== null) 
                {
                    echo '<p class="px-2 mt-2 mb-0 text-danger">' . esc(session('errorTextNewPassword')) . '</p></br>';
                }
                ?>
                <div class="mt-5 text-center">
                    <button class="btn btn-primary profile-button" type="submit"><?= lang('App.updateNewPassword')?> </button>
                </div>
            </form>
        </div>
    </div>

    <?php
    if ($user->role() == 'user' || $user->role() == 'admin') {
        echo
        '<div class="col-md-4">
            <div class="p-3 py-5">
                <div class="d-flex justify-content-between align-items-center experience">
                    <h4>' . lang('App.profileUpdate') . '<h4>
                </div>
                <br>
                <form method="POST" action=' . base_url('UserController/OPconvertProfile') . '>   
                    <div class="col-md-12">
                        <label class="labels">' . lang('App.providerCategory') . '</label>
                        <select name="kategorija" class="form-select form-select-md mb-3" aria-label=".form-select-lg example">
                            <option disabled selected>' . lang('App.choose') . '</option>';

                        foreach ($categories as $category) 
                        {
                            echo '<option value="' . esc($category->idKategorije) . '">' . esc(ucfirst($category->naziv)) . '</option>';
                        }

            	echo '</select>
                    <br>
                    <div class="col-md-12">
                        <label class="labels-sel">' . lang('App.providerAddress') . '</label>
                        <input name="adresaPoslovanja" type="text" class="form-control" placeholder="';
                        echo empty(session('podaciKonverzija')) || session('podaciKonverzija')['adresa'] == null ? lang('App.profileAddress') : session('podaciKonverzija')['adresa'];
                        echo '"></div>
                        <div class="mt-5 text-center">
                            <button class="btn btn-primary profile-button" type="submit">' . lang('App.convertProfile') . '</button>
                        </div>
                    </div>
                </form>';
        if (session('errorTextConversion') !== null) {
            echo '<p class="px-2 mt-2 mb-0 text-danger">' . esc(session('errorTextConversion')) . '</p> </br>';
        }
        echo 
            '</div> 
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