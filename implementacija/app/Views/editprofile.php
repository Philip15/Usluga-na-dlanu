<?= $this->extend('layouts/defaultLayout') ?>

<?= $this->section('additionalhead') ?>
<link rel="stylesheet" type="text/css" href="<?= base_url('css/profile.css') ?>" />
<link rel="stylesheet" type="text/css" href="<?= base_url('css/calendar.css') ?>" />
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php $user = session('user'); ?>

<div class="container rounded bg-white mt-5 mb-5">
    <div class="row">
        <div class="col-md-3 border-right">
            <div class="d-flex flex-column align-items-center text-center p-3 py-5">
            <img width="150px" src="<?php if (isset($user->profilnaSlika) && $user->profilnaSlika !== null) {echo 'data:image/jpeg;base64,' . base64_encode($user->profilnaSlika);} else {echo base_url('res/placeholder-user.jpg');} ?>" class="rounded-circle">
            <span class="font-weight-bold" style="font-size:larger"><?= $user->ime ?>&nbsp;<?= $user->prezime ?></span>
            <span class="text-black-50"><?= $user->email ?></span><br>    
            <form method="POST" enctype="multipart/form-data" action="<?= base_url('UserController/OPchangeProfilePicture') ?>">
                <label for="profilePicture" class="btn btn-secondary"><?= lang('App.chooseProfilePicture') ?></label>
                <input type="file" style="visibility:hidden;" name="profilePicture" id="profilePicture">
                <button class="btn btn-primary profile-button" type="submit"><?= lang('App.changeProfilePicture') ?></button>
            </form>
            </div>
        </div>
        
        <div class="col-md-5 border-right">
            <div class="p-3 py-5">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="text-right"><?= lang('App.profileOverview') ?></h4>
                </div>
                <form method="POST" action="<?= base_url('UserController/OPsaveChanges') ?>">                                                                                           
                <div class="row mt-2">
                    <div class="col-md-6"><label class="labels"><?= lang('App.name') ?></label><input type="text" name="ime" class="form-control input" placeholder="<?= lang('App.name') ?>" value="<?= empty(session('podaci')) || session('podaci')['ime'] == null ? $user->ime : session('podaci')['ime'] ?>"></div>
                    <div class="col-md-6"><label class="labels"><?= lang('App.surname') ?></label><input type="text" name="prezime" class="form-control" placeholder="<?= lang('App.surname') ?>" value="<?= empty(session('podaci')) || session('podaci')['prezime'] == null ? $user->prezime : session('podaci')['prezime'] ?>"></div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-12"><label class="labels"><?= lang('App.username') ?></label><input type="text" name="username" class="form-control input" placeholder="<?= lang('App.username') ?>" value="<?= empty(session('podaci')) || session('podaci')['korisnickoIme'] == null ? $user->korisnickoIme : session('podaci')['korisnickoIme'] ?>"></div>
                    <div class="col-md-12"><label class="labels"><?= lang('App.emailAddress') ?></label><input type="email" name="email" class="form-control input" placeholder="<?= lang('App.regEmail') ?>" value="<?= empty(session('podaci')) || session('podaci')['email'] == null ? $user->email : session('podaci')['email'] ?>"></div>
                    <div class="col-md-12"><label class="labels"><?= lang('App.password') ?></label><input type="password" name="password" class="form-control input" placeholder="<?= lang('App.password') ?>" value="<?= empty(session('podaci')) || session('podaci')['lozinka'] == null ? $user->lozinka : session('podaci')['lozinka'] ?>"></div>
                    <?php
                    if ($user->role() == 'provider') {
                        echo
                        '<div class="col-md-12"><label class="labels">' . lang('App.providerCategory') . '</label>
                                <select name="kategorija" class="form-select form-select-md mb-3" aria-label=".form-select-lg example">
                                <option selected>' . esc(ucfirst($userCategory->naziv)) . '</option>';
                        foreach ($categories as $category) {
                            if ($userCategory->idKategorije != $category->idKategorije)
                                echo '<option value="' . esc($category->idKategorije) . '">' . esc(ucfirst($category->naziv)) . '</option>';
                        }
                        echo '</select>
                            </div>
                            <div class="col-md-12"><label class="labels-sel">' . lang('App.providerAddress') . '</label><input type="text" class="form-control" name="adresa" value="' . $user->adresa . '">
                            </div>';
                    }
                    ?>
                    <div class="col-md-12"><label class="labels"><?= lang('App.additionalInformation') ?></label><input type="text" name="dodatneInformacije" class="form-control" value="<?= $user->opis ?>"></div>
                </div>
                <?php
                if (session('errorText') !== null) {
                    echo '<p class="px-2 mt-2 mb-0 text-danger">' . esc(session('errorText')) . '</p>.</br>';
                }
                ?>
                <div class="mt-5 text-center">
                    <button class="btn btn-primary profile-button" type="submit"><?= lang('App.saveChanges') ?></button>
                </div>
                </form>
            </div>
        </div>
        
        <?php
        if ($user->role() == 'user') {
            echo
            '<div class="col-md-4">
            <div class="p-3 py-5">
                <div class="d-flex justify-content-between align-items-center experience">
                    <h4>' . lang('App.profileUpdate') . '<h4>
                </div><br>
                <div class="col-md-12"><label class="labels">' . lang('App.providerCategory') . '</label>
                    <select class="form-select form-select-md mb-3" aria-label=".form-select-lg example">
                        <option disabled selected>' . lang('App.choose') . '</option>';

            foreach ($categories as $category) {
                echo '<option value="' . esc($category->idKategorije) . '">' . esc(ucfirst($category->naziv)) . '</option>';
            }

            echo '</select>
                    <br>
                    <div class="col-md-12"><label class="labels-sel">' . lang('App.providerAddress') . '</label><input type="text" class="form-control" placeholder="' . lang('App.profileAddress') . '"></div>
                </div>
                </div>
            </div>';
        } else {
            echo
            '<div class="col-md-4">
            <div class="p-1 py-5">
                <div class="d-flex justify-content-between align-items-center offset-4"><h4>' . lang('App.calendar') . '</h4></div><br>
                <div class="container-cal-profile"> 
                    <div class="wrap-cal">
                            <div class="month">
                                <ul>
                                <li class="prev">&#10094;</li>
                                <li class="next">&#10095;</li>
                                <li>Mart<br><span style="font-size:10px;">2022</span></li>
                                </ul>
                            </div>
                            
                            <ul class="weekdays">
                                <li>Pon</li>
                                <li>Uto</li>
                                <li>Sre</li>
                                <li>Čet</li>
                                <li>Pet</li>
                                <li>Sub</li>
                                <li>Ned</li>
                            </ul>
                            
                            <ul class="days">
                                <li><span class="last-month">28</span></li>
                                <li>1</li>
                                <li>2</li>
                                <li>3</li>
                                <li>4</li>
                                <li>5</li>
                                <li>6</li>
                                <li>7</li>
                                <li>8</li>
                                <li>9</li>
                                <li><span class="active">10</span></li>
                                <li>11</li>
                                <li>12</li>
                                <li>13</li>
                                <li>14</li>
                                <li>15</li>
                                <li>16</li>
                                <li>17</li>
                                <li>18</li>
                                <li>19</li>
                                <li>20</li>
                                <li>21</li>
                                <li><span class="occupied">22</span></li>
                                <li><span class="occupied">23</span></li>
                                <li><span class="occupied">24</span></li>
                                <li><span class="occupied">25</span></li>
                                <li>26</li>
                                <li>27</li>
                                <li>28</li>
                                <li>29</li>
                                <li>30</li>
                                <li>31</li>
                                <li><span class="last-month">1</span></li>
                                <li><span class="last-month">2</span></li>
                                <li><span class="last-month">3</span></li>
                            </ul>
                        </div>
                    </div>
                <br>
            <form action="POST" method="' . esc(base_url("UserController/timetable")) . '">    
            <button class="btn btn-primary profile-button offset-4" type="submit">' . lang('App.changeCalendar') . '</button>
            </form>';
        }
        ?>
    </div>
</div>


<?= $this->endSection() ?>