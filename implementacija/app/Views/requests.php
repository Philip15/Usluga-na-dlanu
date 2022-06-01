<?= $this->extend('layouts/defaultLayout') ?>

<?= $this->section('content') ?>
<div class="container">
    <?php

        use App\Libraries\RequestInfoLib;

        if($provider)
        {
            echo $this->include("components/newOffer");
        }

        for ($i=0; $i < 4; $i++) 
        { 
            echo '<h1 class="text-center text-white">'.lang('App.requestSectionTitle'.($provider?$i+4:$i)).'</h1>';
            if(count($requests[$i])==0)
            {
                echo '<div class="container px-4 my-5">';
                echo '<h5 class="text-light  text-center">'.lang('App.noRequests').'</h5>';
                echo '</div>';
            }
            else
            {
                foreach ($requests[$i] as $request)
                {
                    echo
                    '<div class="d-flex flex-column container justify-content-center bg-light rounded-3 px-4 py-3 my-5" id="'.$request->idZahteva.'">';
                        if($request->termini[0]??null != null)
                        {
                            echo RequestInfoLib::slotInfo($request->termini[0]??null,$provider,true);
                        }
                        else
                        {
                            echo RequestInfoLib::requestInfo($request,$provider);
                        }
                    echo '</div>';
                }
            }
        }
    ?>
</div>
<?= $this->endSection() ?>