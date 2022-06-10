<?php

use App\Models\KorisnikModel;
use App\Models\TerminModel;
use App\Models\ZahtevModel;
use PHPUnit\Framework\TestCase;

final class TerminModelTest extends TestCase 
{
    protected function setUp():void
    {
        $this->resetDB();
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

    public function testLinkPruzalac()
    {
        $model = new TerminModel();
        $termin = $model->find(1);
        $termin->linkPruzalac();
        $this->assertEquals(KorisnikModel::findById(2),$termin->pruzalac);
    }

    public function testNoLinkZahtev()
    {
        $model = new TerminModel();
        $termin = $model->find(5);
        $termin->linkZahtev();
        $this->assertNull($termin->zahtev);
    }

    public function testLinkZahtev()
    {
        $model = new TerminModel();
        $termin = $model->find(1);
        $termin->linkZahtev();
        $this->assertEquals(ZahtevModel::findById(1),$termin->zahtev);
    }

    public function testFindById()
    {
        $model = new TerminModel();
        $termin = $model->find(1);
        $this->assertEquals($termin, TerminModel::findById(1));
        $termin = $model->find(3);
        $this->assertEquals($termin, TerminModel::findById(3));
        $termin = $model->find(5);
        $this->assertEquals($termin, TerminModel::findById(5));
    }
}

?>