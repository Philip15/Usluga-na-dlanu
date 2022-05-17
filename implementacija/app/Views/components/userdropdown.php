<div class="dropdown justify-content-end" id="userProfile">
    <a href="#" class="d-block link-dark text-decoration-none dropdown-toggle" id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
        <img src="<?php if(isset($user->profile_picture)){echo 'data:image/jpeg;base64,'.base64_encode($user->profile_picture);}else{echo base_url('placeholder-user.jpg');}?>" width="40" height="40" class="rounded-circle">
        <span class="position-absolute top-0 start-40px translate-middle p-2 bg-danger border border-light rounded-circle" id="hasNotifications">
            <span class="visually-hidden"><?=lang('App.newNotifications')?></span>
        </span>
    </a>
    <ul class="dropdown-menu text-small" aria-labelledby="dropdownUser">
        <?= view_cell('\App\Libraries\Header::menuItems', ['user' => $user]) ?>
    </ul>
</div>