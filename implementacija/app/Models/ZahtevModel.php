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

    public function descriptiveState()
    {
        return lang('App.requestState'.$this->stanje);
    }

    public static function findById($id)
    {
        $zahtevM = new ZahtevModel();
        return $zahtevM->find($id);
    }
}