<?php

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;
use App\Models\KorisnikModel;
use App\Models\ZahtevModel;
use App\Models\KategorijaModel;
use Config\Services;

use function PHPUnit\Framework\equalTo;

final class AdminControllerTest extends CIUnitTestCase
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

    public function testOPAddCategorySuccess()
    {
        $res = $this->withSession()->post('/AdminController/OPAddCategory', ['category'=>'Novakategorija']);
        
        $res->assertOK();
        $this->assertNotNull(KategorijaModel::get('novakategorija'));
    }

    public function testOPAddCategoryEmptyName()
    {
        $res = $this->withSession()->post('/AdminController/OPAddCategory', ['category'=>'']);
        
        $res->assertOK();
        $this->assertTrue(session('alertErrorText')===lang('App.errEmptyCategory'));
    }

    public function testOPAddCategoryAlreadyExists()
    {
        $res = $this->withSession()->post('/AdminController/OPAddCategory', ['category'=>'moler']);
        
        $res->assertOK();
        $this->assertTrue(session('alertErrorText')===lang('App.errCategoryAlreadyExists'));
    }

    public function testOPRemoveCategorySuccess()
    {
        $res = $this->withSession()->post('/AdminController/OPRemoveCategory', ['id'=>40]);
        
        $res->assertOK();
        $this->assertNull(KategorijaModel::get('stolar'));
    }

    public function testOPRemoveCategoryHasProviders()
    {
        $res = $this->withSession()->post('/AdminController/OPRemoveCategory', ['id'=>2]);
        $res->assertOK();
        $this->assertEquals(session('alertErrorText'), lang('App.errCategoryHasProviders'));
        $this->assertNotNull(KategorijaModel::get('moler'));
    }

    public function testOPApproveRequest()
    {
        $korM = new KorisnikModel();
        $korM->update(4, ['pruzalac'=>0]);
        $_SESSION['user']=KorisnikModel::findById(4);
        $res = $this->post('/UserController/OPConvertProfile', [
            'kategorija' => 2,
            'adresaPoslovanja' => 'Ulica 5',
            'lat' => '45',
            'lon' => '45',
        ]);
        $res->assertOK();
        $_SESSION['user']=KorisnikModel::findById(1);

        $res = $this->withSession()->post('/AdminController/OPApproveRequest', ['id'=>4]);
        $res->assertOK();

        $this->assertEquals(KorisnikModel::findById(4)->pruzalac, 1); 
    }

    public function testOPDenyRequest()
    {
        $korM = new KorisnikModel();
        $korM->update(4, ['pruzalac'=>0]);
        $_SESSION['user']=KorisnikModel::findById(4);
        $res = $this->withSession()->post('/UserController/OPConvertProfile', [
            'kategorija' => 2,
            'adresaPoslovanja' => 'Ulica 5',
            'lat' => '45',
            'lon' => '45',
        ]);
        $res->assertOK();
        $_SESSION['user']=KorisnikModel::findById(1);

        $res = $this->withSession()->post('/AdminController/OPDenyRequest', ['id'=>4]);
        $res->assertOK();

        $this->assertEquals(KorisnikModel::findById(4)->pruzalac, 0);

    }
}