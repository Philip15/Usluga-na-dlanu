<?php

use App\Models\KorisnikModel;
use App\Models\TerminModel;
use App\Models\ZahtevModel;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;
use Config\Services;

final class ProviderControllerTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected function setUp():void
    {
        parent::setUp();
        // $this->resetDB();
        unset($_SESSION);
        $_SESSION['user'] = KorisnikModel::findById(2);
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
    
    public function testRequests()
    {
        $result = $this->withSession()->get('/ProviderController/requests');
        $result->assertOK();
        $result->assertSee('Lazar Premović (pl190091d@student.etf.bg.ac.rs)');
        $result->assertSee('Trenutno nema zahteva.');
        $result->assertSee('Trenutno nema zahteva.');
        $result->assertSee('Trenutno nema zahteva.');
    }

    public function testOPCreateOffer1() 
    {
        $_SESSION['user'] = KorisnikModel::findById(2);
        $result = $this->withSession()->post('ProviderController/OPCreateOffer', ['idZ' => 1, 'priceVal' => 100, 'komentar' => 'opis']);
        $result->assertRedirectTo(base_url('ProviderController/requests'));
        $zahtev = ZahtevModel::findById(1);
        $this->assertEquals(2, $zahtev->stanje);
        self::resetDB();
    }

    public function testOPCreateOffer2() 
    {
        $_SESSION['user'] = KorisnikModel::findById(2);
        $result = $this->withSession()->post('ProviderController/OPCreateOffer', ['idZ' => 2, 'priceVal' => 100, 'komentar' => 'opis']);
        $result->assertRedirectTo(base_url('ProviderController/requests'));
        $zahtev = ZahtevModel::findById(2);
        $this->assertEquals(2, $zahtev->stanje);
        self::resetDB();
    }

    public function testOPCreateOffer3() 
    {
        $_SESSION['user'] = KorisnikModel::findById(2);
        $result = $this->withSession()->post('ProviderController/OPCreateOffer', ['idZ' => 1, 'priceVal' => 100]);
        $result->assertRedirectTo(base_url('ProviderController/requests'));
        $zahtev = ZahtevModel::findById(1);
        $this->assertEquals(2, $zahtev->stanje);
        self::resetDB();
    }

    public function testOPCreateOffer4() 
    {
        $_SESSION['user'] = KorisnikModel::findById(2);
        $result = $this->withSession()->post('ProviderController/OPCreateOffer', ['idZ' => 1]);
        $result->assertRedirectTo(base_url('ProviderController/requests'));
        $result->assertSessionHas('errorTextPrice', lang('App.errMsgPrice'));
        $zahtev = ZahtevModel::findById(1);
        $this->assertNotEquals(2, $zahtev->stanje);
        self::resetDB();
    }

    public function testOPCreateOffer5() 
    {
        $_SESSION['user'] = KorisnikModel::findById(2);
        $result = $this->withSession()->post('ProviderController/OPCreateOffer', ['idZ' => 1, 'priceVal' => 'asas']);
        $result->assertRedirectTo(base_url('ProviderController/requests'));
        $result->assertSessionHas('errorTextPrice', lang('App.errMsgPrice'));
        $zahtev = ZahtevModel::findById(1);
        $this->assertNotEquals(2, $zahtev->stanje);
        self::resetDB();
    }

    public function testOPCreateOffer6() 
    {
        $_SESSION['user'] = KorisnikModel::findById(2);
        $result = $this->withSession()->post('ProviderController/OPCreateOffer', ['idZ' => 101, 'priceVal' => '100']);
        $result->assertRedirectTo(base_url('ProviderController/requests'));
        $zahtev = ZahtevModel::findById(101);
        $this->assertNull($zahtev);
        self::resetDB();
    }

    public function testOPCreateOffer7() 
    {
        $_SESSION['user'] = KorisnikModel::findById(14);
        $result = $this->withSession()->post('ProviderController/OPCreateOffer', ['idZ' => 1, 'priceVal' => '100']);
        $result->assertRedirect();
        $zahtev = ZahtevModel::findById(1);
        $this->assertNotEquals(2, $zahtev->stanje);
        self::resetDB();
    }

    public function testRejectOffer1() 
    {
        $_SESSION['user'] = KorisnikModel::findById(2);
        $result = $this->withSession()->get('ProviderController/OPRejectRequest', ['id' => 1]);
        $result->assertRedirectTo(base_url('ProviderController/requests'));
        $zahtev = ZahtevModel::findById(1);
        $this->assertEquals(6, $zahtev->stanje);
        $terminM = new TerminModel();
        $termin = $terminM->where('idZahteva', 1)->findAll();
        $this->assertEmpty($termin);
        self::resetDB();
    }

    public function testRejectOffer2() 
    {
        $_SESSION['user'] = KorisnikModel::findById(4);
        $result = $this->withSession()->get('ProviderController/OPRejectRequest', ['id' => 3]);
        $result->assertRedirectTo(base_url('ProviderController/requests'));
        $zahtev = ZahtevModel::findById(3);
        $this->assertEquals(6, $zahtev->stanje);
        $terminM = new TerminModel();
        $termin = $terminM->where('idZahteva', 3)->findAll();
        $this->assertEmpty($termin);
        self::resetDB();
    }

    public function testRejectOffer3() 
    {
        $_SESSION['user'] = KorisnikModel::findById(3);
        $result = $this->withSession()->get('ProviderController/OPRejectRequest', ['id' => 2]);
        $result->assertRedirectTo(base_url('ProviderController/requests'));
        $zahtev = ZahtevModel::findById(2);
        $this->assertNotEquals(6, $zahtev->stanje);
        $terminM = new TerminModel();
        $termin = $terminM->where('idZahteva', 3)->findAll();
        $this->assertNotEmpty($termin);
        self::resetDB();
    }

    public function testRejectOffer4() 
    {
        $_SESSION['user'] = KorisnikModel::findById(3);
        $result = $this->withSession()->get('ProviderController/OPRejectRequest', ['id' => 102]);
        $result->assertRedirectTo(base_url('ProviderController/requests'));
        $zahtev = ZahtevModel::findById(102);
        $this->assertNull($zahtev);
        $terminM = new TerminModel();
        $termin = $terminM->where('idZahteva', 3)->findAll();
        $this->assertNotEmpty($termin);
        self::resetDB();
    }

    public function testRejectOffer5() 
    {
        $_SESSION['user'] = KorisnikModel::findById(14);
        $result = $this->withSession()->get('ProviderController/OPRejectRequest', ['id' => 4]);
        $result->assertRedirect();
        $zahtev = ZahtevModel::findById(4);
        $this->assertNotEquals(6, $zahtev->stanje);
        $terminM = new TerminModel();
        $termin = $terminM->where('idZahteva', 4)->findAll();
        $this->assertEmpty($termin);
        self::resetDB();
    }

    public function testRealizeRequest1() 
    {
        $_SESSION['user'] = KorisnikModel::findById(4);
        $result = $this->withSession()->get('ProviderController/OPRealizeRequest', ['id' => 3]);
        $result->assertRedirectTo(base_url('ProviderController/requests'));
        $zahtev = ZahtevModel::findById(3);
        $this->assertEquals(4, $zahtev->stanje);
        self::resetDB();
    }

    public function testRealizeRequest2() 
    {
        $_SESSION['user'] = KorisnikModel::findById(4);
        $result = $this->withSession()->get('ProviderController/OPRealizeRequest', ['id' => 100]);
        $result->assertRedirectTo(base_url('ProviderController/requests'));
        $zahtev = ZahtevModel::findById(100);
        $this->assertNull($zahtev);
        self::resetDB();
    }

    public function testRealizeRequest3() 
    {
        $_SESSION['user'] = KorisnikModel::findById(4);
        $result = $this->withSession()->get('ProviderController/OPRealizeRequest', ['id' => 4]);
        $result->assertRedirectTo(base_url('ProviderController/requests'));
        $zahtev = ZahtevModel::findById(4);
        $this->assertNotEquals(4, $zahtev->stanje);
        self::resetDB();
    }

    public function testRealizeRequest4() 
    {
        $_SESSION['user'] = KorisnikModel::findById(14);
        $result = $this->withSession()->get('ProviderController/OPRealizeRequest', ['id' => 3]);
        $result->assertRedirect();
        $zahtev = ZahtevModel::findById(3);
        $this->assertNotEquals(4, $zahtev->stanje);
        self::resetDB();
    }

    public function testCheckRejection1() 
    {
        $_SESSION['user'] = KorisnikModel::findById(4);
        $result = $this->withSession()->get('ProviderController/OPCheckRejection', ['id' => 4]);
        $result->assertRedirectTo(base_url('ProviderController/requests'));
        $zahtev = ZahtevModel::findById(4);
        $this->assertEquals(8, $zahtev->stanje);
        self::resetDB();
    }

    public function testCheckRejection2() 
    {
        $_SESSION['user'] = KorisnikModel::findById(4);
        $result = $this->withSession()->get('ProviderController/OPCheckRejection', ['id' => 104]);
        $result->assertRedirectTo(base_url('ProviderController/requests'));
        $zahtev = ZahtevModel::findById(104);
        $this->assertNull($zahtev);
        self::resetDB();
    }

    public function testCheckRejection3() 
    {
        $_SESSION['user'] = KorisnikModel::findById(4);
        $result = $this->withSession()->get('ProviderController/OPCheckRejection', ['id' => 3]);
        $result->assertRedirectTo(base_url('ProviderController/requests'));
        $zahtev = ZahtevModel::findById(3);
        $this->assertNotEquals(8, $zahtev->stanje);
        self::resetDB();
    }

    public function testCheckRejection4() 
    {
        $_SESSION['user'] = KorisnikModel::findById(14);
        $result = $this->withSession()->get('ProviderController/OPCheckRejection', ['id' => 3]);
        $result->assertRedirect();
        $zahtev = ZahtevModel::findById(3);
        $this->assertNotEquals(8, $zahtev->stanje);
        self::resetDB();
    }

    public function testTimetable()
    {
        $result = $this->withSession()->get('/ProviderController/timetable');
        $result->assertOK();
    }

    public function testAJAXGetSlotInfo1()
    {
        $_SESSION['user'] = KorisnikModel::findById(4);
        $result = $this->withSession()->get('ProviderController/AJAXGetSlotInfo');
        $result->assertStatus(400);
    }

    public function testAJAXGetSlotInfo2()
    {
        $_SESSION['user'] = KorisnikModel::findById(4);
        $result = $this->withSession()->get('ProviderController/AJAXGetSlotInfo', ['slot' => 50]);
        $result->assertStatus(400);
    }

    public function testAJAXGetSlotInfo3()
    {
        $_SESSION['user'] = KorisnikModel::findById(2);
        $result = $this->withSession()->get('ProviderController/AJAXGetSlotInfo', ['slot' => 5]);
        $result->assertSee(lang('App.manuallyReservedSlot'));
        $result->assertSee(lang('App.removeSlot'));
        $result->assertSee(lang('App.date'));
        $result->assertSee(lang('App.time'));
    }

    public function testAJAXGetSlotInfo4()
    {
        $_SESSION['user'] = KorisnikModel::findById(4);
        $result = $this->withSession()->get('ProviderController/AJAXGetSlotInfo', ['slot' => 3]);
        $result->assertSee(lang('App.client'));
        $result->assertSee(lang('App.desc'));
        $result->assertSee(lang('App.state'));
        $result->assertSee(lang('App.urgent'));
        $result->assertSee(lang('App.price'));
        $result->assertSee(lang('App.comment'));
        $result->assertSee(lang('App.seeMore'));
        $result->assertSee(lang('App.date'));
        $result->assertSee(lang('App.time'));
    }

    public function testReserveTime1() 
    {
        $_SESSION['user'] = KorisnikModel::findById(4);
        $result = $this->withSession()->get('ProviderController/OPReserveTime', ['duration' => 120, 'startTime' => 1654794000]);
        $result->assertOK();
        $terminM = new TerminModel();
        $data = ['trajanje' => 120, 'datumVremePocetka' => 1654794000, 'idPruzaoca' => 4];
        $termin = $terminM->where($data)->find();
        $this->assertNotNull($termin);
        $result->assertRedirectTo(base_url('ProviderController/timetable'));
        self::resetDB();
    }

    public function testReserveTime2() 
    {
        $_SESSION['user'] = KorisnikModel::findById(4);
        $result = $this->withSession()->get('ProviderController/OPReserveTime', ['duration' => 130, 'startTime' => 1654794000]);
        $result->assertOK();
        $terminM = new TerminModel();
        $data = ['trajanje' => 130, 'datumVremePocetka' => 1654794000, 'idPruzaoca' => 4];
        $termin = $terminM->where($data)->findAll();
        $this->assertEmpty($termin);
        $result->assertRedirectTo(base_url('ProviderController/timetable'));
        self::resetDB();
    }

    public function testReserveTime3() 
    {
        $_SESSION['user'] = KorisnikModel::findById(4);
        $result = $this->withSession()->get('ProviderController/OPReserveTime', ['duration' => 25, 'startTime' => 1654794000]);
        $result->assertOK();
        $terminM = new TerminModel();
        $data = ['trajanje' => 25, 'datumVremePocetka' => 1654794000, 'idPruzaoca' => 4];
        $termin = $terminM->where($data)->findAll();
        $this->assertEmpty($termin);
        $result->assertRedirectTo(base_url('ProviderController/timetable'));
        self::resetDB();
    }

    public function testReserveTime4() 
    {
        $_SESSION['user'] = KorisnikModel::findById(4);
        $terminM = new TerminModel();
        $sviTermini = $terminM->orderBy('idTermina','ASC')->findAll();
        $result = $this->withSession()->get('ProviderController/OPReserveTime', ['duration' => 120]);
        $result->assertOK();
        $terminM = new TerminModel();
        $data = ['trajanje' => 120, 'idPruzaoca' => 4];
        $termin = $terminM->where($data)->orderBy('idTermina', 'ASC')->findAll();
        if ($termin != null) $termin = $termin[count($termin)-1];
        if ($termin != null) $this->assertNotEquals($sviTermini[count($sviTermini)-1]->idTermina, $termin->idTermina);
        $result->assertRedirectTo(base_url('ProviderController/timetable'));
        self::resetDB();
    }

    public function testReserveTime5() 
    {
        $_SESSION['user'] = KorisnikModel::findById(14);
        $result = $this->withSession()->get('ProviderController/OPReserveTime', ['duration' => 120, 'startTime' => 1654794000]);
        $result->assertOK();
        $terminM = new TerminModel();
        $data = ['trajanje' => 120, 'datumVremePocetka' => 1654794000, 'idPruzaoca' => 4];
        $termin = $terminM->where($data)->findAll();
        $this->assertEmpty($termin);
        $result->assertRedirect();
        self::resetDB();
    }

    public function testFreeTime1()
    {
        $_SESSION['user'] = KorisnikModel::findById(2);
        $result = $this->withSession()->get('ProviderController/OPFreeTime');
        $result->assertStatus(400);
    }

    public function testFreeTime2()
    {
        $_SESSION['user'] = KorisnikModel::findById(2);
        $result = $this->withSession()->get('ProviderController/OPFreeTime', ['id' => 100]);
        $result->assertStatus(400);
    }

    public function testFreeTime3()
    {
        $_SESSION['user'] = KorisnikModel::findById(2);
        $result = $this->withSession()->get('ProviderController/OPFreeTime', ['id' => 1]);
        $result->assertStatus(400);
    }

    public function testFreeTime4()
    {
        $_SESSION['user'] = KorisnikModel::findById(2);
        $result = $this->withSession()->get('ProviderController/OPFreeTime', ['id' => 5]);
        $result->assertOK();
        $terminM = new TerminModel();
        $termin = $terminM->find(5);
        $this->assertNull($termin);
        $result->assertRedirectTo(base_url('ProviderController/timetable'));
    }

    public function testFreeTime5() 
    {
        $_SESSION['user'] = KorisnikModel::findById(5);
        $result = $this->withSession()->get('ProviderController/OPFreeTime', ['id' => 1]);
        $result->assertStatus(400);
    }
}

?>