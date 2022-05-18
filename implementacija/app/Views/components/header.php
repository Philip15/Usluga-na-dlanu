<header>

<?= $this->include('components/loginpopup') ?>

<nav class="navbar navbar-light bg-light px-3">
    <a class="navbar-brand fw-bolder fs-3" href="<?=base_url()?>">
        <img src="<?=base_url('res/Logot.png')?>" height="50px"/>
        <?=lang('App.title')?>
    </a>

    <?php if(session('user')!==null){
        echo $this->include('components/userdropdown');
    }else{
        echo $this->include('components/loginregisterbuttons');
    }?>

</nav>
</header>