<!--
  * @author Lazar Premović  2019/0091
-->

<?= $this->extend('layouts/defaultLayout') ?>

<?= $this->section('content') ?>
    
    <div class="container">
        <?php
            if(count($reviews)==0)
            {
                echo '<div class="container px-4 my-5">';
                echo '<h5 class="text-light  text-center">'.lang('App.noReviews').'</h5>';
                echo '</div>';
            }
            foreach ($reviews as $review)
            {
                echo
                '<form class="d-flex flex-column container justify-content-center bg-light rounded-3 px-4 my-5" method="POST" action="'.base_url('UserController/OPpostReview').'">
                    <div class="row mt-4">
                        <div class="col-auto">
                            <div class="row">
                                <label  class="form-label fs-4 fw-bold">'.lang('App.provider').':</label>
                            </div>
                            <div class="row">
                                <label  class="form-label fs-5 fw-bold">'.lang('App.serviceDate').':</label>
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="row">
                                <output class="fs-4 fw-bold mb-2">'.esc($review->pruzalac->ime . ' ' . $review->pruzalac->prezime).'</output>
                            </div>
                            <div class="row">
                                <output class="fs-5 fw-bold mb-2">'.esc(date('d/m/Y',strtotime($review->termini[0]->datumVremePocetka))).'</output>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-auto">
                            <label  class="form-label fs-5 fw-bold">'.lang('App.ratingS').':</label>
                        </div>
                        <div class="col-auto">
                            <p class="fs-5 fw-bold stars">
                                <i class="bi bi-star onestar"></i><i class="bi bi-star twostar"></i><i class="bi bi-star threestar"></i><i class="bi bi-star fourstar"></i><i class="bi bi-star fivestar"></i><input type="hidden" class="rating" name="rating" value="0"></input>
                            </p>
                        </div>
                    </div>
                    <textarea class="form-control mb-3" rows="5" name="comment" placeholder="'.lang('App.optionalComment').'"></textarea>
                    <div class="d-flex mb-3 justify-content-end">
                        <button type="button" class="btn btn-danger mx-1" onclick="onClick_Remove(\''.lang('App.confirmRemove').'\',\''.base_url('UserController/OPremoveReview/?id='.$review->idZahteva).'\')">'.lang('App.removeCategory').'</button>
                        <button type="submit" class="btn btn-primary  mx-1">'.lang('App.postReview').'</button>
                        <input type="hidden" name="id" value="'.esc($review->idZahteva).'">                        
                    </div>
                </form>';
            }
        ?>
    </div>

    <?php 
        if (session('alertErrorText') != null) 
        {
            echo '<script > window.onload = window.setTimeout(function(){alert("'.esc(session('alertErrorText')).'");}, 100);</script>';
        }
    ?>

<?= $this->endSection() ?>
