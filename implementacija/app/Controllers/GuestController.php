<?php

namespace App\Controllers;

use App\Models\KorisnikModel;

class GuestController extends BaseController
{
    public function index()
    {
        echo view('index.php');
    }

    protected function prikaz($page, $data)
    {
        $data['controller']='GostController';
        echo view('sablon/header_gost');
        echo view("stranice/$page", $data);
        echo view('sablon/footer');
    }

    public function login() 
    {
        if (!$this->validate(['username'=>'required', 'pass'=> 'required'])) {
            return $this->prikaz('index', ['errors'=>'Niste uneli sva polja!']);
        }
        $korisnikModel = new KorisnikModel();
        $korisnik = $korisnikModel->find($this->request->getVar('username'));
        if ($korisnik == null)
            return $this->prikaz('index', ['errors'=>'Korisnik ne postoji!']);
        if ($korisnik->lozinka != $this->request->getVar('pass'))
            return $this->prikaz('index', ['errors'=>'Nije ispravna lozinka!']);
        $this->session->set('korisnik', $korisnik);
        if ($korisnikModel->linkKategorija($korisnik) != null) {
            return redirect()->to(site_url('ProviderController'));
        }
        else 
            return redirect()->to(site_url('UserController'));
    }

    public function register() 
    {
        if (!$this->validate(['email'=>'required', 'ime'=>'required', 'prezime'=> 'required', 'username'=>'required', 'pass'=> 'required', 'pass2'=> 'required'])) {
            return $this->prikaz('registracija', ['errors'=>'Niste uneli sva obavezna polja!']);
        }
        if ($this->request->getVar('uslovi_koriscenja') !=  "on") {
            return $this->prikaz('registracija', ['errors'=>'Niste prihvatili uslove korišćenja!']);
        }
        $korisnikModel = new KorisnikModel();
        $korisnik = $korisnikModel->find($this->request->getVar('username'));
        if ($korisnik != null) {
            return $this->prikaz('registracija', ['errors'=>'Korisničko ime postoji!']);
        }
        if ($this->request->getVar('pass') != $this->request->getVar('pass2')) {
            return $this->prikaz('registracija', ['errors'=>'Lozinke moraju biti iste!']);
        }

        $this->session->set('username', $this->request->getVar('username'));
        $this->session->set('lozinka',$this->request->getVar('lozinka'));
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
		}

		$message = "Zdravo " . $imeKorisnika . ",";
		$message .= "\n\nOvaj maik je poslat na zahtev registracije naloga sa sajta Usluga na dlanu. Kod koji je potrebno da unesete u predviđeno polje je: ". $code;

		$email = \Config\Services::email();

		$email->setFrom('uslugaNaDlanu@gmail.com', 'Usluga na dlanu');
		$email->setTo($emailKorisnika);

		$email->setSubject('Registracija naloga');
		$email->setMessage($message);

		$email->send();

        $this->session->set('code', $code);
    }

    public function confirmCode() {
        if ($this->get->var('verifikacioniKod') != $this->session->get('code')) {
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
            return $this->prikaz('registracija', ['errors'=>'Niste uneli dobar verifikacioni kod.']);
        }
    }
}
