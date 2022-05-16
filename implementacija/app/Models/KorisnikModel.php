<?php
namespace App\Models;
use CodeIgniter\Model;

class KorisnikModel extends Model
{
    protected $table      = 'korisnici';
    protected $primaryKey = 'idKorisnika';
    protected $useAutoIncrement = true;

    protected $returnType     = 'object';

    protected $allowedFields = [];

    public function linkKategorija($korisnik)
    {
        $kategorijaM = new KategorijaModel();
        $korisnik->kategorija=$kategorijaM->find($korisnik->idKategorije);
    }

    public function linkUpuceniZahtevi($korisnik)
    {
        $zahtevM = new ZahtevModel();
        $korisnik->upuceniZahtevi=$zahtevM->where('idKorisnika',$korisnik->idKorisnika)->findAll();
    }

    public function linkPrimljeniZahtevi($korisnik)
    {
        $zahtevM = new ZahtevModel();
        $korisnik->primljeniZahtevi=$zahtevM->where('idPruzaoca',$korisnik->idKorisnika)->findAll();
    }

    public function linkManuelnoZauzetiTermini($korisnik)
    {
        $terminM = new TerminModel();
        $korisnik->manuelnoZauzetiTermini=$terminM->where('idPruzaoca',$korisnik->idKorisnika)->findAll();
    }
}