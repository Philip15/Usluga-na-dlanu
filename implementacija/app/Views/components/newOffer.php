<div class="modal fade" id="newOfferModal" tabindex="-1" aria-labelledby="newOfferModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered ">
        <div class="modal-content bg-light">
            <div class="modal-header border-light">
                <h5 class="modal-title w-100 text-center ms-5" id="newOfferModalLabel"><?=lang('App.newOffer')?></h5>
                <button type="button" class="btn-close me-3" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="d-flex flex-column justify-content-center" method="POST" action="<?=base_url('ProviderController/OPCreateOffer')?>">
                    <div class="row m-0">
                        <input class="form-control" type="number" name="priceVal" id="priceVal" placeholder=<?= lang("App.price")?>>
                        <textarea class="form-control mt-2" name="offerDesc" id="offerDesc" placeholder=<?=lang('App.desc')?>></textarea>
                        <?php
                            if(session('errorTextPrice') !== null)
                            {
                                echo '<p class="px-2 mt-2 mb-0 text-danger" id="priceError">'.esc(session('errorTextPrice')).'</p>';
                            }
                        ?>
                    </div>
                    <div class="d-flex mt-3 justify-content-end">
                        <button type="submit" class="btn btn-primary mx-1"><?=lang('App.createOffer')?></button>
                        <input type="hidden" id="idZ" name="idZ" value="<?= session('errorId')??''?>">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<button class="d-none" type="button" data-bs-toggle="modal" data-bs-target="#newOfferModal" id="newOfferModalButton"></button>