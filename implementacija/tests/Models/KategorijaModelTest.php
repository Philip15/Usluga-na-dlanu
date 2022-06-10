<?php

use App\Models\KategorijaModel;
use App\Models\KorisnikModel;
use PHPUnit\Framework\TestCase;

final class KategorijaModelTest extends TestCase
{

    public function testLinkPruzaoci()
    {
        $katM = new KategorijaModel();
        $kategorija = $katM->find(1);
        $kategorija->linkPruzaoci();
        
        $this->assertNotNull($kategorija->pruzaoci);
        $this->assertEquals(KorisnikModel::getProviders(1), $kategorija->pruzaoci);

        $kategorija = $katM->find(2);
        $kategorija->linkPruzaoci();
        
        $this->assertNotNull($kategorija->pruzaoci);
        $this->assertEquals(KorisnikModel::getProviders(2), $kategorija->pruzaoci);

        $kategorija = $katM->find(3);
        $kategorija->linkPruzaoci();
        
        $this->assertNotNull($kategorija->pruzaoci);
        $this->assertEquals(KorisnikModel::getProviders(3), $kategorija->pruzaoci);
    }

    public function testGetAll()
    {
        $katM = new KategorijaModel();
        $kategorije = $katM->orderBy('idKategorije', 'ASC')->findAll();

        $this->assertNotNull($kategorije);
        $this->assertEquals($katM->getAll(), $kategorije);
    }

    public function testGetByName()
    {
        $kategorija = KategorijaModel::get('moler');
        $this->assertNotNull($kategorija);
        $this->assertEquals('moler', $kategorija->naziv);

        $kategorija = KategorijaModel::get('vodoinstalater');
        $this->assertNotNull($kategorija);
        $this->assertEquals('vodoinstalater', $kategorija->naziv);

        $kategorija = KategorijaModel::get('nekakategorija');
        $this->assertNull($kategorija);
    }

    public function testGetById()
    {
        $katM = new KategorijaModel();

        $kategorija = $katM->findById(1);
        $this->assertNotNull($kategorija);
        $this->assertEquals($kategorija->idKategorije, 1);


        $kategorija = $katM->findById(2);
        $this->assertNotNull($kategorija);
        $this->assertEquals($kategorija->idKategorije, 2);

        $kategorija = $katM->findById(8);
        $this->assertNull($kategorija);
    }



}