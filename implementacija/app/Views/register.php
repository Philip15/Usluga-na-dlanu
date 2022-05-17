<?= $this->extend('layouts/defaultLayout') ?>

<?= $this->section('additionalhead') ?>
    <link rel="stylesheet" type="text/css"  href="<?=base_url('css/reg.css')?>" />
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="reg">
    <div class="container-reg">
        <div class="wrap-reg">
            <form>
                <span class="reg-title p-b-34 p-t-27">
                    Registracija
                </span>

                <div class="wrap-input">
                    Email adresa
                    <input class="input" type="email" name="email" placeholder="email@email.com">
                </div>

                <div class="wrap-input">
                    Ime
                    <input class="input" type="text" name="ime" placeholder="Ime">
                </div>

                <div class="wrap-input">
                    Prezime
                    <input class="input" type="text" name="prezime" placeholder="Prezime">
                </div>

                <div class="wrap-input">
                    Korisničko ime
                    <input class="input" type="text" name="username" placeholder="Korisničko ime">
                </div>

                <div class="wrap-input">
                    Lozinka
                    <input class="input" type="password" name="pass" placeholder="Lozinka">
                </div>

                <div class="wrap-input">
                    Ponovi lozinku
                    <input class="input" type="password" name="pass2" placeholder="Lozinka">
                </div>

                <div class="wrap-input">
                    <input class="input-checkbox" id="ckb1" type="checkbox" name="uslovi koriscenja">
                    <label class="label-checkbox" for="ckb1">
                        Slažem se sa uslovima korišćenja
                    </label>
                </div>
                
                <div>
                    <button class="btn-reg" data-bs-toggle="modal" data-bs-target="#KODModal" type="button">
                        Registruj se
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>