<?php
namespace App\Models;
use CodeIgniter\Model;

class KorisnikModel extends Model
{
    protected $table      = 'korisnici';
    protected $primaryKey = 'idKorisnika';
    protected $useAutoIncrement = true;

    protected $returnType     = 'App\Models\KorisnikModel';

    protected $allowedFields = [];

    public function linkKategorija()
    {
        $kategorijaM = new KategorijaModel();
        $this->kategorija=$kategorijaM->find($this->idKategorije);
    }

    public function linkUpuceniZahtevi()
    {
        $zahtevM = new ZahtevModel();
        $this->upuceniZahtevi=$zahtevM->where('idKorisnika',$this->idKorisnika)->findAll();
    }

    public function linkPrimljeniZahtevi()
    {
        $zahtevM = new ZahtevModel();
        $this->primljeniZahtevi=$zahtevM->where('idPruzaoca',$this->idKorisnika)->findAll();
    }

    public function linkManuelnoZauzetiTermini()
    {
        $terminM = new TerminModel();
        $this->manuelnoZauzetiTermini=$terminM->where('idPruzaoca',$this->idKorisnika)->findAll();
    }

    public function role() 
    {
        if ($this->administrator == 1) return 'admin';
        else if ($this->pruzalac == 1) return 'provider';
        else return 'user';
    }
}