<?php
namespace App\Models;
use CodeIgniter\Model;

class KategorijaModel extends Model
{
    protected $table      = 'kategorije';
    protected $primaryKey = 'idKategorije';
    protected $useAutoIncrement = true;

    protected $returnType     = 'KategorijaModel';

    protected $allowedFields = [];

    public function linkPruzaoci()
    {
        $korsnikM = new KorisnikModel();
        $this->pruzaoci = $korsnikM->where('idKategorije',$this->idKategorije)->find();
    }
}