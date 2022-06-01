<div class="modal fade" id="newRequestModal" tabindex="-1" aria-labelledby="newRequestModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered ">
        <div class="modal-content bg-light">
            <div class="modal-header border-light">
                <h5 class="modal-title w-100 text-center ms-5" id="newRequestModalLabel"><?=lang('App.newRequest')?></h5>
                <button type="button" class="btn-close me-3" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="d-flex flex-column justify-content-center" method="POST" action="<?=base_url('UserController/OPCreateRequest')?>">
                    <div class="row">
                        <div class="col-auto">
                            <div class="row">
                                <label  class="form-label fs-6 fw-bold"><?=lang('App.date')?>:</label>
                            </div>
                            <div class="row">
                                <label  class="form-label fs-6 fw-bold"><?=lang('App.from')?>:</label>
                            </div>
                            <div class="row">
                                <label  class="mt-3px mb-3px fs-6 fw-bold"><?=lang('App.duration')?>:</label>
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="row">
                                <output class="fs-6 fw-bold mb-2" id="dateDisp"></output>
                            </div>
                            <div class="row">
                                <output class="fs-6 fw-bold mb-2" id="startTimeDisp"></output>
                            </div>
                            <div class="row d-flex">
                                <input type="number" min="30" max="720" step="30" value="30" class="fs-6 fw-bold w-100px px-0 ms-10px" name="duration" id="duration"></input>
                                <label  class="mt-3px mb-3px fs-6 fw-bold w-100px ps-1"><?=lang('App.min')?></label>
                            </div>
                        </div>
                    </div>

                    <div>
                        <textarea class="form-control m-1" name="requestDesc" id="requestDesc" placeholder=<?=lang('App.desc')?>></textarea>
                        <input class="form-check-input" type="checkbox" id="urgentBox">
                        <label class="form-check-label" for="urgentBox"><?=lang('App.urgent')?></label>
                    </div>

                    <div class="d-flex mt-3 justify-content-end">
                        <button type="submit" class="btn btn-primary mx-1"  data-bs-toggle="modal" data-bs-target="#messageModal"><?=lang('App.createRequest')?></button>
                        <input type="hidden" name="startTime" id="startTime" value="">
                        <input type="hidden" name="providerId" id="providerId" value=<?=$id ?>>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<button class="d-none" type="button" data-bs-toggle="modal" data-bs-target="#newRequestModal" id="newRequestModalButton"></button>