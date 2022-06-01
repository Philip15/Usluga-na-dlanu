<?= $this->extend('layouts/defaultLayout') ?>

<?= $this->section('content') ?>

<div>

    <div class="container rounded bg-white mt-5 mb-5">
        <div class="row">

            <!--                                                                                                                                                     PROFILNA -->
            <div class="col-sm-5 border-right">
                <div class="d-flex flex-column align-items-center text-center p-3 py-5"><img width="150px" src="<?php if (isset($provider->profilnaSlika) && $provider->profilnaSlika !== null) {
                                                                                                                    echo 'data:image/jpeg;base64,' . base64_encode($provider->profilnaSlika);
                                                                                                                } else {
                                                                                                                    echo base_url('res/placeholder-user.jpg');
                                                                                                                } ?>" class="rounded-circle">
                    <span class="font-weight-bold" style="font-size:larger">
                        <?= $provider->ime . " " . $provider->prezime ?></span>
                    <span class="text-black-50"><?= $provider->email ?></span>
                    <span class="text-blamd-4k-50" style="margin-bottom:1%"><?= ucfirst($provider->kategorija->naziv) ?></span>
                    <br>
                </div>
            </div>

            <!--                                                                                                                                                     OPIS -->


            <div class="col-sm-7 text-center" style="margin-top:60px">
                <br><br><br><br>
                <h1><?=lang('App.info')?></h1>
                <span class="text-blamd-4k-50"><?= $provider->opis ?></span>

            </div>


        </div>



        <div class="row" style="margin-top:10px">

            <!--                                                                                                                                      				KOMENTARI -->

            <div class="col-sm border-right" style="overflow-y: scroll; height:500px">
                <div class="card shadow-0 border overflow text-center" style="background-color: #f0f2f5;margin-top:30px; margin-bottom:30px;">
                    <h4><?=lang('App.comments')?></h4>
                    <div class="card-body p-4 overflow">
                        <div class="form-outline mb-4">
                        </div>

                        <?php
                        for ($i = 0; $i < count($komentari); $i++) {
                            $rec = $komentari[$i];
                            echo
                            '
								<div class="card mb-4">
									<div class="card-body">
										<p>' . esc($rec[1]) . '</p>

										<div class="d-flex justify-content-between">
                                            <div class="d-flex flex-row align-items-center">
                                                    <img src="'; if (isset($rec[2]->profilnaSlika) && $rec[2]->profilnaSlika !== null) {
                                                        echo "data:image/jpeg;base64," . base64_encode($rec[2]->profilnaSlika);
                                                    } else {
                                                        echo base_url("res/placeholder-user.jpg");
                                                    } ;
                                                    echo '" alt="avatar" width="40" height="40" />
                                                    <p class="small mb-0 ms-2">'.esc($rec[2]->korisnickoIme).'</p> &nbsp &nbsp &nbsp
                                                    <script type="text/javascript">document.write(stars('.esc($rec[0]).'))</script>
                                            </div>
											<div class="d-flex flex-row align-items-center">
												<img src="res/like.jpg" style="height:25px" />
												<i class="far fa-thumbs-up mx-2 fa-xs text-black" style="margin-top: -0.16rem;"></i>
												<p class="small text-muted mb-0">17</p>
											</div>
										</div>
									</div>
								</div>
								';
                        }
                        ?>


                    </div>
                </div>
            </div>
        </div>
    </div>


</div>

</div>

</div>
<?= $this->endSection() ?>