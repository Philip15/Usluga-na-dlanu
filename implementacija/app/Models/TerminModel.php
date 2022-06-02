<?php
/**
  * @author Lazar Premović  2019/0091
  */

namespace App\Models;
use CodeIgniter\Model;

/**
 * TerminModel - model za tabelu Termini
 */
class TerminModel extends Model
{
    protected $table      = 'termini';
    protected $primaryKey = 'idTermina';
    protected $useAutoIncrement = true;

    protected $returnType     = 'App\Models\TerminModel';

    protected $allowedFields = ['idPruzaoca','datumVremePocetka','trajanje','idZahteva'];

    /**
     * Funkcija koja dohvata pruzaoca za termin
     * 
     * @return void
     */
    public function linkPruzalac()
    {
        $korsnikM = new KorisnikModel();
        $this->pruzalac = $korsnikM->find($this->idPruzaoca);
    }

    /**
     * Funkcija koja dohvata zahtev za termin
     * 
     * @return void
     */
    public function linkZahtev()
    {
        $zahtevM = new ZahtevModel();
        $this->zahtev = $zahtevM->find($this->idZahteva);
    }

    /**
     * Funkcija koja pronalazi termin po identifikatoru
     * 
     * @param int $id identifikator
     * 
     * @return TerminModel
     */
    public static function findById($id)
    {
        $terminM = new TerminModel();
        return $terminM->find($id);
    }
}