<?= $this->extend('layouts/defaultLayout') ?>

<?= $this->section('content') ?>

<?=$this->include('components/newRequest')?>

<div class="container rounded bg-white mt-5 mb-5">
    <div class="row">
        <!--PROFILNA-->
        <div class="col-md-auto border-right">
            <div class="d-flex flex-column m-3 my-5 w-300px">
                <img src="<?php if(isset($provider->profilnaSlika)&&$provider->profilnaSlika!==null){echo 'data:image/jpeg;base64,'.base64_encode($provider->profilnaSlika);}else{echo base_url('res/placeholder-user.jpg');}?>" class="rounded-circle" width="300px" height="300px">
                <span class="font-weight-bold text-center fs-4 mt-3"><?= esc($provider->ime) ?>&nbsp;<?= esc($provider->prezime) ?></span>
                <span class="font-weight-bold fs-5 my-1"><?= esc(ucfirst($provider->kategorija->naziv)) ?></span>
                <span class="font-weight-bold fs-5 my-1"><script type="text/javascript">document.write(stars(<?=$provider->rating?>))</script></span>
                <a href="<?= (session('user')!=null)? 'mailto://'.esc($provider->email) : '' ?>" class="text-muted"><?= (session('user')!=null)? esc($provider->email) : lang('App.loginForEmail') ?></a>
                <?php if(session('user')!=null){echo '<span class="font-weight-bold fs-6 my-1">'.esc($provider->adresa).'</span>';}?>
            </div>
        </div>
        <!--OPIS-->
        <div class="col-md text-center d-flex flex-column justify-content-center">
            <h1><?=lang('App.info')?></h1>
            <span class="font-weight-bold fs-5 my-1"><?= esc($provider->opis) ?></span>
        </div>
    </div>
    <div class="row mb-5">
        <!--KALENDAR-->
        <h4 class="text-center"><?=lang('App.calendar')?></h4>  
        <div class="d-flex justify-content-center">
            <div class="rounded bg-light p-4">
                <?= $this->include('components/calendar') ?>
            </div>
        </div>
    </div>

    <div class="row p-2">
        <!--KOMENTARI-->
        <h4 class="text-center"><?=lang('App.comments')?></h4>
        <div class="row p-0 m-0">
            <div class="col card scroll-y mh-500px">
                <?php
                    for ($i = 0; $i < count($komentari); $i++) 
                    {
                        $rec = $komentari[$i];
                        echo
                        '<div class="card my-2">
                            <div class="card-body row">
                                <div class="col-auto d-flex justify-content-between">
                                    <div class="d-flex h-fit">
                                        <img src="';
                                            if (isset($rec[2]->profilnaSlika) && $rec[2]->profilnaSlika !== null) 
                                            {
                                                echo "data:image/jpeg;base64," . base64_encode($rec[2]->profilnaSlika);
                                            }
                                            else
                                            {
                                                echo base_url("res/placeholder-user.jpg");
                                            }
                                            echo '" alt="avatar" width="40" height="40" class="rounded-circle"/>
                                        <p class="ms-2 my-auto">'.esc($rec[2]->korisnickoIme).'</p>
                                        <div class="ms-2 my-auto">
                                            <script type="text/javascript">document.write(stars('.esc($rec[0]).'))</script>
                                        </div>
                                    </div>
                                </div>';
                                if(!empty($rec[1]))
                                {
                                    echo
                                    '<div class="col mt-2">'.esc($rec[1]).'</div>';
                                }
                        echo
                            '</div>
                        </div>';
                    }
                ?>
            </div>
        </div>
    </div>
</div>

<?php 
    if (session('alertErrorText') != null) 
    {
        echo '<script > window.onload = window.setTimeout(function(){alert("'.esc(session('alertErrorText')).'");}, 100);</script>';
    }
?>

<?= $this->endSection() ?>