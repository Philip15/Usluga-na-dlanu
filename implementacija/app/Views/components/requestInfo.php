<div class="modal fade" id="requestInfoModal" tabindex="-1" aria-labelledby="requestInfoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered ">
        <div class="modal-content bg-light">
            <div class="modal-header border-light">
                <h5 class="modal-title w-100 text-center ms-5" id="requestInfoModalLabel"><?=lang('App.requestInfo')?></h5>
                <button type="button" class="btn-close me-3" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex flex-column justify-content-center bg-light rounded-3" id="requestInfoModalContent">
                    
                </div>
            </div>
        </div>
    </div>
</div>
<button class="d-none" type="button" data-bs-toggle="modal" data-bs-target="#requestInfoModal" id="requestInfoModalButton"></button>