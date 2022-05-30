<?= $this->extend('layouts/defaultLayout') ?>

<?= $this->section('content') ?>
    <div class="text-center d-flex justify-content-center align-items-center mt-5">
        <div class="rounded-4 w-500px p-5 bg-light">
            <form method="POST" action="<?= base_url('GuestController/OPregister')?>">
                <div class="text-uppercase fs-3 mb-4">
                    <?=lang('App.registerTitle')?>
                </div>
                <div class="mb-3">
                    <?=lang('App.emailAddress')?>
                    <input class="form-control" type="email" name="email" placeholder="<?=lang('App.regEmail')?>" value="<?=empty(session('podaci')) ? '' : esc(session('podaci')['email'])?>">
                </div>
                <div class="mb-3">
                    <?=lang('App.name')?>
                    <input class="form-control" type="text" name="ime" placeholder="<?=lang('App.name')?>" value="<?=empty(session('podaci')) ? '' : esc(session('podaci')['ime'])?>">
                </div>
                <div class="mb-3">
                    <?=lang('App.surname')?>
                    <input class="form-control" type="text" name="prezime" placeholder="<?=lang('App.surname')?>" value="<?=empty(session('podaci')) ? '' : esc(session('podaci')['prezime'])?>">
                </div>
                <div class="mb-3">
                    <?=lang('App.username')?>
                    <input class="form-control" type="text" name="username" placeholder="<?=lang('App.username')?>" value="<?=empty(session('podaci')) ? '' : esc(session('podaci')['korisnickoIme'])?>">
                </div>
                <div class="mb-3">
                    <?=lang('App.password')?>
                    <input class="form-control" type="password" name="password" placeholder="<?=lang('App.password')?>" value="<?=empty(session('podaci')) ? '' : esc(session('podaci')['lozinka'])?>">
                </div>
                <div class="mb-3">
                    <?=lang('App.regPasswordAgain')?>
                    <input class="form-control" type="password" name="password2" placeholder="<?=lang('App.password')?>" value="<?=empty(session('podaci')) ? '' : esc(session('podaci')['lozinka2'])?>">
                </div>
                <div class="mb-3">
                    <input class="form-check-input" id="ckb1" type="checkbox" name="uslovi_koriscenja">
                    <label class="form-check-label" for="ckb1"><?=lang('App.termsOfUse')?></label>
                </div>
                <?php
                    if(session('errorText') !== null)
                    {
                        echo '<p class="px-2 mt-2 mb-0 text-danger">'.esc(session('errorText')).'</p></br>';
                    }
                ?>
                <div>
                    <button type="submit" class="btn btn-outline-primary px-3 py-2">
                        <?=lang('App.register')?>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <?php 
        if (session('alertErrorText') != null) 
        {
            echo '<script > window.onload = window.setTimeout(function(){alert("'.esc(session('alertErrorText')).'");window.location.href=new URL(window.location.href).origin}, 100);</script>';
        }
    ?>

<?= $this->endSection() ?>