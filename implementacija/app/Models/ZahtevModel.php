<?php
/**
  * @author Lazar Premović  2019/0091
  * @author Filip Janjić    2019/0116
  */

namespace App\Models;
use CodeIgniter\Model;
/**
 * ZahtevModel - model za tabelu Zahtevi
 */
class ZahtevModel extends Model
{
    protected $table      = 'zahtevi';
    protected $primaryKey = 'idZahteva';
    protected $useAutoIncrement = true;

    protected $returnType     = 'App\Models\ZahtevModel';

    protected $allowedFields = ['idKorisnika','idPruzaoca','stanje','opis','hitno','cena','komentar','ocena','recenzija'];
    // stanje: 1 - upucen pruzaocu, 2 - data ponuda, 3 - prihvacena ponuda, 4 - realizovan, 5 - ocenjen, 6 - odbijen od provajdera, 7 - odbijen od korisnika, 8 - odbijen obavesten

    /**
     * Funkcija koja dohvata termine za zahtev
     * 
     * @return void
     */
    public function linkTermini()
    {
        $terminM = new TerminModel();
        $this->termini = $terminM->where('idZahteva',$this->idZahteva)->orderBy('datumVremePocetka','ASC')->findAll();
    }

    /**
     * Funkcija koja dohvata klijenta za zahtev
     * 
     * @return void
     */
    public function linkKorisnik()
    {
        $korisnikM = new KorisnikModel();
        $this->korisnik = $korisnikM->find($this->idKorisnika);
    }

    /**
     * Funkcija koja dohvata pruzaoca za zahtev
     * 
     * @return void
     */
    public function linkPruzalac()
    {
        $korisnikM = new KorisnikModel();
        $this->pruzalac = $korisnikM->find($this->idPruzaoca);
    }

    /**
     * Funkcija koja dohvata kategoriju pruzaoca za zahtev
     * 
     * @return void
     */
    public function linkKategorijaPruzaoca()
    {
        $korisnikM = new KorisnikModel();
        $kategorijaM = new KategorijaModel();
        $this->kategorija = $kategorijaM->find($korisnikM->find($this->idPruzaoca)->idKategorije);
    }
    
    /**
     * Funkcija koja dohvata tekstualni opis za trenutno stanje zahteva
     * 
     * @return string
     */
    public function descriptiveState()
    {
        return lang('App.requestState'.$this->stanje);
    }

    /**
     * Funkcija koja pronalazi zahtev po njegovom identifikatoru
     * 
     * @param int $id identifikator
     * 
     * @return ZahtevModel
     */
    public static function findById($id)
    {
        $zahtevM = new ZahtevModel();
        return $zahtevM->find($id);
    }

    /**
     * Funkcija koja pronalazi sve zahteve sa recenzijom za datog pruzaoca
     * 
     * @param int $id id pruzaoca
     * 
     * @return ZahtevModel[]
     */
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