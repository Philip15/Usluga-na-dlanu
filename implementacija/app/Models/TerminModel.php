<?php
namespace App\Models;
use CodeIgniter\Model;

class TerminModel extends Model
{
    protected $table      = 'termini';
    protected $primaryKey = 'idTermina';
    protected $useAutoIncrement = true;

    protected $returnType     = 'object';

    protected $allowedFields = [];

    public function linkPruzalac($termin)
    {
        $korsnikM = new KorisnikModel();
        $termin->pruzalac = $korsnikM->find($termin->idPruzaoca);
    }

    public function linkZahtev($termin)
    {
        $zahtevM = new ZahtevModel();
        $termin->zahtev = $zahtevM->find($termin->idZahteva);
    }
}