<?php

namespace App\Controllers;

use App\Models\KategorijaModel;
use App\Models\KorisnikModel;
use App\Models\ZahtevModel;


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
        $kategorijaModel = new KategorijaModel();
        $data['userCategory'] = $kategorijaModel->find($korisnik->idKategorije);
        return view('editprofile', $data);
    }

    public function OPsaveChanges()
    {
        $korisnikPodaci = [
            'ime' =>  $this->request->getVar('ime'),
            'prezime' =>  $this->request->getVar('prezime'),
            'korisnickoIme' => $this->request->getVar('username'),
            'lozinka' => $this->request->getVar('password'),
            'email' =>  $this->request->getVar('email'),
            'opis' => $this->request->getVar('dodatneInformacije')
        ];
        
        $korisnikModel = new KorisnikModel();

        if (session('user')->role() == "user") {
            
            if (!$this->validate(['email'=>'required', 'ime'=>'required', 'prezime'=> 'required', 'username'=>'required', 'password'=> 'required']))
            {
                $this->session->setFlashdata('podaci', $korisnikPodaci);
                $this->session->setFlashdata('errorText', 'Obavezna polja ne smeju biti prazna!');
                return redirect()->to(site_url('UserController/editProfile'));
            }
            else {
                $korisnikModel->update(session('user')->idKorisnika, $korisnikPodaci);
                $this->session->setFlashdata('podaci', $korisnikPodaci);
                return redirect()->to(site_url('UserController/editProfile'));
            }
        }
        else {
            $korisnikPodaci[] = [
                'adresa' => $this->request->getVar('adresa'),
                'kategorija' => $this->request->getVar('kategorija')
            ];
            $korisnikModel->update(session('user')->idKorisnika, $korisnikPodaci);
            $this->session->setFlashdata('podaci', $korisnikPodaci);
            return redirect()->to(site_url('UserController/editProfile'));
        }
        
    }

    public function OPchangeProfilePicture()
    {
        var_dump($_FILES);
        $img = file_get_contents($_FILES['profilePicture']['tmp_name']);
        $korisnikModel = new KorisnikModel();
        $korisnikModel->update(session('user')->idKorisnika, ['profilnaSlika' => $img]);
        return redirect()->to(site_url('UserController/editProfile'));
    }

    public function reviews()
    {
        $korisnik = $this->session->get('user');
        $data['jsinit']='reviews';
        $data['reviews'] = $korisnik->getReviews();
        return view('reviews',$data);
    }   
    
    public function OPpostReview()
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
}