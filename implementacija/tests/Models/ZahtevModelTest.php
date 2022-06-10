<?php

use App\Models\KategorijaModel;
use App\Models\KorisnikModel;
use App\Models\TerminModel;
use App\Models\ZahtevModel;
use PHPUnit\Framework\TestCase;

final class ZahtevModelTest extends TestCase 
{
    protected function setUp():void
    {
        // $this->resetDB();
    }

    protected function resetDB()
    {
        $db = \Config\Database::connect();
        $lines = file("C:\Users\Jana\Desktop\Usluga-na-dlanu\docs\Modelovanje Baze\usluga_na_dlanu_data.sql");
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

    public function testLinkTermini() 
    {
        $model = new ZahtevModel();
        $zahtev = $model->find(1);
        $zahtev->linkTermini();
        $terminM = new TerminModel();
        $termini = $terminM->where('idZahteva', 1)->orderBy('datumVremePocetka','ASC')->findAll();
        $this->assertEquals($termini, $zahtev->termini);
    }

    public function testLinkKorisnik()
    {
        $model = new ZahtevModel();
        $zahtev = $model->find(1);
        $zahtev->linkKorisnik();
        $this->assertEquals(KorisnikModel::findById(1), $zahtev->korisnik);
    }

    public function testLinkPruzalac()
    {
        $model = new ZahtevModel();
        $zahtev = $model->find(1);
        $zahtev->linkPruzalac();
        $this->assertEquals(KorisnikModel::findById(2), $zahtev->pruzalac);
    }

    public function testLinkKategorijaPruzaoca()
    {
        $model = new ZahtevModel();
        $zahtev = $model->find(1);
        $zahtev->linkKategorijaPruzaoca();
        $korisnikM = new KorisnikModel();
        $kategorijaM = new KategorijaModel();
        $pruzalac = $korisnikM->find(2);
        $kategorija = $kategorijaM->find(1);
        $this->assertEquals($kategorija, $zahtev->kategorija);
    }

    public function testDescriptiveState()
    {
        $model = new ZahtevModel();
        $zahtev = $model->find(1);
        $this->assertEquals(lang('App.requestState'.ZahtevModel::findById(1)->stanje), $zahtev->descriptiveState());
        $zahtev = $model->find(2);
        $this->assertEquals(lang('App.requestState'.ZahtevModel::findById(2)->stanje), $zahtev->descriptiveState());
        $zahtev = $model->find(5);
        $this->assertEquals(lang('App.requestState'.ZahtevModel::findById(5)->stanje), $zahtev->descriptiveState());
    }

    public function testFindById()
    {
        $model = new ZahtevModel();
        $zahtev = $model->find(1);
        $this->assertEquals($zahtev, ZahtevModel::findById(1));
        $zahtev = $model->find(4);
        $this->assertEquals($zahtev, ZahtevModel::findById(4));
        $zahtev = $model->find(7);
        $this->assertEquals($zahtev, ZahtevModel::findById(7));
    }

    public function testNoFindAllReviewsForProvider() 
    {
        $model = new ZahtevModel();
        $recenzije = $model->findAllReviewsForProvider(1);
        $this->assertEmpty($recenzije);
    }

    public function testFindAllReviewsForProvider() 
    {
        $model = new ZahtevModel();
        $recenzije = $model->findAllReviewsForProvider(12);
        $this->assertEquals(['4', 'komentar', KorisnikModel::findById(14)], $recenzije[0]);

        $model->insert(['idZahteva'=>10,'idKorisnika'=>14,'idPruzaoca'=>2,'stanje'=>5,'opis'=>'tst','hitno'=>0,'ocena'=>5, 'recenzija'=>'kom0']);
        $id = $model->getInsertID();
        $model->insert(['idZahteva'=>11,'idKorisnika'=>14,'idPruzaoca'=>2,'stanje'=>5,'opis'=>'tst','hitno'=>0,'ocena'=>4, 'recenzija'=>'kom1']);
        $model->insert(['idZahteva'=>12,'idKorisnika'=>14,'idPruzaoca'=>2,'stanje'=>5,'opis'=>'tst','hitno'=>0,'ocena'=>3, 'recenzija'=>'kom2']);
        $model->insert(['idZahteva'=>13,'idKorisnika'=>14,'idPruzaoca'=>2,'stanje'=>5,'opis'=>'tst','hitno'=>0,'ocena'=>1, 'recenzija'=>'kom3']);
        $model->insert(['idZahteva'=>14,'idKorisnika'=>14,'idPruzaoca'=>2,'stanje'=>5,'opis'=>'tst','hitno'=>0,'ocena'=>5, 'recenzija'=>'kom4']);

        $recenzije = $model->findAllReviewsForProvider(2);
        $korisnik =  KorisnikModel::findById(14);
        $this->assertEquals([['5', 'kom0', $korisnik], ['4', 'kom1', $korisnik], ['3', 'kom2', $korisnik],['1', 'kom3', $korisnik], ['5', 'kom4', $korisnik]], $recenzije);

        $model->delete($id);
        $model->delete($id+1);
        $model->delete($id+2);
        $model->delete($id+3);
        $model->delete($id+4);
        
    }
}


?>