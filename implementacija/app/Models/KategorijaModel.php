<?php
/**
  * @author Lazar Premović  2019/0091
  * @author Jana Pašajlić   2019/0132
  */

namespace App\Models;
use CodeIgniter\Model;
/**
 * KategorijaModel - model za tabelu Kategorije
 */
class KategorijaModel extends Model
{
    protected $table      = 'kategorije';
    protected $primaryKey = 'idKategorije';
    protected $useAutoIncrement = true;

    protected $returnType     = 'App\Models\KategorijaModel';

    protected $allowedFields = ['naziv'];

    /**
     * Funckija koja pronalazi pruzaoce za kategoriju
     * 
     * @return void
     */
    public function linkPruzaoci()
    {
        $korsnikM = new KorisnikModel();
        $this->pruzaoci = $korsnikM->where('idKategorije',$this->idKategorije)->where('pruzalac', 1)->find();
    }

    /**
     * Funkcija koja dohvata sve kategorije soritrane po id rastuce
     * 
     * @return KategorijaModel[]
     */
    public static function getAll()
    {
        $kategorijaM = new  KategorijaModel();
        return $kategorijaM->orderBy('idKategorije','ASC')->findAll();
    }

    /**
     * Funkcija koja dohvata kategoriju po nazivu
     * 
     * @param string $name ime kategorije
     * 
     * @return KategorijaModel
     */
    public static function get($name)
    {
        $kategorijaM = new  KategorijaModel();
        return $kategorijaM->where('naziv',$name)->first();
    }

    /**
     * Funkcija koja dohvata kateogorju za dati id
     * 
     * @param int $id id kategorije
     * 
     * @return KategorijaModel
     */
    public static function findById($id)
    {
        $kategorijaM = new  KategorijaModel();
        return $kategorijaM->find($id);
    }
}