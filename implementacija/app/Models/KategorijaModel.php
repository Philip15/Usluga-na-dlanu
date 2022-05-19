<?php
namespace App\Models;
use CodeIgniter\Model;

class KategorijaModel extends Model
{
    protected $table      = 'kategorije';
    protected $primaryKey = 'idKategorije';
    protected $useAutoIncrement = true;

    protected $returnType     = 'App\Models\KategorijaModel';

    protected $allowedFields = ['naziv'];

    public function linkPruzaoci()
    {
        $korsnikM = new KorisnikModel();
        $this->pruzaoci = $korsnikM->where('idKategorije',$this->idKategorije)->find();
    }

    public static function getAll()
    {
        $kategorijaM = new  KategorijaModel();
        return $kategorijaM->orderBy('idKategorije','ASC')->findAll();
    }

}