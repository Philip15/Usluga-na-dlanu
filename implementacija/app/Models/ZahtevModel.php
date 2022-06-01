<?php
namespace App\Models;
use CodeIgniter\Model;

class ZahtevModel extends Model
{
    protected $table      = 'zahtevi';
    protected $primaryKey = 'idZahteva';
    protected $useAutoIncrement = true;

    protected $returnType     = 'App\Models\ZahtevModel';

    protected $allowedFields = ['idKorisnika','idPruzaoca','stanje','opis','hitno','cena','komentar','ocena','recenzija'];
    // stanje: 1 - upucen pruzaocu, 2 - data ponuda, 3 - prihvacena ponuda, 4 - realizovan, 5 - ocenjen, 6 - odbijen 

    public function linkTermini()
    {
        $terminM = new TerminModel();
        $this->termini = $terminM->where('idZahteva',$this->idZahteva)->orderBy('datumVremePocetka','ASC')->findAll();
    }

    public function linkKorisnik()
    {
        $korisnikM = new KorisnikModel();
        $this->korisnik = $korisnikM->find($this->idKorisnika);
    }

    public function linkPruzalac()
    {
        $korisnikM = new KorisnikModel();
        $this->pruzalac = $korisnikM->find($this->idPruzaoca);
    }

    public function linkKategorijaPruzaoca()
    {
        $korisnikM = new KorisnikModel();
        $kategorijaM = new KategorijaModel();
        $this->kategorija = $kategorijaM->find($korisnikM->find($this->idPruzaoca)->idKategorije);
    }
    
    public function descriptiveState()
    {
        return lang('App.requestState'.$this->stanje);
    }

    public static function findById($id)
    {
        $zahtevM = new ZahtevModel();
        return $zahtevM->find($id);
    }

    // TODO
    public static function findAllPendingForUser($id)
    {
        $zahtevi = new ZahtevModel();
        $zahtevi =  $zahtevi->where("idKorisnika", $id);
    }

    //TODO
    public static function findAllPendingForProvider($id)
    {
        $zahtevi = new ZahtevModel();
        $zahtevi =  $zahtevi->where("idPruzaoca", $id);
    }

    public function findAllReviewsForProvider($id)
    {
        $zahtevi = $this->where('idPruzaoca', $id)->findAll();
        $reviews = [];
        foreach($zahtevi as $zahtev)
        {
            $zahtev->linkKorisnik();
            if($zahtev->ocena !== null)
            {
                array_push($reviews, [$zahtev->ocena, $zahtev->recenzija, $zahtev->korisnik]);
            }
        }
        return $reviews;
    }
}