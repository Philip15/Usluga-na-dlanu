<?php

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;
use App\Models\KorisnikModel;
use Config\Services;
use PHPUnit\Framework\TestCase;

final class GuestControllerTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected function setUp():void
    {
        parent::setUp();
        $this->resetDB();
        unset($_SESSION);
        unset($_FILES);
        $_FILES=[];
        $_SESSION=[];
        Services::validation()->reset();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    protected function resetDB()
    {
        $db = \Config\Database::connect();
        $lines = file("..\docs\Modelovanje Baze\usluga_na_dlanu_data.sql");
        $tmp = '';

        foreach ($lines as $line)
        {
            if (substr($line, 0, 2) == '--' || $line == '')
                continue;
            $tmp .= $line;

            if (substr(trim($line), -1, 1) == ';')
            {
                $db->query($tmp);
                $tmp = '';
            }
        }
    }
   
    public function testOPloginSuccess()
    {
        $res = $this->withSession()->post('/GuestController/OPlogin', ['username'=>'luka', 'password'=>'luka123']);
        $res->assertOK();

        $this->assertNull(session('loginErrorText'));
    }

    public function testOPloginUserEmpty()
    {
        $res = $this->withSession()->post('/GuestController/OPlogin', ['username'=>'', 'password'=>'luka123']);
        $res->assertOK();

        $this->assertEquals(session('loginErrorText'), lang('App.errFieldEmpty'));
    }

    public function testOPloginPassEmpty()
    {
        $res = $this->withSession()->post('/GuestController/OPlogin', ['username'=>'luka', 'password'=>'']);
        $res->assertOK();

        $this->assertEquals(session('loginErrorText'), lang('App.errFieldEmpty'));
    }

    public function testOPloginUserDoesntExist()
    {
        $res = $this->withSession()->post('/GuestController/OPlogin', ['username'=>'nekiuser', 'password'=>'luka123']);
        $res->assertOK();

        $this->assertEquals(session('loginErrorText'), lang('App.errUserNotFound'));
    }

    public function testOPloginPassWrong()
    {
        $res = $this->withSession()->post('/GuestController/OPlogin', ['username'=>'luka', 'password'=>'luka']);
        $res->assertOK();

        $this->assertEquals(session('loginErrorText'),  lang('App.errWrongPassword'));
    }

    public function testOPRegisterSuccess()
    {
        $korM = new KorisnikModel();
        if( $korM->where('korisnickoIme', 'novkorisnik')->find() != null)
        {
            $korM->delete($korM->where('korisnickoIme', 'novkorisnik')->find()[0]->idKorisnika);
        }

        $res = $this->withSession()->post('/GuestController/OPregister', [
            'username'=>'novkorisnik',
            'password'=>'novkor123',
            'password2'=>'novkor123',
            'email'=>'movkormejl@mejl.mejl',
            'ime'=>'novi',
            'prezime'=>'korisnik',
            'uslovi_koriscenja'=>'on',
        ]);
        $res->assertOK();

        $korisnik = $korM->where('korisnickoIme', 'novkorisnik')->find();
        $this->assertTrue(sizeof($korisnik)>0);
        
    }

    public function testOPRegisterUsernameEmpty()
    {
        $korM = new KorisnikModel();
        if( $korM->where('korisnickoIme', 'novkorisnik')->find() != null)
        {
            $korM->delete($korM->where('korisnickoIme', 'novkorisnik')->find()[0]->idKorisnika);
        }

        $res = $this->withSession()->post('/GuestController/OPregister', [
            'username'=>'',
            'password'=>'novkor123',
            'password2'=>'novkor123',
            'email'=>'movkormejl@mejl.mejl',
            'ime'=>'novi',
            'prezime'=>'korisnik',
            'uslovi_koriscenja'=>'on',
        ]);
        $res->assertOK();

        $this->assertEquals(session('errorText'),  lang('App.errFieldEmpty'));
        
    }

    public function testOPRegisterPassEmpty()
    {
        $korM = new KorisnikModel();
        if( $korM->where('korisnickoIme', 'novkorisnik')->find() != null)
        {
            $korM->delete($korM->where('korisnickoIme', 'novkorisnik')->find()[0]->idKorisnika);
        }

        $res = $this->withSession()->post('/GuestController/OPregister', [
            'username'=>'novkorisnik',
            'password'=>'',
            'password2'=>'novkor123',
            'email'=>'movkormejl@mejl.mejl',
            'ime'=>'novi',
            'prezime'=>'korisnik',
            'uslovi_koriscenja'=>'on',
        ]);
        $res->assertOK();

        $this->assertEquals(session('errorText'),  lang('App.errFieldEmpty'));
    }

    public function testOPRegisterPass2Empty()
    {
        $korM = new KorisnikModel();
        if( $korM->where('korisnickoIme', 'novkorisnik')->find() != null)
        {
            $korM->delete($korM->where('korisnickoIme', 'novkorisnik')->find()[0]->idKorisnika);
        }

        $res = $this->withSession()->post('/GuestController/OPregister', [
            'username'=>'novkorisnik',
            'password'=>'novkor123',
            'password2'=>'',
            'email'=>'movkormejl@mejl.mejl',
            'ime'=>'novi',
            'prezime'=>'korisnik',
            'uslovi_koriscenja'=>'on',
        ]);
        $res->assertOK();
        
        $this->assertEquals(session('errorText'),  lang('App.errFieldEmpty'));
    }

    public function testOPRegisterEmailEmpty()
    {
        $korM = new KorisnikModel();
        if( $korM->where('korisnickoIme', 'novkorisnik')->find() != null)
        {
            $korM->delete($korM->where('korisnickoIme', 'novkorisnik')->find()[0]->idKorisnika);
        }

        $res = $this->withSession()->post('/GuestController/OPregister', [
            'username'=>'novkorisnik',
            'password'=>'novkor123',
            'password2'=>'novkor123',
            'email'=>'',
            'ime'=>'novi',
            'prezime'=>'korisnik',
            'uslovi_koriscenja'=>'on',
        ]);
        $res->assertOK();
        
        $this->assertEquals(session('errorText'),  lang('App.errFieldEmpty'));
    }

    public function testOPRegisterNameEmpty()
    {
        $korM = new KorisnikModel();
        if( $korM->where('korisnickoIme', 'novkorisnik')->find() != null)
        {
            $korM->delete($korM->where('korisnickoIme', 'novkorisnik')->find()[0]->idKorisnika);
        }

        $res = $this->withSession()->post('/GuestController/OPregister', [
            'username'=>'novkorisnik',
            'password'=>'novkor123',
            'password2'=>'novkor123',
            'email'=>'movkormejl@mejl.mejl',
            'ime'=>'',
            'prezime'=>'korisnik',
            'uslovi_koriscenja'=>'on',
        ]);
        $res->assertOK();
        
        $this->assertEquals(session('errorText'),  lang('App.errFieldEmpty'));
    }

    public function testOPRegisterSurnameEmpty()
    {
        $korM = new KorisnikModel();
        if( $korM->where('korisnickoIme', 'novkorisnik')->find() != null)
        {
            $korM->delete($korM->where('korisnickoIme', 'novkorisnik')->find()[0]->idKorisnika);
        }

        $res = $this->withSession()->post('/GuestController/OPregister', [
            'username'=>'novkorisnik',
            'password'=>'novkor123',
            'password2'=>'novkor123',
            'email'=>'movkormejl@mejl.mejl',
            'ime'=>'novi',
            'prezime'=>'',
            'uslovi_koriscenja'=>'on',
        ]);
        $res->assertOK();
        
        $this->assertEquals(session('errorText'),  lang('App.errFieldEmpty'));
    }

    public function testOPRegisterEmailNotValid()
    {
        $korM = new KorisnikModel();
        if( $korM->where('korisnickoIme', 'novkorisnik')->find() != null)
        {
            $korM->delete($korM->where('korisnickoIme', 'novkorisnik')->find()[0]->idKorisnika);
        }

        $res = $this->withSession()->post('/GuestController/OPregister', [
            'username'=>'novkorisnik',
            'password'=>'novkor123',
            'password2'=>'novkor123',
            'email'=>'movkormejlmejl.mejl',
            'ime'=>'novi',
            'prezime'=>'korisnik',
            'uslovi_koriscenja'=>'on',
        ]);
        $res->assertOK();
        
        $this->assertEquals(session('errorText'),  lang('App.errInvalidEmail'));
    }

    public function testOPRegisterEmailRepeated()
    {
        $korM = new KorisnikModel();
        if( $korM->where('korisnickoIme', 'novkorisnik')->find() != null)
        {
            $korM->delete($korM->where('korisnickoIme', 'novkorisnik')->find()[0]->idKorisnika);
        }

        $res = $this->withSession()->post('/GuestController/OPregister', [
            'username'=>'novkorisnik',
            'password'=>'novkor123',
            'password2'=>'novkor123',
            'email'=>'zika@gmail.com',
            'ime'=>'novi',
            'prezime'=>'korisnik',
            'uslovi_koriscenja'=>'on',
        ]);
        $res->assertOK();
        
        $this->assertEquals(session('errorText'),  lang('App.errEmailAlreadyExists'));
    }

    public function testOPRegisterTermsNotAccepted()
    {
        $korM = new KorisnikModel();
        if( $korM->where('korisnickoIme', 'novkorisnik')->find() != null)
        {
            $korM->delete($korM->where('korisnickoIme', 'novkorisnik')->find()[0]->idKorisnika);
        }

        $res = $this->withSession()->post('/GuestController/OPregister', [
            'username'=>'novkorisnik',
            'password'=>'novkor123',
            'password2'=>'novkor123',
            'email'=>'movkormejl@mejl.mejl',
            'ime'=>'novi',
            'prezime'=>'korisnik',
            'uslovi_koriscenja'=>'off',
        ]);
        $res->assertOK();
        
        $this->assertEquals(session('errorText'),  lang('App.errNotTNC'));
    }

    public function testOPRegisterUsernameRepeated()
    {
        $korM = new KorisnikModel();
        if( $korM->where('korisnickoIme', 'novkorisnik')->find() != null)
        {
            $korM->delete($korM->where('korisnickoIme', 'novkorisnik')->find()[0]->idKorisnika);
        }

        $res = $this->withSession()->post('/GuestController/OPregister', [
            'username'=>'mika',
            'password'=>'novkor123',
            'password2'=>'novkor123',
            'email'=>'movkormejl@mejl.mejl',
            'ime'=>'novi',
            'prezime'=>'korisnik',
            'uslovi_koriscenja'=>'on',
        ]);
        $res->assertOK();
        
        $this->assertEquals(session('errorText'),  lang('App.errUserAlreadyExists'));
    }

    public function testOPRegisterPasswordNotRepeated()
    {
        $korM = new KorisnikModel();
        if( $korM->where('korisnickoIme', 'novkorisnik')->find() != null)
        {
            $korM->delete($korM->where('korisnickoIme', 'novkorisnik')->find()[0]->idKorisnika);
        }

        $res = $this->withSession()->post('/GuestController/OPregister', [
            'username'=>'novkorisnik',
            'password'=>'novkor123',
            'password2'=>'novkor12',
            'email'=>'movkormejl@mejl.mejl',
            'ime'=>'novi',
            'prezime'=>'korisnik',
            'uslovi_koriscenja'=>'on',
        ]);
        $res->assertOK();
        
        $this->assertEquals(session('errorText'),  lang('App.errPasswordConfirmation'));
    }

}