<?php
namespace App\Models;
use CodeIgniter\Model;

class ZahtevModel extends Model
{
    protected $table      = 'zahtevi';
    protected $primaryKey = 'idZahteva';
    protected $useAutoIncrement = true;

    protected $returnType     = 'App\Models\ZahtevModel';

    protected $allowedFields = [];

    public function linkTermini()
    {
        $terminM = new TerminModel();
        $this->termini = $terminM->where('idZahteva',$this->idZahteva)->findAll();
    }

    public function linkKorisnik()
    {
        $korisnikM = new KorisnikModel();
        $this->korisnik = $korisnikM->find($this->idKorisnika);
    }

    public function linkPruzalac()
    {
        $korisnikM = new KorisnikModel();
        $this->korisnik = $korisnikM->find($this->idPruzaoca);
    }
}