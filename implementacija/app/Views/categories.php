<!--
  * @author Lazar Premović  2019/0091
  * @author Jana Pašajlić   2019/0132
-->

<?= $this->extend('layouts/defaultLayout') ?>

<?= $this->section('content') ?>

    <div class="container h-100">
        <div class="row justify-content-center align-items-center h-100">
            <div class="col-xl-4">
                <ul class="list-group">
                    <?php
                        foreach ($categories as $category) 
                        {
                            echo '<li class="list-group-item d-flex justify-content-between align-items-center bg-light">';
                            echo esc(ucfirst($category->naziv));
                            echo '<a href="'.esc(base_url('AdminController/OPRemoveCategory/?id=' . esc($category->idKategorije))).'" class="btn btn-danger">'.lang('App.removeCategory').'</a></li>';
                        }
                    ?>
                    <li class="list-group-item bg-light">
                        <form method="POST" action="<?= base_url('AdminController/OPAddCategory') ?>">
                            <div class="row">
                                <div class="col-xl">
                                    <input type="text" class="form-control" name="category" placeholder="<?=lang('App.nameN')?>">
                                </div>
                                <div class="col-xl-2">
                                    <button type="submit" class="form-control btn btn-success">+</button>
                                </div>
                            </div>
                        </form>
                    </li>
                </ul>
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