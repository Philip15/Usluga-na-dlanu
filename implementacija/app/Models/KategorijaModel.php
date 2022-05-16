<?php
namespace App\Models;
use CodeIgniter\Model;

class KategorijaModel extends Model
{
    protected $table      = 'kategorije';
    protected $primaryKey = 'idKategorije';
    protected $useAutoIncrement = true;

    protected $returnType     = 'object';

    protected $allowedFields = [];

    public function linkPruzaoci($kategorija)
    {
        $korsnikM = new KorisnikModel();
        $kategorija->pruzaoci = $korsnikM->where('idKategorije',$kategorija->idKategorije)->find();
    }
}