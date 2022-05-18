<?php $user=session('user');?>
<div class="dropdown justify-content-end">
    <a href="#" class="d-block link-dark text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
        <img src="<?php if(isset($user->profilnaSlika) && $user->profilnaSlika !== null){echo 'data:image/jpeg;base64,'.base64_encode($user->profilnaSlika);}else{echo base_url('res/placeholder-user.jpg');}?>" width="40" height="40" class="rounded-circle">
        <?php 
            if($user->hasNotifications())
            {
                echo '<span class="position-absolute top-0 start-40px translate-middle p-2 bg-danger border border-light rounded-circle"><span class="visually-hidden">'.lang('App.newNotifications').'</span></span>';
            }
        ?>
    </a>
    <ul class="dropdown-menu text-small">
        <?= view_cell('\App\Libraries\HeaderLib::menuItems', ['user' => $user]) ?>
    </ul>
</div>