<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered ">
        <div class="modal-content bg-light">
            <div class="modal-header border-light mt-3">
                <h5 class="modal-title w-100 text-center ms-5" id="loginModalLabel"><?=lang('App.loginTitle')?></h5>
                <button type="button" class="btn-close me-3" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="d-flex flex-column container justify-content-center" method="POST" action="<?=base_url('GuestController/OPLogin')?>">
                    <div class="form-floating">
                        <input class="form-control m-1" type="text" name="username" id="username" placeholder="<?=lang('App.username')?>">
                        <label class="d-flex align-items-center" for="username"><?=lang('App.username')?></label>
                    </div>
                    <div class="form-floating">
                        <input class="form-control m-1" type="password" name="password" id="password" placeholder="<?=lang('App.password')?>">
                        <label class="d-flex align-items-center" for="password"><?=lang('App.password')?></label>
                    </div>
                    <?php
                    if(session('loginErrorText') !== null)
                    {
                       echo '<p class="px-2 mt-2 mb-0 text-danger" id="wrongPassword">'.esc(session('loginErrorText')).'</p>';
                    }
                    ?>
                    <div class="form-floating">
                        <button type="submit" class="form-control btn btn-primary m-1 mt-4 mb-3 py-2"><?=lang('App.login')?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>