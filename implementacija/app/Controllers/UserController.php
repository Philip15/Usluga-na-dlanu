<?php

namespace App\Controllers;

use App\Models\KategorijaModel;
use App\Models\KorisnikModel;
use App\Models\ZahtevModel;
use App\Models\TerminModel;


class UserController extends BaseController
{
    public function OPlogout() 
    {
        $this->session->destroy();
        return self::safeRedirectBack();
    }

    public function editProfile()
    {
        $data['categories'] = KategorijaModel::getAll();
        $korisnikModel = new KorisnikModel();
        $korisnik = $korisnikModel->find(session('user')->idKorisnika);
        $korisnik->linkKategorija();
        $this->session->set('user', $korisnik);
        return view('editprofile', $data);
    }

    public function OPsaveChanges()
    {
        $korisnikPodaci = [
            'ime' =>  $this->request->getVar('ime'),
            'prezime' =>  $this->request->getVar('prezime'),
            'korisnickoIme' => $this->request->getVar('username'),
            'email' =>  $this->request->getVar('email'),
            'opis' => $this->request->getVar('dodatneInformacije')
        ];
        
        $korisnikModel = new KorisnikModel();

        if (session('user')->role() == "user") 
        {
            
            if (!$this->validate(['email'=>'required', 'ime'=>'required', 'prezime'=> 'required', 'username'=>'required']))
            {
                $this->session->setFlashdata('podaci', $korisnikPodaci);
                $this->session->setFlashdata('errorText', 'Obavezna polja ne smeju biti prazna!');
                return redirect()->to(site_url('UserController/editProfile'));
            }
            else {
                $korisnikModel->update(session('user')->idKorisnika, $korisnikPodaci);
                $this->session->setFlashdata('podaci', $korisnikPodaci);
                $this->session->setFlashdata('alertErrorText', lang('App.successfulProfileUpdate'));
                return redirect()->to(site_url('UserController/editProfile'));
            }
        }
        else 
        {
            $korisnikPodaci[] = [
                'adresa' => $this->request->getVar('adresa'),
                'kategorija' => $this->request->getVar('kategorija')
            ];
            $korisnikModel->update(session('user')->idKorisnika, $korisnikPodaci);
            $this->session->setFlashdata('podaci', $korisnikPodaci);
            $this->session->setFlashdata('alertErrorText', lang('App.successfulProfileUpdate'));
            return redirect()->to(site_url('UserController/editProfile'));
        }
    }

    public function OPchangeProfilePicture()
    {
        if ($_FILES['profilePicture']['tmp_name'] != "") 
        {
            $img = file_get_contents($_FILES['profilePicture']['tmp_name']);
            $korisnikModel = new KorisnikModel();
            $korisnikModel->update(session('user')->idKorisnika, ['profilnaSlika' => $img]);
            $korisnik = $korisnikModel->find(session('user')->idKorisnika);
            $this->session->set('user', $korisnik);
            $this->session->setFlashdata('alertErrorText', lang('App.successfulPictureChange'));
        }
        return redirect()->to(site_url('UserController/editProfile'));
    }

    public function OPconvertProfile()
    {
        $korisnikPodaci = [
            'idKategorije' => $this->request->getVar('kategorija'),
            'adresa' => $this->request->getVar('adresaPoslovanja'),
            'pruzalac' => 2
        ];

        if (!$this->validate(['kategorija'=>'required', 'adresaPoslovanja'=>'required']))
        {
            $this->session->setFlashdata('podaciKonverzija', $korisnikPodaci);
            $this->session->setFlashdata('errorTextConversion', lang('App.errEmptyFieldsConversion'));
            return redirect()->to(site_url('UserController/editProfile'));
        }
        $korisnikModel = new KorisnikModel();
        $korisnikModel->update(session('user')->idKorisnika, $korisnikPodaci);
        $this->session->setFlashdata('podaciKonverzija', $korisnikPodaci);
        $this->session->setFlashdata('alertErrorText', lang('App.successfulConversion'));
        return redirect()->to(site_url('UserController/editProfile'));
    }

    public function OPudpatePassword()
    {
        $korisnikPodaci = [
            'staraLozinka' => $this->request->getVar('oldPassword'),
            'lozinka' => $this->request->getVar('newPassword'),
            'lozinka2' => $this->request->getVar('newPasswordAgain')
        ];
        if (!$this->validate(['oldPassword' => 'required', 'newPassword'=>'required', 'newPasswordAgain'=>'required']))
        {
            $this->session->setFlashdata('podacilozinka', $korisnikPodaci);
            $this->session->setFlashdata('errorTextNewPassword', lang('App.errFieldEmpty'));
            return redirect()->to(site_url('UserController/editProfile'));
        }
        if (!password_verify($korisnikPodaci['staraLozinka'], session('user')->lozinka))
        {
            $this->session->setFlashdata('podacilozinka', $korisnikPodaci);
            $this->session->setFlashdata('errorTextNewPassword', lang('App.errOldPassword'));
            return redirect()->to(site_url('UserController/editProfile'));
        }
        if ($korisnikPodaci['lozinka'] != $korisnikPodaci['lozinka2']) 
        {
            $this->session->setFlashdata('podacilozinka', $korisnikPodaci);
            $this->session->setFlashdata('errorTextNewPassword', lang('App.errPasswordConfirmation'));
            return redirect()->to(site_url('UserController/editProfile'));
        }
        unset($korisnikPodaci['staraLozinka']);
        unset($korisnikPodaci['lozinka2']);
        $korisnikPodaci['lozinka'] = password_hash($korisnikPodaci['lozinka'], PASSWORD_DEFAULT);

        $korisnikModel = new KorisnikModel();
        $korisnikModel->update(session('user')->idKorisnika, $korisnikPodaci);
        $this->session->setFlashdata('alertErrorText', lang('App.successfulPasswordChange'));
        return redirect()->to(site_url('UserController/editProfile'));
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

        $data = [
            'idKorisnika' => (int)session('user')->idKorisnika,
            'idPruzaoca' => (int)$this->request->getVar('providerId'),
            'stanje'      => 1,
            'opis'        => $this->request->getVar('requestDesc'),
            'hitno'       => $this->request->getVar('urgentBox') == "on",
        ];
        $newRequest->insert($data);

        $idZahteva = $newRequest->getInsertID();

        date_default_timezone_set('Europe/Belgrade');
        $dataT['trajanje']=intval($this->request->getPost('duration'));
        $dataT['datumVremePocetka']=intval($this->request->getPost('startTime'));
        $dataT['idPruzaoca'] = (int)$this->request->getVar('providerId');

        if($dataT['trajanje']==null || $dataT['datumVremePocetka']==null || session('user')->role()!='provider')
        {
            return redirect()->to(base_url('UserController/requests'));
        }
        if($dataT['trajanje']<30 || $dataT['trajanje']%30!=0 || session('user')->overlap($dataT['datumVremePocetka'],$dataT['datumVremePocetka']+$dataT['trajanje']*60))
        {
            return redirect()->to(base_url('UserController/requests'));
        }
        $dataT['datumVremePocetka']=date('Y-m-d H:i:s',$dataT['datumVremePocetka']);
        $dataT['idZahteva'] = $idZahteva;
        $TerminM = new TerminModel();
        $TerminM->insert($dataT);
        return redirect()->to(base_url('UserController/requests'));
        
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