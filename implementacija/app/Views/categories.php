<?= $this->extend('layouts/defaultLayout') ?>

<?= $this->section('additionalhead') ?>
<link rel="stylesheet" type="text/css" href="<?= base_url('css/calendar.css') ?>" />
<link rel="stylesheet" type="text/css" href="<?= base_url('css/profile.css') ?>" />
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <div class="modal fade" id="categoryModal" tabindex="-1" aria-labelledby="categoryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered ">
            <div class="modal-content bg-light">
                <div class="modal-header border-light mt-3">
                    <h5 class="modal-title w-100 text-center ms-5" id="categoryModalLabel"><?= lang('App.addNewCategory') ?></h5>
                    <button type="button" class="btn-close me-3" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="d-flex flex-column container justify-content-center" method="POST" action="<?= base_url('AdminController/OPAddCategory') ?>">
                        <div class="form-floating">
                            <input class="form-control m-1" type="text" name="category" id="category" placeholder="<?= lang('App.newCategory') ?>">
                            <label class="d-flex align-items-center" for="category"><?= lang('App.newCategory') ?> </label>
                        </div>

                        <div class="form-floating">
                            <button type="submit" class="form-control btn btn-primary m-1 mt-4 mb-3 py-2" data-bs-dismiss="modal" onclick="onClick_AddCategory()"><?= lang('App.add') ?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <ul class="list-group" style="margin-left:40%; margin-top:10%" id="categories">
        <?php
        foreach ($categories as $category) {
            echo
            '<form method="POST" action="' . esc(base_url('AdminController/OPRemoveCategory/?id=' . esc($category->idKategorije))) . '">
            <li class="list-group-item" style="background-color:#1abc9c; width:30%">' . esc(ucfirst($category->naziv)) . '<input name=' . esc($category->idKategorije) . ' class="btn btn-primary profile-button" type="submit" style="background-color:red; width:30%; float:right" value=' . lang('App.removeCategory') . '></input></li>
            </form>';
        }
        ?>
    </ul>


    <button class="btn btn-primary profile-button" type="submit" style="background-color:#1abc9c; width:18%; float:center; margin-left:40%" data-bs-toggle="modal" data-bs-target="#categoryModal"><?= lang('App.addCategory') ?></button>


<?= $this->endSection() ?>