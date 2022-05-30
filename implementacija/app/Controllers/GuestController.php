<?php

namespace App\Controllers;

use App\Models\KorisnikModel;

class GuestController extends BaseController
{
    public function register()
    {
        return view('register');
    }

    public function OPlogin() 
    {
        if (!$this->validate(['username'=>'required', 'password'=> 'required'])) 
        {
            $this->session->setFlashdata('loginErrorText', lang('App.errFieldEmpty'));
            return self::safeRedirectBack();
        }
        $korisnikModel = new KorisnikModel();
        $korisnik = $korisnikModel->where('korisnickoIme',$this->request->getVar('username'))->first();
        if ($korisnik == null)
        {
            $this->session->setFlashdata('loginErrorText', lang('App.errUserNotFound'));
            return self::safeRedirectBack();
        }
        if(!password_verify($this->request->getVar('password'), $korisnik->lozinka))
        {
            $this->session->setFlashdata('loginErrorText', lang('App.errWrongPassword'));
            return self::safeRedirectBack();
        }
        $this->session->set('user', $korisnik);
        return self::safeRedirectBack();
    }

    public function OPregister() 
    {
        $korisnikPodaci = [
            'korisnickoIme' => $this->request->getVar('username'),
            'lozinka' => $this->request->getVar('password'),
            'lozinka2' => $this->request->getVar('password2'),
            'email' =>  $this->request->getVar('email'),
            'ime' =>  $this->request->getVar('ime'),
            'prezime' =>  $this->request->getVar('prezime')
        ];

        if (!$this->validate(['email'=>'required', 'ime'=>'required', 'prezime'=> 'required', 'username'=>'required', 'password'=> 'required', 'password2'=> 'required'])) 
        {
            $this->session->setFlashdata('podaci', $korisnikPodaci);
            $this->session->setFlashdata('errorText', lang('App.errFieldEmpty'));
            return redirect()->to(site_url('GuestController/register'));
        }
        if (!$this->validate(['email'=>'valid_email'])) 
        {
            $this->session->setFlashdata('podaci', $korisnikPodaci);
            $this->session->setFlashdata('errorText', lang('App.errInvalidEmail'));
            return redirect()->to(site_url('GuestController/register'));
        }
        $korisnikModel = new KorisnikModel();
        $korisnik = $korisnikModel->where('email', $korisnikPodaci['email'])->findAll();
        if ($korisnik != null) {
            $this->session->setFlashdata('podaci', $korisnikPodaci);
            $this->session->setFlashdata('errorText', lang('App.errEmailAlreadyExists'));
            return redirect()->to(site_url('GuestController/register'));
        }
        if ($this->request->getVar('uslovi_koriscenja') !=  "on") 
        {
            $this->session->setFlashdata('podaci', $korisnikPodaci);
            $this->session->setFlashdata('errorText', lang('App.errNotTNC'));
            return redirect()->to(site_url('GuestController/register'));
        }

        $korisnik = $korisnikModel->where('korisnickoIme', $korisnikPodaci['korisnickoIme'])->findAll();
        if ($korisnik != null) 
        {
            $this->session->setFlashdata('podaci', $korisnikPodaci);
            $this->session->setFlashdata('errorText', lang('App.errUserAlreadyExists'));
            return redirect()->to(site_url('GuestController/register'));
        }
        if ($korisnikPodaci['lozinka'] != $korisnikPodaci['lozinka2']) 
        {
            $this->session->setFlashdata('podaci', $korisnikPodaci);
            $this->session->setFlashdata('errorText', lang('App.errPasswordConfirmation'));
            return redirect()->to(site_url('GuestController/register'));
        }

        unset($korisnikPodaci['lozinka2']);
        $korisnikPodaci['lozinka']=password_hash($korisnikPodaci['lozinka'], PASSWORD_DEFAULT);
        $korisnikModel->save($korisnikPodaci);

        $this->session->setFlashdata('alertErrorText', lang('App.successfulRegistration'));
        return redirect()->to(site_url('GuestController/register'));
    }
}
