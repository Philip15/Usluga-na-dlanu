<?= $this->extend('layouts/defaultLayout') ?>

<?= $this->section('content') ?>
<div>
        <?php
            echo '<hr style=";border:none;border-top: 10px solid #adefd1;">';
            function showRequests($naslov, $requests, $op)
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
                $btnAccept = lang("App.approve");
                if($op == 3) $btnAccept = lang("App.realise");
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
                            </div>';
                        if ($op != 1)
                            echo '
                            <div class="row">
                                <label  class="form-label fs-5 fw-bold">'.lang('App.price').':</label>
                            </div>';
                        if ($op != 1)
                            echo '
                            <div class="row">
                                <label  class="form-label fs-5 fw-bold">'.lang('App.comment').':</label>
                            </div>';
                            echo'
                        </div>
                        <div class="col-auto">
                            <div class="row">
                                <output class="fs-5 fw-bold mb-2">'.esc($req->korisnik->korisnickoIme).'</output>
                            </div>
                            <div class="row">
                                <output class="fs-5 fw-bold mb-2">'.esc($req->kategorija->naziv).'</output>
                            </div>
                            <div class="row">
                                <output class="fs-4 fw-bold mb-2 pruzalac">'.esc($req->stanje).'</output>
                            </div>';
                            if ($op != 1)
                            echo '
                                <div class="row">
                                    <output class="fs-5 fw-bold mb-2">'.esc($req->cena).'</output>
                                </div>';
                            if ($op != 1)
                            echo '
                                <div class="row">
                                    <output class="fs-5 fw-bold mb-2">'.esc($req->opis).'</output>
                                </div>';
                        echo'
                        </div>
                    </div>
                    <div class="d-flex mb-3 justify-content-end">';
                    if ($op != 2 && $op != 7)
                        echo '<button type="submit" name="btnAcc" class="btn btn-success  mx-1" data-bs-toggle="modal" data-bs-target="#newOfferModal" onclick="onClick_AcceptRequest('.esc($req->idZahteva).', '.$cnt.', 1, '.$op.')">'.$btnAccept.'</button>';
                    if ($op != 2 && $op != 7)
                        echo '<button type="submit" name="btnRej" class="btn btn-danger mx-1" onclick="onClick_DenyRequest('.esc($req->idZahteva).', '.$cnt.', 1, '.$op.')">'.lang("App.deny").'</button>';                        
                    echo
                    '</div>
                    </div>';
                }
                echo '<hr style=";border:none;border-top: 10px solid #adefd1;">';
            }
            showRequests(lang('App.arrivedRequests'), $requests1, 1);
            showRequests(lang('App.madeOffers'),      $requests2, 2);
            showRequests(lang('App.acceptedOffers'),  $requests3, 3);
            showRequests(lang('App.rejectedOffers'),  $requests7, 7);
        ?>
</div>
<?=$this->include("components/newOffer")?>
<?= $this->endSection() ?>  
