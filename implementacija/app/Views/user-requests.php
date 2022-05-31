<?= $this->extend('layouts/defaultLayout') ?>

<?= $this->section('content') ?>
<div>
        <?php
            echo '<hr style=";border:none;border-top: 10px solid #adefd1;">';
            function showRequests($naslov, $requests)
            {
                static $cnt = 0;
                echo
                '<h1 class="text-center" style="color:white">'.$naslov.'</h1>';
                if(count($requests) == 0)
                {
                    echo '<h5 class="text-light  text-center">'.lang('App.noRequests').'</h5>';
                    echo '<hr style=";border:none;border-top: 10px solid #adefd1;">';
                    return;
                }
                for ($i=0; $i<count($requests); $i++) 
                {
                    $req = $requests[$i];
                    $cnt++;
                    echo
                    '
                    <div class="d-flex flex-column container justify-content-center bg-light rounded-3 px-4 my-5" id="k'.$cnt.'">
                    <div class="row mt-4">
                        <div class="col-auto">
                            <div class="row">
                                <label  class="form-label fs-4 fw-bold">'.lang('App.username').':</label>
                            </div>
                            <div class="row">
                                <label  class="form-label fs-5 fw-bold">'.lang('App.category').':</label>
                            </div>
                            <div class="row">
                                <label  class="form-label fs-5 fw-bold">'.lang('App.period').':</label>
                            </div>
                            <div class="row">
                                <label  class="form-label fs-5 fw-bold">'.lang('App.price').':</label>
                            </div>
                            <div class="row">
                                <label  class="form-label fs-5 fw-bold">'.lang('App.comment').':</label>
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="row">
                                <output class="fs-5 fw-bold mb-2">'.esc($req->pruzalac->korisnickoIme).'</output>
                            </div>
                            <div class="row">
                                <output class="fs-5 fw-bold mb-2">'.esc($req->kategorija->naziv).'</output>
                            </div>
                            <div class="row">
                                <output class="fs-4 fw-bold mb-2 pruzalac">'.esc($req->stanje).'</output>
                            </div>
                            <div class="row">
                                <output class="fs-5 fw-bold mb-2">'.esc($req->cena).'</output>
                            </div>
                            <div class="row">
                                <output class="fs-5 fw-bold mb-2">'.esc($req->opis).'</output>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex mb-3 justify-content-end">
                        <button type="submit" name="btnAcc" class="btn btn-success  mx-1" onclick="onClick_AcceptRequest('.esc($req->idZahteva).', '.$cnt.', 0)">Prihvati</button>
                        <button type="submit" name="btnRej" class="btn btn-danger mx-1" onclick="onClick_DenyRequest('.esc($req->idZahteva).', '.$cnt.', 0)">Odbij</button>
                        
                    </div>
                    </div>';
                }
                echo    '<hr style=";border:none;border-top: 10px solid #adefd1;">';
            }
            showRequests('Upuceni zahtevi', $requests1);
            showRequests('Pristigle ponude', $requests2);
            showRequests('Prihvacene ponude', $requests3);
            showRequests('Odbijeni zahtevi', $requests6);
        ?>
</div>
<?= $this->endSection() ?>    

    
