<?= $this->extend('layouts/defaultLayout') ?>

<?= $this->section('additionalhead') ?>
    <link rel="stylesheet" type="text/css"  href="<?=base_url('css/calendar.css')?>" />
    <link rel="stylesheet" type="text/css"  href="<?=base_url('css/profile.css')?>" />
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
    for ($i=0; $i<count($requests); $i++) {
        $req = $requests[$i];
        echo 
        '<div class="d-flex flex-column container justify-content-center bg-light rounded-3 px-4 my-5" id="k1">
        <div class="row mt-4">
            <div class="col-auto">
                <div class="row">
                    <label class="form-label fs-4 fw-bold">Korisničko ime:</label>
                </div>
                <div class="row">
                    <label class="form-label fs-5 fw-bold">Ime i prezime:</label>
                </div>
                <div class="row">
                    <label class="form-label fs-5 fw-bold">Email:</label>
                </div>
                <div class="row">
                    <label class="form-label fs-5 fw-bold">Kategorija:</label>
                </div>
            </div>
            <div class="col-auto">
                <div class="row">
                    <output class="fs-4 fw-bold mb-2 pruzalac">'.esc($req->korisnickoIme).'</output>
                </div>
                <div class="row">
                    <output class="fs-5 fw-bold mb-2">'.esc($req->ime).lang('App.blank').esc($req->prezime).'</output>
                </div>
                <div class="row">
                    <output class="fs-5 fw-bold mb-2">'.esc($req->email).'</output>
                </div>
                <div class="row">
                    <output class="fs-5 fw-bold mb-2">'.esc(ucfirst($naziviKategorija[$i])).'</output>
                </div>
            </div>
        </div>
        <div class="d-flex mb-3 justify-content-end">
            <form method="POST" action="'.esc(base_url('AdminController/OPApproveRequest/?korisnickoime='.esc($req->korisnickoIme))).'">
                <button type="submit" class="btn btn-success mx-1" onclick="onClick_AdminAcceptRequest(1)">'.lang('App.approve').'</button>
            </form>
            <form method="POST" action="'.esc(base_url('AdminController/OPDenyRequest/?korisnickoime='.esc($req->korisnickoIme))).'">
                <button type="sumbit" class="btn btn-danger mx-1" onclick="onClick_AdminDenyRequest(1)">'.lang('App.deny').'</button>
            </form>
            </div>
        </div>';
        
    }
    ?>
<?= $this->endSection() ?>    

    
