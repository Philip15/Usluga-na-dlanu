<?= $this->extend('layouts/defaultLayout') ?>

<?= $this->section('additionalhead') ?>
    <link rel="stylesheet" type="text/css"  href="<?=base_url('css/reg.css')?>" />
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="reg">
    <div class="container-reg">
        <div class="wrap-reg">
            <form method="POST" action="<?= base_url('GuestController/OPregister')?>">
                <span class="reg-title">
                    <?=lang('App.registerTitle')?>
                </span>
            
                <div class="wrap-input">
                <?=lang('App.emailAddress')?>
                    <input class="input" type="email" name="email" placeholder="<?=lang('App.regEmail')?>" value="<?=empty(session('podaci')) ? '' : session('podaci')['email']?>">
                </div>

                <div class="wrap-input">
                    <?=lang('App.name')?>
                    <input class="input" type="text" name="ime" placeholder="<?=lang('App.name')?>" value="<?=empty(session('podaci')) ? '' : session('podaci')['ime']?>">
                </div>

                <div class="wrap-input">
                    <?=lang('App.surname')?>
                    <input class="input" type="text" name="prezime" placeholder="<?=lang('App.surname')?>" value="<?=empty(session('podaci')) ? '' : session('podaci')['prezime']?>">
                </div>

                <div class="wrap-input">
                    <?=lang('App.username')?>
                    <input class="input" type="text" name="username" placeholder="<?=lang('App.username')?>" value="<?=empty(session('podaci')) ? '' : session('podaci')['korisnickoIme']?>">
                </div>

                <div class="wrap-input">
                    <?=lang('App.password')?>
                    <input class="input" type="password" name="password" placeholder="<?=lang('App.password')?>" value="<?=empty(session('podaci')) ? '' : session('podaci')['lozinka']?>">
                </div>

                <div class="wrap-input">
                    <?=lang('App.regPasswordAgain')?>
                    <input class="input" type="password" name="password2" placeholder="<?=lang('App.password')?>" value="<?=empty(session('podaci')) ? '' : session('podaci')['lozinka2']?>">
                </div>

                <div class="wrap-input">
                    <input class="input-checkbox" id="ckb1" type="checkbox" name="uslovi_koriscenja">
                    <label class="label-checkbox" for="ckb1">
                    <?=lang('App.termsOfUse')?>
                    </label>
                </div>
                <?php
                    if(session('errorText') !== null)
                    {
                       echo '<p class="px-2 mt-2 mb-0 text-danger">'.esc(session('errorText')).'</p>.</br>';

                    }
                ?>
            
                <div>
                    <button type="submit" class="btn-reg" type="button">
                        <?=lang('App.register')?>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>