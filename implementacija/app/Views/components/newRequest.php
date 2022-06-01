<!--
  * @author Lazar Premović  2019/0091
  * @author Filip Janjić    2019/0116
-->

<div class="modal fade" id="newRequestModal" tabindex="-1" aria-labelledby="newRequestModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered ">
        <div class="modal-content bg-light">
            <div class="modal-header border-light">
                <h5 class="modal-title w-100 text-center ms-5" id="newRequestModalLabel"><?=lang('App.createRequest')?></h5>
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

                    <div class="row m-0 mt-3">
                        <textarea class="form-control" name="requestDesc" id="requestDesc" placeholder=<?=lang('App.desc')?>></textarea>
                        <div class="form-check mt-2">
                            <label class="form-check-label fs-6 fw-bold" for="urgentBox"><?=lang('App.urgent')?></label>
                            <input class="form-check-input" type="checkbox" id="urgentBox" name="urgentBox">
                        </div>
                    </div>
                    <?php
                            if(session('errorTextCreate') !== null)
                            {
                                echo '<p class="px-2 mt-2 mb-0 text-danger" id="createError">'.esc(session('errorTextCreate')).'</p>';
                            }
                        ?>
                    <div class="d-flex mt-3 justify-content-end">
                        <button type="submit" class="btn btn-primary mx-1"><?=lang('App.createRequest')?></button>
                        <input type="hidden" name="startTime" id="startTime" value="">
                        <input type="hidden" name="providerId" id="providerId" value="<?=$provider->idKorisnika?>">
                    </div>
                </form>
                <?php
                    if(session('errorData')!=null)
                    {
                        echo '<input type="hidden" id="errorStartTime" value="'.session('errorData')['datumVremePocetka'].'">';
                        echo '<input type="hidden" id="errorDuration" value="'.session('errorData')['trajanje'].'">';
                    }
                ?>
            </div>
        </div>
    </div>
</div>
<button class="d-none" type="button" data-bs-toggle="modal" data-bs-target="#newRequestModal" id="newRequestModalButton"></button>