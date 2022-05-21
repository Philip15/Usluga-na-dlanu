<?= $this->extend('layouts/defaultLayout') ?>

<?= $this->section('content') ?>
    <div>
        <?php
            for ($i=0; $i<count($requests); $i++) 
            {
                $req = $requests[$i];
                echo 
                '<div class="d-flex flex-column container justify-content-center bg-light rounded-3 px-4 my-5">
                    <div class="row mt-4">
                        <div class="col-auto">
                            <div class="row">
                                <label class="form-label fs-4 fw-bold">'.lang('App.username').':</label>
                            </div>
                            <div class="row">
                                <label class="form-label fs-5 fw-bold">'.lang('App.firstAndLastName').':</label>
                            </div>
                            <div class="row">
                                <label class="form-label fs-5 fw-bold">'.lang('App.emailAddress').':</label>
                            </div>
                            <div class="row">
                                <label class="form-label fs-5 fw-bold">'.lang('App.category').':</label>
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="row">
                                <output class="fs-4 fw-bold mb-2">'.esc($req->korisnickoIme).'</output>
                            </div>
                            <div class="row">
                                <output class="fs-5 fw-bold mb-2">'.esc($req->ime).' '.esc($req->prezime).'</output>
                            </div>
                            <div class="row">
                                <output class="fs-5 fw-bold mb-2">'.esc($req->email).'</output>
                            </div>
                            <div class="row">
                                <output class="fs-5 fw-bold mb-2">'.esc(ucfirst($req->kategorija->naziv)).'</output>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex mb-3 justify-content-end">
                        <a href="'.esc(base_url('AdminController/OPApproveRequest/?id=' . esc($req->idKorisnika))).'" class="btn btn-success mx-1">'.lang('App.approve').'</a>
                        <a href="'.esc(base_url('AdminController/OPDenyRequest/?id=' . esc($req->idKorisnika))).'" class="btn btn-danger mx-1">'.lang('App.deny').'</a>
                    </div>
                </div>';
            }
        ?>
    </div>
<?= $this->endSection() ?>    

    
