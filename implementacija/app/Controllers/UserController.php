<?php

namespace App\Controllers;

use App\Models\KategorijaModel;
use App\Models\KorisnikModel;

class UserController extends BaseController
{
    public function OPlogout() 
    {
        $this->session->destroy();
        return redirect()->to(base_url());
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

}