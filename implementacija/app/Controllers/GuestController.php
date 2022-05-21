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
        if ($korisnik->lozinka != $this->request->getVar('password'))
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
        if ($this->request->getVar('uslovi_koriscenja') !=  "on") 
        {
            $this->session->setFlashdata('podaci', $korisnikPodaci);
            $this->session->setFlashdata('errorText', lang('App.errNotTNC'));
            return redirect()->to(site_url('GuestController/register'));
        }
        $korisnikModel = new KorisnikModel();
        $korisnik = $korisnikModel->where('korisnickoIme', $korisnikPodaci['korisnickoIme'])->findAll();
        if ($korisnik != null) 
        {
            $this->session->setFlashdata('podaci', $korisnikPodaci);
            $this->session->setFlashdata('errorText', lang('App.errUserAlreadyExists'));
            return redirect()->to(site_url('GuestController/register'));
        }
        if ($korisnikPodaci['lozinka'] != $korisnikPodaci['lozinka']) 
        {
            $this->session->setFlashdata('podaci', $korisnikPodaci);
            $this->session->setFlashdata('errorText', lang('App.errPasswordConfirmation'));
            return redirect()->to(site_url('GuestController/register'));
        }

        /*$this->session->set('username', $this->request->getVar('username'));
        $this->session->set('lozinka',$this->request->getVar('password'));
        $this->session->set('email', $this->request->getVar('email'));
        $this->session->set('ime', $this->request->getVar('ime'));
        $this->session->set('prezime',$this->request->getVar('prezime'));


        $imeKorisnika = $this->request->getVar('ime');
        $emailKorisnika = $this->request->getVar('email');

        $code = "";
        for ($i = 0; $i < 6; $i++) {
			$option = rand(0, 2);
			if ($option == 0) $code .= chr(rand(48, 57));
			else if ($option == 1) $code .= chr(rand(65, 90));
			else $code .= chr(rand(97, 122));
		}*/

        unset($korisnikPodaci['password2']);

        $korisnikModel->save($korisnikPodaci);

        //TODO Temp
        $this->session->setFlashdata('errorText', lang('App.successfulRegistration'));
        return redirect()->to(site_url('GuestController/register'));

		/*$message = "Zdravo " . $imeKorisnika . ",";
		$message .= "\n\nOvaj maik je poslat na zahtev registracije naloga sa sajta Usluga na dlanu. Kod koji je potrebno da unesete u predviđeno polje je: ". $code;

		$email = \Config\Services::email();

		$email->setFrom('uslugaNaDlanu@gmail.com', 'Usluga na dlanu');
		$email->setTo($emailKorisnika);

		$email->setSubject('Registracija naloga');
		$email->setMessage($message);

		$email->send();

        $this->session->set('code', $code);*/
    }

    public function confirmCode() 
    {
        /*if ($this->get->var('verifikacioniKod') != $this->session->get('code')) {
            $korisnikModel = new KorisnikModel();
            $korisnikModel->save([
                'korisnickoIme' => $this->session->get('username'),
                'lozinka' => $this->session->get('lozinka'),
                'email' =>  $this->session->get('email'),
                'ime' =>  $this->session->get('ime'),
                'prezime' =>  $this->session->get('prezime')
            ]);
        }
        else {
            return /*$this->prikaz('registracija', ['errors'=>'Niste uneli dobar verifikacioni kod.']);
        }*/
    }
}
