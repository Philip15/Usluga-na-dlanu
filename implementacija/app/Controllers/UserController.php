<?php

namespace App\Controllers;

use App\Models\ZahtevModel;

class UserController extends BaseController
{
    public function OPlogout() 
    {
        $this->session->destroy();
        return self::safeRedirectBack();
    }

    public function reviews()
    {
        $korisnik = $this->session->get('user');
        $data['jsinit']='reviews';
        $data['reviews'] = $korisnik->getReviews();
        return view('reviews',$data);
    }   
    
    public function OPpostReview()                                                                  //( prelazak 4 -> 5 )
    {
        $id = $this->request->getPost('id');
        $rating = $this->request->getPost('rating');
        $comment = $this->request->getPost('comment');
        if($id === null || $rating === null || $comment === null)
        {
            //Invalid request, fail silently
            return redirect()->to(base_url('UserController/reviews'));
        }
        if(!is_numeric($id) || !is_numeric($rating))
        {
            //Invalid request, fail silently
            return redirect()->to(base_url('UserController/reviews'));
        }
        $zahtev = ZahtevModel::findById($id);
        if($zahtev==null || $zahtev->idKorisnika!=session('user')->idKorisnika || $zahtev->stanje != 4)
        {
            //Invalid request, fail silently
            return redirect()->to(base_url('UserController/reviews'));
        }
        if($rating>5)
        {
            //Invalid request, fail silently
            return redirect()->to(base_url('UserController/reviews'));
        }
        if($rating==0)
        {
            $this->session->setFlashdata('alertErrorText',lang('App.mandatoryRating'));
            return redirect()->to(base_url('UserController/reviews'));
        }
        if(empty($comment))
        {
            $comment=null;
        }
        $zahtevM = new ZahtevModel();
        $zahtevM->update($id,['stanje'=>5,'ocena'=>$rating,'recenzija'=>$comment]);
        
        $this->session->setFlashdata('alertErrorText',lang('App.tankYouReview'));
        return redirect()->to(base_url('UserController/reviews'));
    }

    public function OPremoveReview()
    {
        $id = $this->request->getGet('id');
        if($id === null)
        {
            //Invalid request, fail silently
            return redirect()->to(base_url('UserController/reviews'));
        }
        if(!is_numeric($id))
        {
            //Invalid request, fail silently
            return redirect()->to(base_url('UserController/reviews'));
        }
        $zahtev = ZahtevModel::findById($id);
        if($zahtev==null || $zahtev->idKorisnika!=session('user')->idKorisnika || $zahtev->stanje != 4)
        {
            //Invalid request, fail silently
            return redirect()->to(base_url('UserController/reviews'));
        }
        $zahtevM = new ZahtevModel();
        $zahtevM->update($id,['stanje'=>5]);
        
        return redirect()->to(base_url('UserController/reviews'));
    }

    //TODO
    public function requests()
    {
        // prikaz view-a user-requests.php
        $korisnik = $this->session->get('user');
        $data['requests1'] = $korisnik->getRequestsUser(1);
        $data['requests2'] = $korisnik->getRequestsUser(2);
        $data['requests3'] = $korisnik->getRequestsUser(3);
        $data['requests6'] = $korisnik->getRequestsUser(6);
        return view('user-requests',$data);
    }

    //TODO
    public function OPCreateRequest()
    {
        // kreiranje zahteva za uslugom od strane korisnika                         ( kreiranje u stanju 1 )
        $newRequest = new ZahtevModel();
        
    }

    //TODO
    public function OPAcceptRequest()
    {
        // prihvatanje ponude                                                       ( prelazak 2 -> 3 )
        $id = $this->request->getGet('id');
        $zahtevModel = new ZahtevModel();

        $zahtevModel->update($id, ['stanje' => 3]);

    }

    public function OPRejectRequest()
    {
        $id = $this->request->getGet('id');
        // odbijanje zahteva u bilo kom trenutku                                    ( prelazak 2 -> 7 )
        $zahtevModel = new ZahtevModel();

        $zahtevModel->update($id, ['stanje' => 7]);

    }

    //TODO
    public function OPcheckRejection()
    {
        // oznacavanje notifikacije odbijenog zahteva kao pregledane                ( prelazak 7 -> 8 )
        $id = $this->request->getGet('id');
        $zahtevModel = new ZahtevModel();

        $zahtevModel->update($id, ['stanje' => 8]);

        return redirect()->to(base_url('UserController/requests'));
    }

}