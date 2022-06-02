<?php
/**
  * @author Lazar Premović  2019/0091
  * @author Jana Pašajlić   2019/0132
  */

namespace App\Controllers;

use App\Models\KorisnikModel;

/**
 * GuestController - kontroler za neulogovanog korisnika
 */
class GuestController extends BaseController
{

    /**
     * Prikaz stranice za registraciju
     * 
     * @return Response
     */
    public function register()
    {
        return view('register');
    }

    /**
     * Funkcija za prijavu korisnika na sistem
     * 
     * @postParam string username korisncko ime
     * @postParam string password lozinka
     * 
     * @return Response
     */
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

    /**
     * Funkcija za registraciju korisnika na sistem
     * 
     * @postParam string username korisncko ime
     * @postParam string password lozinka
     * @postParam string password2 ponovljena lozinka
     * @postParam string email email adresa
     * @postParam string ime ime korisnika
     * @postParam string prezime prezime korisnika
     * @postParam string uslovi_koriscenja da li je korisnik prihvatio uslove koriscenja
     * 
     * @return Response 
     */
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
