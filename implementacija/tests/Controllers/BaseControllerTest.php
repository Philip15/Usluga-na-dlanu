<?php

use App\Controllers\BaseController;
use App\Models\KorisnikModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;

final class BaseControllerTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected function setUp():void
    {
        parent::setUp();
        //$this->resetDB();
    }

    protected function resetDB()
    {
        $db = \Config\Database::connect();
        $lines = file("D:\Faks\_6.Semestar\PSI\Usluga-na-dlanu\docs\Modelovanje Baze\usluga_na_dlanu_data.sql");
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

    public function testIndex()
    {
        $result = $this->get('/');

        $result->assertOK();
        $result->assertSee('Električar');
        $result->assertSee('Moler');
        $result->assertSee('Vodoinstalater');
        $result->assertSee('Bravar');
    }

    public function testAJAXGetProviders()
    {
        $result = $this->get('/AJAXGetProviders');
        $result->assertOK();
        $this->assertTrue($result->getJSON() !== false);
        $this->assertStringContainsString('"ime":"Mika"',$result->getJSON());
        $this->assertStringContainsString('"ime":"Pera"',$result->getJSON());
        $this->assertStringContainsString('"ime":"Zoki"',$result->getJSON());
        $this->assertStringContainsString('"ime":"Mi\u0161ko"',$result->getJSON());
        $this->assertStringContainsString('"ime":"Luka"',$result->getJSON());
        $this->assertStringContainsString('"ime":"\u017dika"',$result->getJSON());
        $this->assertStringContainsString('"ime":"Marko"',$result->getJSON());
        $this->assertStringContainsString('"ime":"Slavko"',$result->getJSON());
        $this->assertStringContainsString('"ime":"Mirko"',$result->getJSON());
        $this->assertStringContainsString('"ime":"Zaki"',$result->getJSON());
        $this->assertStringContainsString('"ime":"Ivan"',$result->getJSON());
        $this->assertStringContainsString('"ime":"Goran"',$result->getJSON());
    }

    public function testAJAXGetProvidersImage()
    {
        $img = file_get_contents("D:\Pictures\\test.jpg");
        $korisnikModel = new KorisnikModel();
        $korisnikModel->update(2, ['profilnaSlika' => $img]);
        $result = $this->get('/AJAXGetProviders');
        $result->assertOK();
        $this->assertTrue($result->getJSON() !== false);
        $this->assertStringContainsString('data:image\\/jpeg;base64,',$result->getJSON());
        $korisnikModel = new KorisnikModel();
        $korisnikModel->update(2, ['profilnaSlika' => null]);
    }

    public function testAJAXGetProvidersCat()
    {
        $result = $this->get('/AJAXGetProviders',['cat'=>1]);
        $result->assertOK();
        $this->assertTrue($result->getJSON() !== false);
        $this->assertStringContainsString('"ime":"Mika"',$result->getJSON());
        $this->assertStringContainsString('"ime":"\u017dika"',$result->getJSON());
        $this->assertStringContainsString('"ime":"Ivan"',$result->getJSON());
    }

    public function testAJAXGetProvidersTime()
    {
        $result = $this->get('/AJAXGetProviders',['tFrom'=>'11:10','tTo'=>'11:20','dFrom'=>'2022-06-02','dTo'=>'2022-06-02']);
        $result->assertOK();
        $this->assertTrue($result->getJSON() !== false);
        $this->assertStringNotContainsString('"ime":"Mika"',$result->getJSON());
    }

    public function testAJAXGetCalendarDataInvalid()
    {
        $result = $this->get('/AJAXGetCalendarData');
        $result->assertOK();
        $this->assertTrue($result->getJSON() !== false);
        $this->assertEquals('[]',$result->getJSON());

        $result = $this->get('/AJAXGetCalendarData',['id'=>2]);
        $result->assertOK();
        $this->assertTrue($result->getJSON() !== false);
        $this->assertEquals('[]',$result->getJSON());

        $result = $this->get('/AJAXGetCalendarData',['date'=>1653861600]);
        $result->assertOK();
        $this->assertTrue($result->getJSON() !== false);
        $this->assertEquals('[]',$result->getJSON());

        $result = $this->get('/AJAXGetCalendarData',['id'=>1,'date'=>1653861600]);
        $result->assertOK();
        $this->assertTrue($result->getJSON() !== false);
        $this->assertEquals('[]',$result->getJSON());

        $result = $this->get('/AJAXGetCalendarData',['id'=>100,'date'=>1653861600]);
        $result->assertOK();
        $this->assertTrue($result->getJSON() !== false);
        $this->assertEquals('[]',$result->getJSON());
    }

    public function testAJAXGetCalendarData()
    {
        $result = $this->get('/AJAXGetCalendarData',['id'=>2,'date'=>1653861600]);
        $result->assertOK();
        $this->assertTrue($result->getJSON() !== false);
        $this->assertEquals('[0,0,0,0.5833333333333334,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0]',$result->getJSON());

        $result = $this->get('/AJAXGetCalendarData',['id'=>2,'date'=>1654120800]);
        $result->assertOK();
        $this->assertTrue($result->getJSON() !== false);
        $this->assertEquals('[0.5833333333333334,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0]',$result->getJSON());

        $result = $this->get('/AJAXGetCalendarData',['id'=>2,'date'=>1654207200]);
        $result->assertOK();
        $this->assertTrue($result->getJSON() !== false);
        $this->assertEquals('[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0]',$result->getJSON());
    }

    public function testAJAXGetDayDataInvalid()
    {
        $result = $this->get('/AJAXGetDayData');
        $result->assertOK();
        $this->assertTrue($result->getJSON() !== false);
        $this->assertEquals('[]',$result->getJSON());

        $result = $this->get('/AJAXGetDayData',['id'=>2]);
        $result->assertOK();
        $this->assertTrue($result->getJSON() !== false);
        $this->assertEquals('[]',$result->getJSON());

        $result = $this->get('/AJAXGetDayData',['date'=>1653861600]);
        $result->assertOK();
        $this->assertTrue($result->getJSON() !== false);
        $this->assertEquals('[]',$result->getJSON());

        $result = $this->get('/AJAXGetDayData',['id'=>1,'date'=>1653861600]);
        $result->assertOK();
        $this->assertTrue($result->getJSON() !== false);
        $this->assertEquals('[]',$result->getJSON());

        $result = $this->get('/AJAXGetDayData',['id'=>100,'date'=>1653861600]);
        $result->assertOK();
        $this->assertTrue($result->getJSON() !== false);
        $this->assertEquals('[]',$result->getJSON());
    }

    public function testAJAXGetDayData()
    {
        $result = $this->get('/AJAXGetDayData',['id'=>2,'date'=>1654639200]);
        $result->assertOK();
        $this->assertTrue($result->getJSON() !== false);
        $this->assertEquals('[null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null]',$result->getJSON());

        $result = $this->get('/AJAXGetDayData',['id'=>2,'date'=>1654120800]);
        $result->assertOK();
        $this->assertTrue($result->getJSON() !== false);
        $this->assertEquals('[null,null,null,null,null,null,0,0,0,0,null,null,0,0,0,0,0,0,0,0,0,0,null,null]',$result->getJSON());

        $result = $this->withSession(['user'=>KorisnikModel::findById('1')])->get('/AJAXGetDayData',['id'=>2,'date'=>1654120800]);
        $result->assertOK();
        $this->assertTrue($result->getJSON() !== false);
        $this->assertEquals('[null,null,null,null,null,null,0,0,0,0,null,null,0,0,0,0,0,0,0,0,0,0,null,null]',$result->getJSON());

        $result = $this->withSession(['user'=>KorisnikModel::findById('2')])->get('/AJAXGetDayData',['id'=>2,'date'=>1654120800,'anon'=>1]);
        $result->assertOK();
        $this->assertTrue($result->getJSON() !== false);
        $this->assertEquals('[null,null,null,null,null,null,0,0,0,0,null,null,0,0,0,0,0,0,0,0,0,0,null,null]',$result->getJSON());

        $result = $this->withSession(['user'=>KorisnikModel::findById('2')])->get('/AJAXGetDayData',['id'=>2,'date'=>1654120800]);
        $result->assertOK();
        $this->assertTrue($result->getJSON() !== false);
        $this->assertEquals('[null,null,null,null,null,null,"1%Lazar Premovi\u0107 - Zahtev1",0,0,0,null,null,"5%Ru\u010dno rezervisan termin",0,0,0,0,0,0,0,0,0,null,null]',$result->getJSON());
    }

    public function testSafeRedirectBack()
    {
        $_SESSION['_ci_previous_url']=base_url('/ProviderController/timetable');
        $bc = new BaseController();
        $res = $bc->safeRedirectBack();

        $this->assertEquals(redirect()->back(),$res);

        $_SESSION['_ci_previous_url']=base_url('/AJAXGetDayData');
        $bc = new BaseController();
        $res = $bc->safeRedirectBack();
        $this->assertEquals(redirect()->to(base_url('/')),$res);

        $_SESSION['_ci_previous_url']=base_url('/UserController/OPlogout');
        $bc = new BaseController();
        $res = $bc->safeRedirectBack();
        $this->assertEquals(redirect()->to(base_url('/')),$res);
    }

    public function testProfile1()
    {
        $this->expectException(PageNotFoundException::class);
        $result = $this->get('/profile',['id'=>1]);
    }

    public function testProfile2()
    {
        $this->expectException(PageNotFoundException::class);
        $result = $this->get('/profile',['id'=>100]);
    }

    public function testProfile3()
    {
        $this->expectException(PageNotFoundException::class);
        $result = $this->get('/profile');
    }

    public function testProfile4()
    {
        $result = $this->withSession([])->get('/profile',['id'=>12]);
        $result->assertOK();
        $result->assertSee('null');
        $result->assertSee('A je l ste možda čuli za EESTEC?');
        $result->assertSee('Prijavite se da biste kontaktirali pružaoca');
        $result->assertSee('komentar');


        $result = $this->withSession(['user'=>KorisnikModel::findById('1')])->get('/profile',['id'=>12]);
        $result->assertOK();
        $result->assertSee('newRequest');
        $result->assertSee('A je l ste možda čuli za EESTEC?');
        $result->assertSee('ivan@gmail.com');
        $result->assertSee('Ulica 10');
        $result->assertSee('komentar');
    }
}
