<?php

use App\Controllers\UserController;
use App\Models\KorisnikModel;
use App\Models\ZahtevModel;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;
use Config\Services;

final class UserControllerTest extends CIUnitTestCase
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
        $_SESSION['user']=KorisnikModel::findById(1);
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

    public function testOPlogout()
    {
        $result = $this->withSession()->get('/UserController/OPlogout');
        $result->assertRedirect();
        $result->asssertSessionMissing('user');
    }

    public function testEditProfile()
    {
        $result = $this->withSession()->get('/UserController/editProfile');
        $result->assertOK();
        $result->assertSee('pl190091d@student.etf.bg.ac.rs');
        $result->assertSee('Pregled profila');

        $_SESSION['user']=KorisnikModel::findById(2);
        $result = $this->withSession()->get('/UserController/editProfile');
        $result->assertOK();
        $result->assertSee('mika@gmail');
        $result->assertSee('Pregled profila');
        $result->assertSee('Adresa poslovanja');

        $_SESSION['user']=KorisnikModel::findById(14);
        $result = $this->withSession()->get('/UserController/editProfile');
        $result->assertOK();
        $result->assertSee('test@test.test');
        $result->assertSee('Pregled profila');
        $result->assertSee('Izmena profila u pružaoca usluge');
    }

    public function testOPSaveChangesUser1()
    {
        $result = $this->withSession()->post('/UserController/OPsaveChanges',['ime'=>'novoIme','prezime'=>'novoPrezime','username'=>'noviusername','email'=>'novi@email.com','dodatneInformacije'=>'opcioni opis']);
        $result->assertRedirectTo(base_url('/UserController/editProfile'));
        $result->assertSessionHas('alertErrorText',lang('App.successfulProfileUpdate'));
        $korisnik=KorisnikModel::findById(1);
        $this->assertEquals('novoIme',$korisnik->ime);
        $this->assertEquals('novoPrezime',$korisnik->prezime);
        $this->assertEquals('noviusername',$korisnik->korisnickoIme);
        $this->assertEquals('novi@email.com',$korisnik->email);
        $this->assertEquals('opcioni opis',$korisnik->opis);
        self::resetDB();
    }

    public function testOPSaveChangesUser2()
    {
        $result = $this->withSession()->post('/UserController/OPsaveChanges',['ime'=>'novoIme','prezime'=>'novoPrezime','username'=>'noviusername','email'=>'novi@email.com']);
        $result->assertRedirectTo(base_url('/UserController/editProfile'));
        $result->assertSessionHas('alertErrorText',lang('App.successfulProfileUpdate'));
        $korisnik=KorisnikModel::findById(1);
        $this->assertEquals('novoIme',$korisnik->ime);
        $this->assertEquals('novoPrezime',$korisnik->prezime);
        $this->assertEquals('noviusername',$korisnik->korisnickoIme);
        $this->assertEquals('novi@email.com',$korisnik->email);
        $this->assertNull($korisnik->opis);
        self::resetDB();
    }

    public function testOPSaveChangesUserInvalid0()
    {
        $result = $this->withSession()->post('/UserController/OPsaveChanges',['prezime'=>'novoPrezime','username'=>'noviusername','email'=>'novi@email.com','dodatneInformacije'=>'opcioni opis']);
        $result->assertRedirectTo(base_url('/UserController/editProfile'));
        $result->assertSessionHas('errorText',lang('App.errRequiredFields'));
    }

    public function testOPSaveChangesUserInvalid1()
    {
        $result = $this->withSession()->post('/UserController/OPsaveChanges',['ime'=>'novoIme','username'=>'noviusername','email'=>'novi@email.com','dodatneInformacije'=>'opcioni opis']);
        $result->assertRedirectTo(base_url('/UserController/editProfile'));
        $result->assertSessionHas('errorText',lang('App.errRequiredFields'));
    }

    public function testOPSaveChangesUserInvalid2()
    {
        $result = $this->withSession()->post('/UserController/OPsaveChanges',['ime'=>'novoIme','prezime'=>'novoPrezime','email'=>'novi@email.com','dodatneInformacije'=>'opcioni opis']);
        $result->assertRedirectTo(base_url('/UserController/editProfile'));
        $result->assertSessionHas('errorText',lang('App.errRequiredFields'));
    }

    public function testOPSaveChangesUserInvalid3()
    {
        $result = $this->withSession()->post('/UserController/OPsaveChanges',['ime'=>'novoIme','prezime'=>'novoPrezime','username'=>'noviusername','dodatneInformacije'=>'opcioni opis']);
        $result->assertRedirectTo(base_url('/UserController/editProfile'));
        $result->assertSessionHas('errorText',lang('App.errRequiredFields'));
    }

    public function testOPSaveChangesUserInvalid4()
    {
        $result = $this->withSession()->post('/UserController/OPsaveChanges',['ime'=>'novoIme','prezime'=>'novoPrezime','username'=>'noviusername','email'=>'noviemail.com','dodatneInformacije'=>'opcioni opis']);
        $result->assertRedirectTo(base_url('/UserController/editProfile'));
        $result->assertSessionHas('errorText',lang('App.errInvalidEmail'));
    }

    public function testOPSaveChangesUserInvalid5()
    {
        $result = $this->withSession()->post('/UserController/OPsaveChanges',['ime'=>'novoIme','prezime'=>'novoPrezime','username'=>'noviusername','email'=>'test@test.test','dodatneInformacije'=>'opcioni opis']);
        $result->assertRedirectTo(base_url('/UserController/editProfile'));
        $result->assertSessionHas('errorText',lang('App.errEmailAlreadyExists'));
    }

    public function testOPSaveChangesUserInvalid6()
    {
        $result = $this->withSession()->post('/UserController/OPsaveChanges',['ime'=>'novoIme','prezime'=>'novoPrezime','username'=>'test','email'=>'novi@email.com','dodatneInformacije'=>'opcioni opis']);
        $result->assertRedirectTo(base_url('/UserController/editProfile'));
        $result->assertSessionHas('errorText',lang('App.errUserAlreadyExists'));
    }

    public function testOPSaveChangesProvider1()
    {
        $_SESSION['user']=KorisnikModel::findById(2);
        $result = $this->withSession()->post('/UserController/OPsaveChanges',['ime'=>'novoIme','prezime'=>'novoPrezime','username'=>'noviusername','email'=>'novi@email.com','dodatneInformacije'=>'opcioni opis','adresa'=>'novaAdresa','kategorija'=>2,'lat'=>-1,'lon'=>1]);
        $result->assertRedirectTo(base_url('/UserController/editProfile'));
        $result->assertSessionHas('alertErrorText',lang('App.successfulProfileUpdate'));
        $korisnik=KorisnikModel::findById(2);
        $this->assertEquals('novoIme',$korisnik->ime);
        $this->assertEquals('novoPrezime',$korisnik->prezime);
        $this->assertEquals('noviusername',$korisnik->korisnickoIme);
        $this->assertEquals('novi@email.com',$korisnik->email);
        $this->assertEquals('opcioni opis',$korisnik->opis);
        $this->assertEquals('novaAdresa',$korisnik->adresa);
        $this->assertEquals(2,$korisnik->idKategorije);
        $this->assertEquals(-1,$korisnik->lat);
        $this->assertEquals(1,$korisnik->lon);
        self::resetDB();
    }

    public function testOPSaveChangesProviderInvalid0()
    {
        $_SESSION['user']=KorisnikModel::findById(2);
        $result = $this->withSession()->post('/UserController/OPsaveChanges',['ime'=>'novoIme','prezime'=>'novoPrezime','username'=>'noviusername','email'=>'novi@email.com','dodatneInformacije'=>'opcioni opis','kategorija'=>2,'lat'=>-1,'lon'=>1]);
        $result->assertRedirectTo(base_url('/UserController/editProfile'));
        $result->assertSessionHas('errorText',lang('App.errEmptyFieldsConversion'));
    }

    public function testOPSaveChangesProviderInvalid1()
    {
        $_SESSION['user']=KorisnikModel::findById(2);
        $result = $this->withSession()->post('/UserController/OPsaveChanges',['ime'=>'novoIme','prezime'=>'novoPrezime','username'=>'noviusername','email'=>'novi@email.com','dodatneInformacije'=>'opcioni opis','adresa'=>'novaAdresa','lat'=>-1,'lon'=>1]);
        $result->assertRedirectTo(base_url('/UserController/editProfile'));
        $result->assertSessionHas('errorText',lang('App.errEmptyFieldsConversion'));
    }

    public function testOPSaveChangesProviderInvalid2()
    {
        $_SESSION['user']=KorisnikModel::findById(2);
        $result = $this->withSession()->post('/UserController/OPsaveChanges',['ime'=>'novoIme','prezime'=>'novoPrezime','username'=>'noviusername','email'=>'novi@email.com','dodatneInformacije'=>'opcioni opis','adresa'=>'novaAdresa','kategorija'=>2,'lon'=>1]);
        $result->assertRedirectTo(base_url('/UserController/editProfile'));
        $result->assertSessionHas('errorText',lang('App.errEmptyFieldsConversion'));
    }

    public function testOPSaveChangesProviderInvalid3()
    {
        $_SESSION['user']=KorisnikModel::findById(2);
        $result = $this->withSession()->post('/UserController/OPsaveChanges',['ime'=>'novoIme','prezime'=>'novoPrezime','username'=>'noviusername','email'=>'novi@email.com','dodatneInformacije'=>'opcioni opis','adresa'=>'novaAdresa','kategorija'=>2,'lat'=>-1]);
        $result->assertRedirectTo(base_url('/UserController/editProfile'));
        $result->assertSessionHas('errorText',lang('App.errEmptyFieldsConversion'));
    }

    public function testOPSaveChangesProviderInvalid4()
    {
        $_SESSION['user']=KorisnikModel::findById(2);
        $result = $this->withSession()->post('/UserController/OPsaveChanges',['ime'=>'novoIme','prezime'=>'novoPrezime','username'=>'noviusername','email'=>'novi@email.com','dodatneInformacije'=>'opcioni opis','adresa'=>'novaAdresa','kategorija'=>99,'lat'=>-1,'lon'=>1]);
        $result->assertRedirectTo(base_url('/UserController/editProfile'));
        $result->assertSessionHas('errorText',lang('App.errEmptyFieldsConversion'));
    }

    public function testOPSaveChangesProviderInvalid5()
    {
        $_SESSION['user']=KorisnikModel::findById(2);
        $result = $this->withSession()->post('/UserController/OPsaveChanges',['ime'=>'novoIme','prezime'=>'novoPrezime','username'=>'noviusername','email'=>'novi@email.com','dodatneInformacije'=>'opcioni opis','adresa'=>'novaAdresa','kategorija'=>'aaa','lat'=>-1,'lon'=>1]);
        $result->assertRedirectTo(base_url('/UserController/editProfile'));
        $result->assertSessionHas('errorText',lang('App.errEmptyFieldsConversion'));
    }

    public function testOPSaveChangesProviderInvalid6()
    {
        $_SESSION['user']=KorisnikModel::findById(2);
        $result = $this->withSession()->post('/UserController/OPsaveChanges',['ime'=>'novoIme','prezime'=>'novoPrezime','username'=>'noviusername','email'=>'novi@email.com','dodatneInformacije'=>'opcioni opis','adresa'=>'novaAdresa','kategorija'=>2,'lat'=>'aaa','lon'=>1]);
        $result->assertRedirectTo(base_url('/UserController/editProfile'));
        $result->assertSessionHas('errorText',lang('App.errEmptyFieldsConversion'));
    }

    public function testOPSaveChangesProviderInvalid7()
    {
        $_SESSION['user']=KorisnikModel::findById(2);
        $result = $this->withSession()->post('/UserController/OPsaveChanges',['ime'=>'novoIme','prezime'=>'novoPrezime','username'=>'noviusername','email'=>'novi@email.com','dodatneInformacije'=>'opcioni opis','adresa'=>'novaAdresa','kategorija'=>2,'lat'=>-1,'lon'=>'aaa']);
        $result->assertRedirectTo(base_url('/UserController/editProfile'));
        $result->assertSessionHas('errorText',lang('App.errEmptyFieldsConversion'));
    }

    public function testOPSaveChangesProviderInvalid8()
    {
        $_SESSION['user']=KorisnikModel::findById(2);
        $result = $this->withSession()->post('/UserController/OPsaveChanges',['ime'=>'novoIme','prezime'=>'novoPrezime','username'=>'noviusername','email'=>'novi@email.com','dodatneInformacije'=>'opcioni opis','adresa'=>'novaAdresa','kategorija'=>2,'lat'=>999,'lon'=>1]);
        $result->assertRedirectTo(base_url('/UserController/editProfile'));
        $result->assertSessionHas('errorText',lang('App.errEmptyFieldsConversion'));
    }

    public function testOPSaveChangesProviderInvalid9()
    {
        $_SESSION['user']=KorisnikModel::findById(2);
        $result = $this->withSession()->post('/UserController/OPsaveChanges',['ime'=>'novoIme','prezime'=>'novoPrezime','username'=>'noviusername','email'=>'novi@email.com','dodatneInformacije'=>'opcioni opis','adresa'=>'novaAdresa','kategorija'=>2,'lat'=>-1,'lon'=>999]);
        $result->assertRedirectTo(base_url('/UserController/editProfile'));
        $result->assertSessionHas('errorText',lang('App.errEmptyFieldsConversion'));
    }

    public function testOPchangeProfilePicture()
    {
        $_FILES['profilePicture']['tmp_name']="D:\Pictures\\test.jpg";
        $_FILES['profilePicture']['size']=filesize("D:\Pictures\\test.jpg");
        $result = $this->withSession()->post('/UserController/OPchangeProfilePicture');
        $result->assertRedirectTo(base_url('/UserController/editProfile'));
        $result->assertSessionHas('alertErrorText',lang('App.successfulPictureChange'));
        $korisnik=KorisnikModel::findById(1);
        $img = file_get_contents("D:\Pictures\\test.jpg");
        $this->assertEquals($img,$korisnik->profilnaSlika);
        self::resetDB();
    }

    public function testOPchangeProfilePictureInvalid0()
    {
        $_FILES['profilePicture']['tmp_name']='';
        $_FILES['profilePicture']['size']=0;
        $result = $this->withSession()->post('/UserController/OPchangeProfilePicture');
        $result->assertRedirectTo(base_url('/UserController/editProfile'));
        $result->assertSessionHas('alertErrorText',lang('App.errNoPicture'));
    }

    public function testOPchangeProfilePictureInvalid1()
    {
        $result = $this->withSession()->post('/UserController/OPchangeProfilePicture');
        $result->assertRedirectTo(base_url('/UserController/editProfile'));
        $result->assertSessionHas('alertErrorText',lang('App.errNoPicture'));
    }

    public function testOPchangeProfilePictureInvalid2()
    {
        $_FILES['profilePicture']['tmp_name']="D:\Pictures\\Sanja.mp4";
        $_FILES['profilePicture']['size']=filesize("D:\Pictures\\Sanja.mp4");
        $result = $this->withSession()->post('/UserController/OPchangeProfilePicture');
        $result->assertRedirectTo(base_url('/UserController/editProfile'));
        $result->assertSessionHas('alertErrorText',lang('App.errWrongType'));
    }

    public function testOPchangeProfilePictureInvalid3()
    {
        $_FILES['profilePicture']['tmp_name']="D:\Pictures\Wallpaper\Anime\\1snKHV169.png";
        $_FILES['profilePicture']['size']=filesize("D:\Pictures\Wallpaper\Anime\\1snKHV169.png");
        $result = $this->withSession()->post('/UserController/OPchangeProfilePicture');
        $result->assertRedirectTo(base_url('/UserController/editProfile'));
        $result->assertSessionHas('alertErrorText',lang('App.errTooBig'));
    }

    public function testAJAXpicturePreview()
    {
        $_FILES['profilePicture']['tmp_name']="D:\Pictures\\test.jpg";
        $_FILES['profilePicture']['size']=filesize("D:\Pictures\\test.jpg");
        $result = $this->withSession()->post('/UserController/AJAXpicturePreview');
        $result->assertSeeElement('img');
    }

    public function testAJAXpicturePreviewInvalid0()
    {
        $_FILES['profilePicture']['tmp_name']='';
        $_FILES['profilePicture']['size']=0;
        $result = $this->withSession()->post('/UserController/AJAXpicturePreview');
        $result->assertSee(lang('App.errNoPicture'));
    }

    public function testAJAXpicturePreviewInvalid1()
    {
        $result = $this->withSession()->post('/UserController/AJAXpicturePreview');
        $result->assertSee(lang('App.errNoPicture'));
    }

    public function testAJAXpicturePreviewInvalid2()
    {
        $_FILES['profilePicture']['tmp_name']="D:\Pictures\\Sanja.mp4";
        $_FILES['profilePicture']['size']=filesize("D:\Pictures\\Sanja.mp4");
        $result = $this->withSession()->post('/UserController/AJAXpicturePreview');
        $result->assertSee(lang('App.errWrongType'));
    }

    public function testAJAXpicturePreviewInvalid3()
    {
        $_FILES['profilePicture']['tmp_name']="D:\Pictures\Wallpaper\Anime\\1snKHV169.png";
        $_FILES['profilePicture']['size']=filesize("D:\Pictures\Wallpaper\Anime\\1snKHV169.png");
        $result = $this->withSession()->post('/UserController/AJAXpicturePreview');
        $result->assertSee(lang('App.errTooBig'));
    }

    public function testOPconvertProfile()
    {
        $_SESSION['user']=KorisnikModel::findById(14);
        $result = $this->withSession()->post('/UserController/OPConvertProfile',['kategorija'=>1,'adresaPoslovanja'=>'testAdresa','lat'=>1,'lon'=>2]);
        $result->assertRedirectTo(base_url('/UserController/editProfile'));
        $result->assertSessionHas('alertErrorText',lang('App.successfulConversion'));
        $korisnik=KorisnikModel::findById(14);
        $this->assertEquals(1,$korisnik->idKategorije);
        $this->assertEquals('testAdresa',$korisnik->adresa);
        $this->assertEquals(1,$korisnik->lat);
        $this->assertEquals(2,$korisnik->lon);
        $this->assertEquals(2,$korisnik->pruzalac);
        self::resetDB();

        $_SESSION['user']=KorisnikModel::findById(1);
        $result = $this->withSession()->post('/UserController/OPConvertProfile',['kategorija'=>1,'adresaPoslovanja'=>'testAdresa','lat'=>1,'lon'=>2]);
        $result->assertRedirectTo(base_url('/UserController/editProfile'));
        $result->assertSessionHas('errorTextConversion',lang('App.errEmptyFieldsConversion'));

        $_SESSION['user']=KorisnikModel::findById(2);
        $result = $this->withSession()->post('/UserController/OPConvertProfile',['kategorija'=>1,'adresaPoslovanja'=>'testAdresa','lat'=>1,'lon'=>2]);
        $result->assertRedirectTo(base_url('/UserController/editProfile'));
        $result->assertSessionHas('errorTextConversion',lang('App.errEmptyFieldsConversion'));

        $_SESSION['user']=KorisnikModel::findById(14);
        $result = $this->withSession()->post('/UserController/OPConvertProfile',['adresaPoslovanja'=>'testAdresa','lat'=>1,'lon'=>2]);
        $result->assertRedirectTo(base_url('/UserController/editProfile'));
        $result->assertSessionHas('errorTextConversion',lang('App.errEmptyFieldsConversion'));

        $_SESSION['user']=KorisnikModel::findById(14);
        $result = $this->withSession()->post('/UserController/OPConvertProfile',['kategorija'=>1,'lat'=>1,'lon'=>2]);
        $result->assertRedirectTo(base_url('/UserController/editProfile'));
        $result->assertSessionHas('errorTextConversion',lang('App.errEmptyFieldsConversion'));

        $_SESSION['user']=KorisnikModel::findById(14);
        $result = $this->withSession()->post('/UserController/OPConvertProfile',['kategorija'=>1,'adresaPoslovanja'=>'testAdresa','lon'=>2]);
        $result->assertRedirectTo(base_url('/UserController/editProfile'));
        $result->assertSessionHas('errorTextConversion',lang('App.errEmptyFieldsConversion'));

        $_SESSION['user']=KorisnikModel::findById(14);
        $result = $this->withSession()->post('/UserController/OPConvertProfile',['kategorija'=>1,'adresaPoslovanja'=>'testAdresa','lat'=>1]);
        $result->assertRedirectTo(base_url('/UserController/editProfile'));
        $result->assertSessionHas('errorTextConversion',lang('App.errEmptyFieldsConversion'));

        $_SESSION['user']=KorisnikModel::findById(14);
        $result = $this->withSession()->post('/UserController/OPConvertProfile',['kategorija'=>'aaaa','adresaPoslovanja'=>'testAdresa','lat'=>1,'lon'=>2]);
        $result->assertRedirectTo(base_url('/UserController/editProfile'));
        $result->assertSessionHas('errorTextConversion',lang('App.errEmptyFieldsConversion'));

        $_SESSION['user']=KorisnikModel::findById(14);
        $result = $this->withSession()->post('/UserController/OPConvertProfile',['kategorija'=>99,'adresaPoslovanja'=>'testAdresa','lat'=>1,'lon'=>2]);
        $result->assertRedirectTo(base_url('/UserController/editProfile'));
        $result->assertSessionHas('errorTextConversion',lang('App.errEmptyFieldsConversion'));

        $_SESSION['user']=KorisnikModel::findById(14);
        $result = $this->withSession()->post('/UserController/OPConvertProfile',['kategorija'=>1,'adresaPoslovanja'=>'testAdresa','lat'=>'aaa','lon'=>2]);
        $result->assertRedirectTo(base_url('/UserController/editProfile'));
        $result->assertSessionHas('errorTextConversion',lang('App.errEmptyFieldsConversion'));

        $_SESSION['user']=KorisnikModel::findById(14);
        $result = $this->withSession()->post('/UserController/OPConvertProfile',['kategorija'=>1,'adresaPoslovanja'=>'testAdresa','lat'=>1,'lon'=>'aaaa']);
        $result->assertRedirectTo(base_url('/UserController/editProfile'));
        $result->assertSessionHas('errorTextConversion',lang('App.errEmptyFieldsConversion'));

        $_SESSION['user']=KorisnikModel::findById(14);
        $result = $this->withSession()->post('/UserController/OPConvertProfile',['kategorija'=>1,'adresaPoslovanja'=>'testAdresa','lat'=>-90.1,'lon'=>2]);
        $result->assertRedirectTo(base_url('/UserController/editProfile'));
        $result->assertSessionHas('errorTextConversion',lang('App.errEmptyFieldsConversion'));

        $_SESSION['user']=KorisnikModel::findById(14);
        $result = $this->withSession()->post('/UserController/OPConvertProfile',['kategorija'=>1,'adresaPoslovanja'=>'testAdresa','lat'=>-90,'lon'=>2]);
        $result->assertRedirectTo(base_url('/UserController/editProfile'));
        $result->assertSessionHas('alertErrorText',lang('App.successfulConversion'));

        $_SESSION['user']=KorisnikModel::findById(14);
        $result = $this->withSession()->post('/UserController/OPConvertProfile',['kategorija'=>1,'adresaPoslovanja'=>'testAdresa','lat'=>-89.9,'lon'=>2]);
        $result->assertRedirectTo(base_url('/UserController/editProfile'));
        $result->assertSessionHas('alertErrorText',lang('App.successfulConversion'));

        $_SESSION['user']=KorisnikModel::findById(14);
        $result = $this->withSession()->post('/UserController/OPConvertProfile',['kategorija'=>1,'adresaPoslovanja'=>'testAdresa','lat'=>89.9,'lon'=>2]);
        $result->assertRedirectTo(base_url('/UserController/editProfile'));
        $result->assertSessionHas('alertErrorText',lang('App.successfulConversion'));

        $_SESSION['user']=KorisnikModel::findById(14);
        $result = $this->withSession()->post('/UserController/OPConvertProfile',['kategorija'=>1,'adresaPoslovanja'=>'testAdresa','lat'=>90,'lon'=>2]);
        $result->assertRedirectTo(base_url('/UserController/editProfile'));
        $result->assertSessionHas('alertErrorText',lang('App.successfulConversion'));

        $_SESSION['user']=KorisnikModel::findById(14);
        $result = $this->withSession()->post('/UserController/OPConvertProfile',['kategorija'=>1,'adresaPoslovanja'=>'testAdresa','lat'=>90.1,'lon'=>2]);
        $result->assertRedirectTo(base_url('/UserController/editProfile'));
        $result->assertSessionHas('errorTextConversion',lang('App.errEmptyFieldsConversion'));

        $_SESSION['user']=KorisnikModel::findById(14);
        $result = $this->withSession()->post('/UserController/OPConvertProfile',['kategorija'=>1,'adresaPoslovanja'=>'testAdresa','lat'=>-89,'lon'=>-180.1]);
        $result->assertRedirectTo(base_url('/UserController/editProfile'));
        $result->assertSessionHas('errorTextConversion',lang('App.errEmptyFieldsConversion'));

        $_SESSION['user']=KorisnikModel::findById(14);
        $result = $this->withSession()->post('/UserController/OPConvertProfile',['kategorija'=>1,'adresaPoslovanja'=>'testAdresa','lat'=>-89,'lon'=>-180]);
        $result->assertRedirectTo(base_url('/UserController/editProfile'));
        $result->assertSessionHas('alertErrorText',lang('App.successfulConversion'));

        $_SESSION['user']=KorisnikModel::findById(14);
        $result = $this->withSession()->post('/UserController/OPConvertProfile',['kategorija'=>1,'adresaPoslovanja'=>'testAdresa','lat'=>-89,'lon'=>-179.9]);
        $result->assertRedirectTo(base_url('/UserController/editProfile'));
        $result->assertSessionHas('alertErrorText',lang('App.successfulConversion'));

        $_SESSION['user']=KorisnikModel::findById(14);
        $result = $this->withSession()->post('/UserController/OPConvertProfile',['kategorija'=>1,'adresaPoslovanja'=>'testAdresa','lat'=>-89,'lon'=>179.9]);
        $result->assertRedirectTo(base_url('/UserController/editProfile'));
        $result->assertSessionHas('alertErrorText',lang('App.successfulConversion'));

        $_SESSION['user']=KorisnikModel::findById(14);
        $result = $this->withSession()->post('/UserController/OPConvertProfile',['kategorija'=>1,'adresaPoslovanja'=>'testAdresa','lat'=>-89,'lon'=>180]);
        $result->assertRedirectTo(base_url('/UserController/editProfile'));
        $result->assertSessionHas('alertErrorText',lang('App.successfulConversion'));

        $_SESSION['user']=KorisnikModel::findById(14);
        $result = $this->withSession()->post('/UserController/OPConvertProfile',['kategorija'=>1,'adresaPoslovanja'=>'testAdresa','lat'=>-89,'lon'=>180.1]);
        $result->assertRedirectTo(base_url('/UserController/editProfile'));
        $result->assertSessionHas('errorTextConversion',lang('App.errEmptyFieldsConversion'));
        self::resetDB();
    }

    public function testOPupdatePassword1()
    {
        $result = $this->withSession()->post('/UserController/OPupdatePassword',['oldPassword'=>'qwerty','newPassword'=>'asdf','newPasswordAgain'=>'asdf']);
        $result->assertRedirectTo(base_url('/UserController/editProfile'));
        $result->assertSessionHas('alertErrorText',lang('App.successfulPasswordChange'));
        $korisnik = KorisnikModel::findById(1);
        $this->assertTrue(password_verify('asdf',$korisnik->lozinka));
        self::resetDB();
    }

    public function testOPupdatePassword2()
    {
        $result = $this->withSession()->post('/UserController/OPupdatePassword',['newPassword'=>'asdf','newPasswordAgain'=>'asdf']);
        $result->assertRedirectTo(base_url('/UserController/editProfile'));
        $result->assertSessionHas('errorTextNewPassword',lang('App.errFieldEmpty'));
    }

    public function testOPupdatePassword3()
    {
        $result = $this->withSession()->post('/UserController/OPupdatePassword',['oldPassword'=>'qwerty','newPasswordAgain'=>'asdf']);
        $result->assertRedirectTo(base_url('/UserController/editProfile'));
        $result->assertSessionHas('errorTextNewPassword',lang('App.errFieldEmpty'));
    }

    public function testOPupdatePassword4()
    {
        $result = $this->withSession()->post('/UserController/OPupdatePassword',['oldPassword'=>'qwerty','newPassword'=>'asdf']);
        $result->assertRedirectTo(base_url('/UserController/editProfile'));
        $result->assertSessionHas('errorTextNewPassword',lang('App.errFieldEmpty'));
    }

    public function testOPupdatePassword5()
    {
        $result = $this->withSession()->post('/UserController/OPupdatePassword',['oldPassword'=>'qwertya','newPassword'=>'asdf','newPasswordAgain'=>'asdf']);
        $result->assertRedirectTo(base_url('/UserController/editProfile'));
        $result->assertSessionHas('errorTextNewPassword',lang('App.errOldPassword'));
    }

    public function testOPupdatePassword6()
    {
        $result = $this->withSession()->post('/UserController/OPupdatePassword',['oldPassword'=>'qwerty','newPassword'=>'asdfa','newPasswordAgain'=>'asdf']);
        $result->assertRedirectTo(base_url('/UserController/editProfile'));
        $result->assertSessionHas('errorTextNewPassword',lang('App.errPasswordConfirmation'));
    }

    public function testReviews()
    {
        $_SESSION['user']=KorisnikModel::findById(14);
        $result = $this->withSession()->get('/UserController/reviews');
        $result->assertOK();
        $result->assertSee('Zaki');
        $result->assertSee('05/06/2022');

        $_SESSION['user']=KorisnikModel::findById(1);
        $result = $this->withSession()->get('/UserController/reviews');
        $result->assertOK();
        $result->assertSee(lang('App.noReviews'));
    }

    public function testOPpostReview1()
    {
        $_SESSION['user']=KorisnikModel::findById(14);
        $result = $this->withSession()->post('/UserController/OPpostReview',['id'=>5,'rating'=>3,'comment'=>'komentar']);
        $result->assertRedirectTo(base_url('UserController/reviews'));
        $result->assertSessionHas('alertErrorText',lang('App.tankYouReview'));
        $zahtev = ZahtevModel::findById(5);
        $this->assertEquals(5,$zahtev->stanje);
        $this->assertEquals(3,$zahtev->ocena);
        $this->assertEquals('komentar',$zahtev->recenzija);
        self::resetDB();
    }

    public function testOPpostReview2()
    {
        $_SESSION['user']=KorisnikModel::findById(14);
        $result = $this->withSession()->post('/UserController/OPpostReview',['rating'=>3,'comment'=>'komentar']);
        $result->assertRedirectTo(base_url('UserController/reviews'));
        $result->assertSessionMissing('alertErrorText');
    }

    public function testOPpostReview3()
    {
        $_SESSION['user']=KorisnikModel::findById(14);
        $result = $this->withSession()->post('/UserController/OPpostReview',['id'=>5,'comment'=>'komentar']);
        $result->assertRedirectTo(base_url('UserController/reviews'));
        $result->assertSessionMissing('alertErrorText');
    }

    public function testOPpostReview4()
    {
        $_SESSION['user']=KorisnikModel::findById(14);
        $result = $this->withSession()->post('/UserController/OPpostReview',['id'=>5,'rating'=>3]);
        $result->assertRedirectTo(base_url('UserController/reviews'));
        $result->assertSessionMissing('alertErrorText');
    }

    public function testOPpostReview5()
    {
        $_SESSION['user']=KorisnikModel::findById(14);
        $result = $this->withSession()->post('/UserController/OPpostReview',['id'=>'asd','rating'=>3,'comment'=>'komentar']);
        $result->assertRedirectTo(base_url('UserController/reviews'));
        $result->assertSessionMissing('alertErrorText');
    }

    public function testOPpostReview6()
    {
        $_SESSION['user']=KorisnikModel::findById(14);
        $result = $this->withSession()->post('/UserController/OPpostReview',['id'=>5,'rating'=>'asdasd','comment'=>'komentar']);
        $result->assertRedirectTo(base_url('UserController/reviews'));
        $result->assertSessionMissing('alertErrorText');
    }

    public function testOPpostReview7()
    {
        $_SESSION['user']=KorisnikModel::findById(14);
        $result = $this->withSession()->post('/UserController/OPpostReview',['id'=>99,'rating'=>3,'comment'=>'komentar']);
        $result->assertRedirectTo(base_url('UserController/reviews'));
        $result->assertSessionMissing('alertErrorText');
    }

    public function testOPpostReview8()
    {
        $zm = new ZahtevModel();
        $zm->update(1,['stanje'=>4]);
        $_SESSION['user']=KorisnikModel::findById(14);
        $result = $this->withSession()->post('/UserController/OPpostReview',['id'=>4,'rating'=>3,'comment'=>'komentar']);
        $result->assertRedirectTo(base_url('UserController/reviews'));
        $result->assertSessionMissing('alertErrorText');
        self::resetDB();
    }

    public function testOPpostReview9()
    {
        $_SESSION['user']=KorisnikModel::findById(14);
        $result = $this->withSession()->post('/UserController/OPpostReview',['id'=>8,'rating'=>3,'comment'=>'komentar']);
        $result->assertRedirectTo(base_url('UserController/reviews'));
        $result->assertSessionMissing('alertErrorText');
    }

    public function testOPpostReview10()
    {
        $_SESSION['user']=KorisnikModel::findById(14);
        $result = $this->withSession()->post('/UserController/OPpostReview',['id'=>5,'rating'=>6,'comment'=>'komentar']);
        $result->assertRedirectTo(base_url('UserController/reviews'));
        $result->assertSessionMissing('alertErrorText');
    }

    public function testOPpostReview11()
    {
        $_SESSION['user']=KorisnikModel::findById(14);
        $result = $this->withSession()->post('/UserController/OPpostReview',['id'=>5,'rating'=>0,'comment'=>'komentar']);
        $result->assertRedirectTo(base_url('UserController/reviews'));
        $result->assertSessionHas('alertErrorText',lang('App.mandatoryRating'));
    }

    public function testOPpostReview12()
    {
        $_SESSION['user']=KorisnikModel::findById(14);
        $result = $this->withSession()->post('/UserController/OPpostReview',['id'=>5,'rating'=>-1,'comment'=>'komentar']);
        $result->assertRedirectTo(base_url('UserController/reviews'));
        $result->assertSessionHas('alertErrorText',lang('App.mandatoryRating'));
    }

    public function testOPpostReview13()
    {
        $_SESSION['user']=KorisnikModel::findById(14);
        $result = $this->withSession()->post('/UserController/OPpostReview',['id'=>5,'rating'=>3.4,'comment'=>'komentar']);
        $result->assertRedirectTo(base_url('UserController/reviews'));
        $result->assertSessionHas('alertErrorText',lang('App.tankYouReview'));
        $zahtev = ZahtevModel::findById(5);
        $this->assertEquals(5,$zahtev->stanje);
        $this->assertEquals(3,$zahtev->ocena);
        $this->assertEquals('komentar',$zahtev->recenzija);
        self::resetDB();
    }

    public function testOPpostReview14()
    {
        $_SESSION['user']=KorisnikModel::findById(14);
        $result = $this->withSession()->post('/UserController/OPpostReview',['id'=>5,'rating'=>3,'comment'=>'']);
        $result->assertRedirectTo(base_url('UserController/reviews'));
        $result->assertSessionHas('alertErrorText',lang('App.tankYouReview'));
        $zahtev = ZahtevModel::findById(5);
        $this->assertEquals(5,$zahtev->stanje);
        $this->assertEquals(3,$zahtev->ocena);
        $this->assertNull($zahtev->recenzija);
        self::resetDB();
    }

    public function testOPremoveReview1()
    {
        $_SESSION['user']=KorisnikModel::findById(14);
        $result = $this->withSession()->get('/UserController/OPremoveReview',['id'=>5]);
        $result->assertRedirectTo(base_url('UserController/reviews'));
        $zahtev = ZahtevModel::findById(5);
        $this->assertEquals(5,$zahtev->stanje);
        $this->assertNull($zahtev->ocena);
        $this->assertNull($zahtev->recenzija);
        self::resetDB();
    }

    public function testOPremoveReview2()
    {
        $_SESSION['user']=KorisnikModel::findById(14);
        $result = $this->withSession()->get('/UserController/OPremoveReview');
        $result->assertRedirectTo(base_url('UserController/reviews'));
        $zahtev = ZahtevModel::findById(5);
        $this->assertEquals(4,$zahtev->stanje);
        $this->assertNull($zahtev->ocena);
        $this->assertNull($zahtev->recenzija);
        self::resetDB();
    }

    public function testOPremoveReview3()
    {
        $_SESSION['user']=KorisnikModel::findById(14);
        $result = $this->withSession()->get('/UserController/OPremoveReview',['id'=>'aaa']);
        $result->assertRedirectTo(base_url('UserController/reviews'));
        $zahtev = ZahtevModel::findById(5);
        $this->assertEquals(4,$zahtev->stanje);
        $this->assertNull($zahtev->ocena);
        $this->assertNull($zahtev->recenzija);
        self::resetDB();
    }

    public function testOPremoveReview4()
    {
        $_SESSION['user']=KorisnikModel::findById(14);
        $result = $this->withSession()->get('/UserController/OPremoveReview',['id'=>99]);
        $result->assertRedirectTo(base_url('UserController/reviews'));
        $zahtev = ZahtevModel::findById(5);
        $this->assertEquals(4,$zahtev->stanje);
        $this->assertNull($zahtev->ocena);
        $this->assertNull($zahtev->recenzija);
        self::resetDB();
    }

    public function testOPremoveReview5()
    {
        $zm = new ZahtevModel();
        $zm->update(1,['stanje'=>4]);
        $_SESSION['user']=KorisnikModel::findById(14);
        $result = $this->withSession()->get('/UserController/OPremoveReview',['id'=>1]);
        $result->assertRedirectTo(base_url('UserController/reviews'));
        $zahtev = ZahtevModel::findById(1);
        $this->assertEquals(4,$zahtev->stanje);
        $this->assertNull($zahtev->ocena);
        $this->assertNull($zahtev->recenzija);
        self::resetDB();
    }

    public function testOPremoveReview6()
    {
        $_SESSION['user']=KorisnikModel::findById(14);
        $result = $this->withSession()->get('/UserController/OPremoveReview',['id'=>7]);
        $result->assertRedirectTo(base_url('UserController/reviews'));
        $zahtev = ZahtevModel::findById(7);
        $this->assertNotEquals(4,$zahtev->stanje);
        self::resetDB();
    }

    public function testRequests()
    {
        $result = $this->withSession()->get('/UserController/requests');
        $result->assertOK();
        $result->assertSee('Mika Mikić (mika@gmail.com) Vodoinstalater');
        $result->assertSee('Pera Perić (pera@gmail.com) Moler');
        $result->assertSee('Zoki Zokić (zoki@gmail.com) Električar');
        $result->assertSee('Trenutno nema zahteva.');
    }

    public function testOPCreateRequest1()
    {
        $result = $this->withSession()->post('/UserController/OPCreateRequest',['providerId'=>5,'requestDesc'=>'test opis','urgentBox'=>'on','startTime'=>1654934400,'duration'=>60]);
        $result->assertRedirectTo(base_url('/profile?id=5'));
        $result->assertSessionHas('alertErrorText',lang('App.successfulRequest'));
        $zahtev = ZahtevModel::findById(10);
        $this->assertEquals(1,$zahtev->idKorisnika);
        $this->assertEquals(5,$zahtev->idPruzaoca);
        $this->assertEquals(1,$zahtev->stanje);
        $this->assertEquals('test opis',$zahtev->opis);
        $this->assertEquals(true,$zahtev->hitno);
        self::resetDB();
    }

    public function testOPCreateRequest2()
    {
        $result = $this->withSession()->post('/UserController/OPCreateRequest',['requestDesc'=>'test opis','urgentBox'=>'on','startTime'=>1654934400,'duration'=>60]);
        $result->assertRedirectTo(site_url('/'));
        $result->assertSessionMissing('alertErrorText');
    }

    public function testOPCreateRequest3()
    {
        $result = $this->withSession()->post('/UserController/OPCreateRequest',['providerId'=>5,'urgentBox'=>'on','startTime'=>1654934400,'duration'=>60]);
        $result->assertRedirectTo(base_url('/profile?id=5'));
        $result->assertSessionHas('errorTextCreate',lang('App.errDesc'));
    }

    public function testOPCreateRequest4()
    {
        $result = $this->withSession()->post('/UserController/OPCreateRequest',['providerId'=>99,'requestDesc'=>'test opis','urgentBox'=>'on','startTime'=>1654934400,'duration'=>60]);
        $result->assertRedirectTo(base_url('/profile?id=99'));
        $result->assertSessionMissing('alertErrorText');
        $result->assertSessionMissing('errorTextCreate');
    }

    public function testOPCreateRequest5()
    {
        $result = $this->withSession()->post('/UserController/OPCreateRequest',['providerId'=>1,'requestDesc'=>'test opis','urgentBox'=>'on','startTime'=>1654934400,'duration'=>60]);
        $result->assertRedirectTo(base_url('/profile?id=1'));
        $result->assertSessionMissing('alertErrorText');
        $result->assertSessionMissing('errorTextCreate');
    }

    public function testOPCreateRequest6()
    {
        $_SESSION['user']=KorisnikModel::findById(5);
        $result = $this->withSession()->post('/UserController/OPCreateRequest',['providerId'=>5,'requestDesc'=>'test opis','urgentBox'=>'on','startTime'=>1654934400,'duration'=>60]);
        $result->assertRedirectTo(base_url('/profile?id=5'));
        $result->assertSessionHas('errorTextCreate',lang('App.errSelfRequest'));
    }

    public function testOPCreateRequest7()
    {
        $result = $this->withSession()->post('/UserController/OPCreateRequest',['providerId'=>5,'requestDesc'=>'test opis','startTime'=>1654934400,'duration'=>60]);
        $result->assertRedirectTo(base_url('/profile?id=5'));
        $result->assertSessionHas('alertErrorText',lang('App.successfulRequest'));
        $zahtev = ZahtevModel::findById(10);
        $this->assertEquals(1,$zahtev->idKorisnika);
        $this->assertEquals(5,$zahtev->idPruzaoca);
        $this->assertEquals(1,$zahtev->stanje);
        $this->assertEquals('test opis',$zahtev->opis);
        $this->assertEquals(0,$zahtev->hitno);
        self::resetDB();
    }

    public function testOPCreateRequest8()
    {
        $result = $this->withSession()->post('/UserController/OPCreateRequest',['providerId'=>5,'requestDesc'=>'test opis','urgentBox'=>'on','duration'=>60]);
        $result->assertRedirectTo(base_url('/profile?id=5'));
        $result->assertSessionMissing('alertErrorText');
        $result->assertSessionMissing('errorTextCreate');
    }

    public function testOPCreateRequest9()
    {
        $result = $this->withSession()->post('/UserController/OPCreateRequest',['providerId'=>5,'requestDesc'=>'test opis','urgentBox'=>'on','startTime'=>1654934400]);
        $result->assertRedirectTo(base_url('/profile?id=5'));
        $result->assertSessionMissing('alertErrorText');
        $result->assertSessionMissing('errorTextCreate');
    }

    public function testOPCreateRequest10()
    {
        $result = $this->withSession()->post('/UserController/OPCreateRequest',['providerId'=>5,'requestDesc'=>'test opis','urgentBox'=>'on','startTime'=>1654934400,'duration'=>0]);
        $result->assertRedirectTo(base_url('/profile?id=5'));
        $result->assertSessionMissing('alertErrorText');
        $result->assertSessionMissing('errorTextCreate');
    }

    public function testOPCreateRequest11()
    {
        $result = $this->withSession()->post('/UserController/OPCreateRequest',['providerId'=>5,'requestDesc'=>'test opis','urgentBox'=>'on','startTime'=>1654934400,'duration'=>1]);
        $result->assertRedirectTo(base_url('/profile?id=5'));
        $result->assertSessionMissing('alertErrorText');
        $result->assertSessionMissing('errorTextCreate');
    }

    public function testOPCreateRequest12()
    {
        $result = $this->withSession()->post('/UserController/OPCreateRequest',['providerId'=>5,'requestDesc'=>'test opis','urgentBox'=>'on','startTime'=>1654934400,'duration'=>61]);
        $result->assertRedirectTo(base_url('/profile?id=5'));
        $result->assertSessionMissing('alertErrorText');
        $result->assertSessionMissing('errorTextCreate');
    }

    public function testOPCreateRequest13()
    {
        $result = $this->withSession()->post('/UserController/OPCreateRequest',['providerId'=>2,'requestDesc'=>'test opis','urgentBox'=>'on','startTime'=>1654160300,'duration'=>61]);
        $result->assertRedirectTo(base_url('/profile?id=2'));
        $result->assertSessionMissing('alertErrorText');
        $result->assertSessionMissing('errorTextCreate');
    }

    public function testOPAcceptRequest1()
    {
        $result = $this->withSession()->get('/UserController/OPAcceptRequest',['id'=>2]);
        $result->assertRedirectTo(base_url('UserController/requests'));
        $zahtev = ZahtevModel::findById(2);
        $this->assertEquals(3,$zahtev->stanje);
        self::resetDB();
    }

    public function testOPAcceptRequest2()
    {
        $result = $this->withSession()->get('/UserController/OPAcceptRequest');
        $result->assertRedirectTo(base_url('UserController/requests'));
        $zahtev = ZahtevModel::findById(2);
        $this->assertNotEquals(3,$zahtev->stanje);
        self::resetDB();
    }

    public function testOPAcceptRequest3()
    {
        $result = $this->withSession()->get('/UserController/OPAcceptRequest',['id'=>99]);
        $result->assertRedirectTo(base_url('UserController/requests'));
        $zahtev = ZahtevModel::findById(2);
        $this->assertNotEquals(3,$zahtev->stanje);
        self::resetDB();
    }

    public function testOPAcceptRequest4()
    {
        $result = $this->withSession()->get('/UserController/OPAcceptRequest',['id'=>'asdasd']);
        $result->assertRedirectTo(base_url('UserController/requests'));
        $zahtev = ZahtevModel::findById(2);
        $this->assertNotEquals(3,$zahtev->stanje);
        self::resetDB();
    }

    public function testOPAcceptRequest5()
    {
        $result = $this->withSession()->get('/UserController/OPAcceptRequest',['id'=>1]);
        $result->assertRedirectTo(base_url('UserController/requests'));
        $zahtev = ZahtevModel::findById(1);
        $this->assertNotEquals(3,$zahtev->stanje);
        self::resetDB();
    }

    public function testOPAcceptRequest6()
    {
        $zm = new ZahtevModel();
        $zm->update(7,['stanje'=>2]);
        $result = $this->withSession()->get('/UserController/OPAcceptRequest',['id'=>7]);
        $result->assertRedirectTo(base_url('UserController/requests'));
        $zahtev = ZahtevModel::findById(7);
        $this->assertNotEquals(3,$zahtev->stanje);
        self::resetDB();
    }

    public function testOPRejectRequest1()
    {
        $result = $this->withSession()->get('/UserController/OPRejectRequest',['id'=>2]);
        $result->assertRedirectTo(base_url('UserController/requests'));
        $zahtev = ZahtevModel::findById(2);
        $this->assertEquals(7,$zahtev->stanje);
        self::resetDB();
    }

    public function testOPRejectRequest2()
    {
        $result = $this->withSession()->get('/UserController/OPRejectRequest');
        $result->assertRedirectTo(base_url('UserController/requests'));
        $zahtev = ZahtevModel::findById(2);
        $this->assertNotEquals(7,$zahtev->stanje);
        self::resetDB();
    }

    public function testOPRejectRequest3()
    {
        $result = $this->withSession()->get('/UserController/OPRejectRequest',['id'=>99]);
        $result->assertRedirectTo(base_url('UserController/requests'));
        $zahtev = ZahtevModel::findById(2);
        $this->assertNotEquals(7,$zahtev->stanje);
        self::resetDB();
    }

    public function testOPRejectRequest4()
    {
        $result = $this->withSession()->get('/UserController/OPRejectRequest',['id'=>'asdasd']);
        $result->assertRedirectTo(base_url('UserController/requests'));
        $zahtev = ZahtevModel::findById(2);
        $this->assertNotEquals(7,$zahtev->stanje);
        self::resetDB();
    }

    public function testOPRejectRequest5()
    {
        $result = $this->withSession()->get('/UserController/OPRejectRequest',['id'=>1]);
        $result->assertRedirectTo(base_url('UserController/requests'));
        $zahtev = ZahtevModel::findById(1);
        $this->assertNotEquals(7,$zahtev->stanje);
        self::resetDB();
    }

    public function testOPRejectRequest6()
    {
        $zm = new ZahtevModel();
        $zm->update(7,['stanje'=>2]);
        $result = $this->withSession()->get('/UserController/OPRejectRequest',['id'=>7]);
        $result->assertRedirectTo(base_url('UserController/requests'));
        $zahtev = ZahtevModel::findById(7);
        $this->assertNotEquals(7,$zahtev->stanje);
        self::resetDB();
    }

    public function testOPCheckRejection1()
    {
        $_SESSION['user']=KorisnikModel::findById(14);
        $result = $this->withSession()->get('/UserController/OPCheckRejection',['id'=>7]);
        $result->assertRedirectTo(base_url('UserController/requests'));
        $zahtev = ZahtevModel::findById(7);
        $this->assertEquals(8,$zahtev->stanje);
        self::resetDB();
    }

    public function testOPCheckRejection2()
    {
        $_SESSION['user']=KorisnikModel::findById(14);
        $result = $this->withSession()->get('/UserController/OPCheckRejection');
        $result->assertRedirectTo(base_url('UserController/requests'));
        $zahtev = ZahtevModel::findById(7);
        $this->assertNotEquals(8,$zahtev->stanje);
        self::resetDB();
    }

    public function testOPCheckRejection3()
    {
        $_SESSION['user']=KorisnikModel::findById(14);
        $result = $this->withSession()->get('/UserController/OPCheckRejection',['id'=>99]);
        $result->assertRedirectTo(base_url('UserController/requests'));
        $zahtev = ZahtevModel::findById(7);
        $this->assertNotEquals(8,$zahtev->stanje);
        self::resetDB();
    }

    public function testOPCheckRejection4()
    {
        $_SESSION['user']=KorisnikModel::findById(14);
        $result = $this->withSession()->get('/UserController/OPCheckRejection',['id'=>'asdasd']);
        $result->assertRedirectTo(base_url('UserController/requests'));
        $zahtev = ZahtevModel::findById(7);
        $this->assertNotEquals(8,$zahtev->stanje);
        self::resetDB();
    }

    public function testOPCheckRejection5()
    {
        $_SESSION['user']=KorisnikModel::findById(14);
        $result = $this->withSession()->get('/UserController/OPCheckRejection',['id'=>9]);
        $result->assertRedirectTo(base_url('UserController/requests'));
        $zahtev = ZahtevModel::findById(9);
        $this->assertNotEquals(8,$zahtev->stanje);
        self::resetDB();
    }

    public function testOPCheckRejection6()
    {
        $_SESSION['user']=KorisnikModel::findById(14);
        $zm = new ZahtevModel();
        $zm->update(1,['stanje'=>6]);
        $result = $this->withSession()->get('/UserController/OPCheckRejection',['id'=>1]);
        $result->assertRedirectTo(base_url('UserController/requests'));
        $zahtev = ZahtevModel::findById(1);
        $this->assertNotEquals(8,$zahtev->stanje);
        self::resetDB();
    }
}