<?php
namespace App\Models;
use CodeIgniter\Model;

class ZahtevModel extends Model
{
    protected $table      = 'zahtevi';
    protected $primaryKey = 'idZahteva';
    protected $useAutoIncrement = true;

    protected $returnType     = 'object';

    protected $allowedFields = [];

    public function linkTermini($zahtev)
    {
        $terminM = new TerminModel();
        $zahtev->termini = $terminM->where('idZahteva',$zahtev->idZahteva)->findAll();
    }

    public function linkKorisnik($zahtev)
    {
        $korisnikM = new KorisnikModel();
        $zahtev->korisnik = $korisnikM->find($zahtev->idKorisnika);
    }

    public function linkPruzalac($zahtev)
    {
        $korisnikM = new KorisnikModel();
        $zahtev->korisnik = $korisnikM->find($zahtev->idPruzaoca);
    }
}