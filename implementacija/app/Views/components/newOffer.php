<div class="modal fade" id="newOfferModal" tabindex="-1" aria-labelledby="newOfferModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered ">
        <div class="modal-content bg-light">
            <div class="modal-header border-light mt-3">
                <h5 class="modal-title w-100 text-center ms-5" id="newOfferModalLabel"><?=lang('App.newOffer')?></h5>
                <button type="button" class="btn-close me-3" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="d-flex flex-column justify-content-center" method="POST" action="<?=base_url('ProviderController/OPCreateOffer')?>">

                    <div>
                        <input class="form-control m-1" type="text" name="priceVal" id="priceVal" placeholder=<?= lang("App.price")?>>
                        <textarea class="form-control m-1" name="offerDesc" id="offerDesc" placeholder=<?=lang('App.desc')?>></textarea>
                    </div>

                    <div class="d-flex mt-3 justify-content-end">
                    <?php 
                    if (session('errorTextPrice') !== null) 
                    {
                        echo '<p class="px-2 mt-2 mb-0 text-danger">' . esc(session('errorTextPrice')) . '</p></br>';
                    }?>
                        <button type="submit" class="btn btn-primary mx-1"><?=lang('App.createOffer')?></button>
                        <input type="hidden" id="idZ" name="idZ">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>