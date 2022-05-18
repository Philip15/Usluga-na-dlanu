<?php
namespace App\Models;
use CodeIgniter\Model;

class TerminModel extends Model
{
    protected $table      = 'termini';
    protected $primaryKey = 'idTermina';
    protected $useAutoIncrement = true;

    protected $returnType     = 'App\Models\TerminModel';

    protected $allowedFields = [];

    public function linkPruzalac()
    {
        $korsnikM = new KorisnikModel();
        $this->pruzalac = $korsnikM->find($this->idPruzaoca);
    }

    public function linkZahtev()
    {
        $zahtevM = new ZahtevModel();
        $this->zahtev = $zahtevM->find($this->idZahteva);
    }
}