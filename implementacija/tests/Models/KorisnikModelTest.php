<?php

use App\Models\KategorijaModel;
use App\Models\KorisnikModel;
use App\Models\TerminModel;
use App\Models\ZahtevModel;
use PHPUnit\Framework\TestCase;

final class KorisnikModelTest extends TestCase
{
    protected function setUp():void
    {
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

    public function testLinkNoKategorija()
    {
        $model = new KorisnikModel();
        $korisnik = $model->find(1);
        $korisnik->linkKategorija();
        $this->assertNull($korisnik->kategorija);
    }

    public function testLinkKategorija()
    {
        $model = new KorisnikModel();
        $korisnik = $model->find(2);
        $korisnik->linkKategorija();
        $this->assertEquals(KategorijaModel::findById(1),$korisnik->kategorija);
    }

    public function testLinkNoUpuceniZahtevi()
    {
        $model = new KorisnikModel();
        $korisnik = $model->find(2);
        $korisnik->linkUpuceniZahtevi();
        $this->assertEmpty($korisnik->upuceniZahtevi);
    }

    public function testLinkUpuceniZahtevi()
    {
        $model = new KorisnikModel();
        $korisnik = $model->find(1);
        $korisnik->linkUpuceniZahtevi();
        $zahtevM = new ZahtevModel();
        $zahtevi = $zahtevM->where('idKorisnika',1)->findAll();
        $this->assertEquals($zahtevi,$korisnik->upuceniZahtevi);
    }

    public function testLinkNoPrimljeniZahtevi()
    {
        $model = new KorisnikModel();
        $korisnik = $model->find(8);
        $korisnik->linkPrimljeniZahtevi();
        $this->assertEmpty($korisnik->primljeniZahtevi);
    }

    public function testLinkPrimljeniZahtevi()
    {
        $model = new KorisnikModel();
        $korisnik = $model->find(4);
        $korisnik->linkPrimljeniZahtevi();
        $zahtevM = new ZahtevModel();
        $zahtevi = $zahtevM->where('idPruzaoca',4)->findAll();
        $this->assertEquals($zahtevi,$korisnik->primljeniZahtevi);
    }

    public function testLinkNoManuelnoZauzetiTermini()
    {
        $model = new KorisnikModel();
        $korisnik = $model->find(5);
        $korisnik->LinkManuelnoZauzetiTermini();
        $this->assertEmpty($korisnik->manuelnoZauzetiTermini);
    }

    public function testLinkManuelnoZauzetiTermini()
    {
        $model = new KorisnikModel();
        $korisnik = $model->find(2);
        $korisnik->LinkManuelnoZauzetiTermini();
        $terminM = new TerminModel();
        $termini = $terminM->where('idPruzaoca',2)->findAll();
        $this->assertEquals($termini,$korisnik->manuelnoZauzetiTermini);
    }

    public function testRole()
    {
        $model = new KorisnikModel();
        $model->pruzalac=0;
        $model->administrator=0;
        $this->assertEquals('user',$model->role());
        $model->pruzalac=1;
        $model->administrator=0;
        $this->assertEquals('provider',$model->role());
        $model->pruzalac=0;
        $model->administrator=1;
        $this->assertEquals('admin',$model->role());
        $model->pruzalac=1;
        $model->administrator=1;
        $this->assertEquals('admin',$model->role());
        $model->pruzalac=2;
        $model->administrator=0;
        $this->assertEquals('user',$model->role());
        $model->pruzalac=2;
        $model->administrator=1;
        $this->assertEquals('admin',$model->role());
    }

    public function testRequestNotifications()
    {
        $model = new KorisnikModel();
        $korisnik=$model->find(1);
        $this->assertEquals(1,$korisnik->requestNotifications());
        $korisnik=$model->find(2);
        $this->assertEquals(0,$korisnik->requestNotifications());
    }

    public function testReviewNotifications()
    {
        $model = new KorisnikModel();
        $korisnik=$model->find(14);
        $this->assertEquals(1,$korisnik->reviewNotifications());
        $korisnik=$model->find(1);
        $this->assertEquals(0,$korisnik->reviewNotifications());
    }

    public function testRejectedNotifications()
    {
        $model = new KorisnikModel();
        $korisnik=$model->find(14);
        $this->assertEquals(1,$korisnik->rejectedNotifications());
        $korisnik=$model->find(1);
        $this->assertEquals(0,$korisnik->rejectedNotifications());
    }

    public function testIncomingNotifications()
    {
        $model = new KorisnikModel();
        $korisnik=$model->find(2);
        $this->assertEquals(1,$korisnik->incomingNotifications());
        $korisnik=$model->find(3);
        $this->assertEquals(0,$korisnik->incomingNotifications());
    }

    public function testAcceptedNotifications()
    {
        $model = new KorisnikModel();
        $korisnik=$model->find(4);
        $this->assertEquals(1,$korisnik->acceptedNotifications());
        $korisnik=$model->find(2);
        $this->assertEquals(0,$korisnik->acceptedNotifications());
    }

    public function testRejectedProviderNotifications()
    {
        $model = new KorisnikModel();
        $korisnik=$model->find(4);
        $this->assertEquals(1,$korisnik->rejectedProviderNotifications());
        $korisnik=$model->find(3);
        $this->assertEquals(0,$korisnik->rejectedProviderNotifications());
    }

    public function testAccountNotifications()
    {
        $model = new KorisnikModel();
        $korisnik=$model->find(1);
        $this->assertEquals(0,$korisnik->accountNotifications());

        $model->update(14,['pruzalac'=>2]);
        
        $korisnik=$model->find(1);
        $this->assertEquals(1,$korisnik->accountNotifications());

        $model->update(14,['pruzalac'=>1]);

        $korisnik=$model->find(1);
        $this->assertEquals(0,$korisnik->accountNotifications());

        $model->update(14,['pruzalac'=>0]);

        $korisnik=$model->find(1);
        $this->assertEquals(0,$korisnik->accountNotifications());
    }

    public function testHasNotifications()
    {
        $model = new KorisnikModel();
        $korisnik=$model->find(1);
        $this->assertEquals(true,$korisnik->hasNotifications());
        $korisnik=$model->find(2);
        $this->assertEquals(true,$korisnik->hasNotifications());
        $korisnik=$model->find(14);
        $this->assertEquals(true,$korisnik->hasNotifications());
        $korisnik=$model->find(10);
        $this->assertEquals(false,$korisnik->hasNotifications());
    }

    public function testRating()
    {
        $model = new KorisnikModel();
        $korisnik=$model->find(1);
        $this->assertEquals(0,$korisnik->rating());
        $korisnik=$model->find(12);
        $this->assertEquals(4,$korisnik->rating());
        
        $zahtevM= new ZahtevModel();
        $zahtevM->insert(['idZahteva'=>10,'idKorisnika'=>1,'idPruzaoca'=>2,'stanje'=>5,'opis'=>'tst','hitno'=>0,'ocena'=>5]);
        $id = $zahtevM->getInsertID();
        $zahtevM->insert(['idZahteva'=>11,'idKorisnika'=>1,'idPruzaoca'=>2,'stanje'=>5,'opis'=>'tst','hitno'=>0,'ocena'=>4]);
        $zahtevM->insert(['idZahteva'=>12,'idKorisnika'=>1,'idPruzaoca'=>2,'stanje'=>5,'opis'=>'tst','hitno'=>0,'ocena'=>2]);
        $zahtevM->insert(['idZahteva'=>13,'idKorisnika'=>1,'idPruzaoca'=>2,'stanje'=>5,'opis'=>'tst','hitno'=>0,'ocena'=>1]);
        $zahtevM->insert(['idZahteva'=>14,'idKorisnika'=>1,'idPruzaoca'=>2,'stanje'=>5,'opis'=>'tst','hitno'=>0,'ocena'=>5]);

        $korisnik=$model->find(2);
        $this->assertEquals(3.4,$korisnik->hasNotifications());

        $zahtevM->delete($id);
        $zahtevM->delete($id+1);
        $zahtevM->delete($id+2);
        $zahtevM->delete($id+3);
        $zahtevM->delete($id+4);
    }

    public function testAvailable()
    {
        $model = new KorisnikModel();
        $korisnik=$model->find(2);
        $this->assertEquals(false,$korisnik->available('11:00','11:10','2022-06-02','2022-06-02'));
        $this->assertEquals(false,$korisnik->available('11:10','11:20','2022-06-02','2022-06-02'));
        $this->assertEquals(false,$korisnik->available('12:30','13:00','2022-06-02','2022-06-02'));
        $this->assertEquals(false,$korisnik->available('12:30','13:00','2022-06-02','2022-06-02'));

        $this->assertEquals(true,$korisnik->available('12:30','15:00','2022-06-02','2022-06-02'));
        $this->assertEquals(true,$korisnik->available('11:10','11:20','2022-06-01','2022-06-01'));
        $this->assertEquals(true,$korisnik->available('11:10','11:20','2022-06-01','2022-06-03'));
        $this->assertEquals(true,$korisnik->available('11:10','11:20','2022-06-03','2022-06-03'));
        
        $this->assertEquals(false,$korisnik->available('','11:20','2022-06-03','2022-06-03'));
        $this->assertEquals(false,$korisnik->available('11:10','','2022-06-03','2022-06-03'));
        $this->assertEquals(false,$korisnik->available('11:10','11:20','','2022-06-03'));
        $this->assertEquals(false,$korisnik->available('11:10','11:20','2022-06-03',''));
        
        $this->assertEquals(false,$korisnik->available('1110','11:20','2022-06-03','2022-06-03'));
        $this->assertEquals(false,$korisnik->available('11:10','1120','2022-06-03','2022-06-03'));
        $this->assertEquals(false,$korisnik->available('11:10','11:20','20220603','2022-06-03'));
        $this->assertEquals(false,$korisnik->available('11:10','11:20','2022-06-03','20220603'));
        
        $this->assertEquals(false,$korisnik->available('a','11:20','2022-06-03','2022-06-03'));
        $this->assertEquals(false,$korisnik->available('11:10','b','2022-06-03','2022-06-03'));
        $this->assertEquals(false,$korisnik->available('11:10','11:20','c','2022-06-03'));
        $this->assertEquals(false,$korisnik->available('11:10','11:20','2022-06-03','d'));
        
        $this->assertEquals(false,$korisnik->available('15:00','12:30','2022-06-02','2022-06-02'));
        $this->assertEquals(false,$korisnik->available('11:20','11:10','2022-06-01','2022-06-01'));
        $this->assertEquals(false,$korisnik->available('11:20','11:10','2022-06-01','2022-06-03'));
        $this->assertEquals(false,$korisnik->available('11:20','11:10','2022-06-03','2022-06-03'));
       
        $this->assertEquals(false,$korisnik->available('11:10','11:20','2022-06-03','2022-06-01'));
        
        $this->assertEquals(false,$korisnik->available('15:00','12:30','2022-06-02','2022-06-02'));
        $this->assertEquals(false,$korisnik->available('11:20','11:10','2022-06-01','2022-06-01'));
        $this->assertEquals(false,$korisnik->available('11:20','11:10','2022-06-03','2022-06-01'));
        $this->assertEquals(false,$korisnik->available('11:20','11:10','2022-06-03','2022-06-03'));
    }

    public function testOverlap()
    {
        $model = new KorisnikModel();
        $korisnik=$model->find(2);
        
        $this->assertEquals(false,$korisnik->overlap(1654160200,1654160300));
        $this->assertEquals(true,$korisnik->overlap(1654160200,1654160600));
        $this->assertEquals(true,$korisnik->overlap(1654160200,1654167800));
        $this->assertEquals(true,$korisnik->overlap(1654160400,1654167800));
        $this->assertEquals(true,$korisnik->overlap(1654160200,1654167600));
        $this->assertEquals(false,$korisnik->overlap(1654160200,1654160400));

        //EQ
        $this->assertEquals(true,$korisnik->overlap(1654160400,1654167600));

        $this->assertEquals(false,$korisnik->overlap(1654167800,1654167900));
        $this->assertEquals(true,$korisnik->overlap(1654160600,1654167800));
        $this->assertEquals(true,$korisnik->overlap(1654160500,1654167500));
        $this->assertEquals(true,$korisnik->overlap(1654160400,1654167800));
        $this->assertEquals(true,$korisnik->overlap(1654160500,1654167600));
        $this->assertEquals(false,$korisnik->overlap(1654167600,1654167900));
    }

    public function testGetReviews()
    {
        $model = new KorisnikModel();
        $korisnik=$model->find(14);
        $zahtevM = new ZahtevModel();
        $zahtev = $zahtevM->find(5);
        $zahtev->linkTermini();
        $zahtev->linkPruzalac();
        $this->assertEquals(1,count($korisnik->getReviews()));
        $this->assertEquals($zahtev,$korisnik->getReviews()[0]);
    }

    public function testGetProviders()
    {
        $providers = KorisnikModel::getProviders();
        $this->assertEquals(12,count($providers));
        $providers = KorisnikModel::getProviders(-1);
        $this->assertEquals(12,count($providers));
        $providers = KorisnikModel::getProviders(12);
        $this->assertEquals(0,count($providers));
        $providers = KorisnikModel::getProviders(1);
        $this->assertEquals(3,count($providers));
        for($i=0;$i<3;$i++)
        {
            $providers[$i]=$providers[$i]->ime;
        }
        $this->assertContains('Mika',$providers);
        $this->assertContains('Žika',$providers);
        $this->assertContains('Ivan',$providers);
    }

    public function testGetRequestsUser()
    {
        $model = new KorisnikModel();
        $korisnik = $model->find(14);
        
        $this->assertEmpty($korisnik->getRequestsUser(1));
        $this->assertEmpty($korisnik->getRequestsUser(2));
        $this->assertEmpty($korisnik->getRequestsUser(3));
        $this->assertEmpty($korisnik->getRequestsUser(7));
        $zahtev = ZahtevModel::findById(5);
        $zahtev->linkTermini();
        $zahtev->linkPruzalac();
        $zahtev->linkKorisnik();
        $zahtev->linkKategorijaPruzaoca();
        $this->assertEquals([$zahtev],$korisnik->getRequestsUser(4));
        $zahtev = ZahtevModel::findById(6);
        $zahtev->linkTermini();
        $zahtev->linkPruzalac();
        $zahtev->linkKorisnik();
        $zahtev->linkKategorijaPruzaoca();
        $zahtev2 = ZahtevModel::findById(9);
        $zahtev2->linkTermini();
        $zahtev2->linkPruzalac();
        $zahtev2->linkKorisnik();
        $zahtev2->linkKategorijaPruzaoca();
        $this->assertEquals([$zahtev2,$zahtev],$korisnik->getRequestsUser(5));
        $zahtev = ZahtevModel::findById(7);
        $zahtev->linkTermini();
        $zahtev->linkPruzalac();
        $zahtev->linkKorisnik();
        $zahtev->linkKategorijaPruzaoca();
        $this->assertEquals([$zahtev],$korisnik->getRequestsUser(6));
        $zahtev = ZahtevModel::findById(8);
        $zahtev->linkTermini();
        $zahtev->linkPruzalac();
        $zahtev->linkKorisnik();
        $zahtev->linkKategorijaPruzaoca();
        $this->assertEquals([$zahtev],$korisnik->getRequestsUser(8));

        $model = new KorisnikModel();
        $korisnik = $model->find(1);

        $this->assertEmpty($korisnik->getRequestsUser(4));
        $this->assertEmpty($korisnik->getRequestsUser(5));
        $this->assertEmpty($korisnik->getRequestsUser(6));
        $this->assertEmpty($korisnik->getRequestsUser(8));
        $zahtev = ZahtevModel::findById(1);
        $zahtev->linkTermini();
        $zahtev->linkPruzalac();
        $zahtev->linkKorisnik();
        $zahtev->linkKategorijaPruzaoca();
        $this->assertEquals([$zahtev],$korisnik->getRequestsUser(1));
        $zahtev = ZahtevModel::findById(2);
        $zahtev->linkTermini();
        $zahtev->linkPruzalac();
        $zahtev->linkKorisnik();
        $zahtev->linkKategorijaPruzaoca();
        $this->assertEquals([$zahtev],$korisnik->getRequestsUser(2));
        $zahtev = ZahtevModel::findById(3);
        $zahtev->linkTermini();
        $zahtev->linkPruzalac();
        $zahtev->linkKorisnik();
        $zahtev->linkKategorijaPruzaoca();
        $this->assertEquals([$zahtev],$korisnik->getRequestsUser(3));
        $zahtev = ZahtevModel::findById(4);
        $zahtev->linkTermini();
        $zahtev->linkPruzalac();
        $zahtev->linkKorisnik();
        $zahtev->linkKategorijaPruzaoca();
        $this->assertEquals([$zahtev],$korisnik->getRequestsUser(7));
    }

    public function testGetRequestsProvider()
    {
        $model = new KorisnikModel();
        $korisnik = $model->find(4);
        
        $this->assertEmpty($korisnik->getRequestsProvider(1));
        $this->assertEmpty($korisnik->getRequestsProvider(2));
        $this->assertEmpty($korisnik->getRequestsProvider(4));
        $this->assertEmpty($korisnik->getRequestsProvider(5));
        $this->assertEmpty($korisnik->getRequestsProvider(6));
        $this->assertEmpty($korisnik->getRequestsProvider(8));
        $zahtev = ZahtevModel::findById(3);
        $zahtev->linkTermini();
        $zahtev->linkPruzalac();
        $zahtev->linkKorisnik();
        $zahtev->linkKategorijaPruzaoca();
        $this->assertEquals([$zahtev],$korisnik->getRequestsProvider(3));
        $zahtev = ZahtevModel::findById(4);
        $zahtev->linkTermini();
        $zahtev->linkPruzalac();
        $zahtev->linkKorisnik();
        $zahtev->linkKategorijaPruzaoca();
        $this->assertEquals([$zahtev],$korisnik->getRequestsProvider(7));
    }

    public function testFindById()
    {
        $this->assertEquals('Lazar',KorisnikModel::findById(1)->ime);
        $this->assertEquals('Miško',KorisnikModel::findById(5)->ime);
        $this->assertEquals('Test',KorisnikModel::findById(14)->ime);
    }
}
