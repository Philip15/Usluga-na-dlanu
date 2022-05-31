<?php

namespace App\Controllers;

use App\Models\ZahtevModel;

class ProviderController extends BaseController
{
    
    //TODO
    public function requests()
    {
        // prikaz view-a provider-requests.php
        $korisnik = $this->session->get('user');
        $data['requests1'] = $korisnik->getRequestsProvider(1);
        $data['requests2'] = $korisnik->getRequestsProvider(2);
        $data['requests3'] = $korisnik->getRequestsProvider(3);
        $data['requests7'] = $korisnik->getRequestsProvider(7);
        return view('provider-requests',$data);
    }

    //TODO
    public function OPCreateOffer()
    {
        // kreiranje ponude iz primljenog zahteva                                       ( prelazak 1 -> 2 )
    }

    //TODO
    public function OPRejectRequest()
    {
        // odbijanje zahteva u bilo kom trenutku                                        ( prelazak 1 -> 6, 3 -> 6 )
        $id = $this->request->getGet('id');
        $zahtevModel = new ZahtevModel();

        $zahtevModel->update($id, ['stanje' => 6]);
    }

    public function OPRealizeRequest()
    {
        // oznacavanje zahteva kao realizovanog, slanje na recenziju                    ( prelazak 3 -> 4 )
        $id = $this->request->getGet('id');
        $zahtevModel = new ZahtevModel();

        $zahtevModel->update($id, ['stanje' => 4]);
    }

    public function OPcheckRejection()
    {
        // oznacavanje notifikacije odbijenog zahteva kao pregledane                ( prelazak 6 -> 8 )
        $id = $this->request->getGet('id');
        $zahtevModel = new ZahtevModel();

        $zahtevModel->update($id, ['stanje' => 8]);
    }

}