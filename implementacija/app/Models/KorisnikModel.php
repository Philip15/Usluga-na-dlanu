<?php
namespace App\Models;
use CodeIgniter\Model;
use DateTime;
use DateInterval;
use DatePeriod;
use Exception;
use stdClass;

class KorisnikModel extends Model
{
    protected $table      = 'korisnici';
    protected $primaryKey = 'idKorisnika';
    protected $useAutoIncrement = true;

    protected $returnType     = 'App\Models\KorisnikModel';

    protected $allowedFields = ['korisnickoIme', 'lozinka', 'email', 'ime', 'prezime', 'profilnaSlika',  'opis', 
            'pruzalac', 'adresa', 'lat', 'lon', 'idKategorije', 'administrator'];

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
        if ($this->administrator == 1) { return 'admin';    }
        elseif  ($this->pruzalac == 1) { return 'provider'; }
        else                           { return 'user';     }
    }

    public function requestNotifications()
    {
        $zahtevM = new ZahtevModel();
        return $zahtevM->where('idKorisnika',$this->idKorisnika)->where('stanje',2)->countAllResults();
    }

    public function reviewNotifications()
    {
        $zahtevM = new ZahtevModel();
        return $zahtevM->where('idKorisnika',$this->idKorisnika)->where('stanje',4)->countAllResults();
    }

    public function incomingNotifications()
    {
        $zahtevM = new ZahtevModel();
        return $zahtevM->where('idPruzaoca',$this->idKorisnika)->where('stanje',1)->countAllResults();
    }

    public function acceptedNotifications()
    {
        $zahtevM = new ZahtevModel();
        return $zahtevM->where('idPruzaoca',$this->idKorisnika)->where('stanje',3)->countAllResults();
    }

    public function accountNotifications()
    {
        $korisnikM = new KorisnikModel();
        return $korisnikM->where('pruzalac',2)->countAllResults();
    }

    public function hasNotifications()
    {
        $res = $this->requestNotifications() + $this->reviewNotifications();
        if($this->role()=='provider')
        {
            $res = $res + $this->incomingNotifications() + $this->acceptedNotifications();
        }
        if($this->role()=='admin')
        {
            $res = $res + $this->accountNotifications();
        }

        return $res!=0;
    }

    public function rating()
    {
        $zahtevM = new ZahtevModel();
        return doubleval($zahtevM->selectAvg('ocena')->where('idPruzaoca',$this->idKorisnika)->first()->ocena);
    }

    public function available($tFrom,$tTo,$dFrom,$dTo)
    {
        $this->linkManuelnoZauzetiTermini();
        $availArr = $this->shortTermini();
        try
        {
            $tFrom = intval(explode(":",$tFrom)[0]*60) + intval(explode(":",$tFrom)[1]);
            $tTo = intval(explode(":",$tTo)[0]*60) + intval(explode(":",$tTo)[1]);

            $begin = new DateTime($dFrom);
            $end = new DateTime($dTo);
        }
        catch (Exception  $ex)
        {
            return false;
        }

        $interval = DateInterval::createFromDateString('1 day');
        $period = new DatePeriod($begin, $interval, $end->add($interval));

        foreach ($period as $dt) 
        {
            $day=$dt->format('Y-m-d');
            for ($time=$tFrom; $time<$tTo ; $time++) 
            { 
                $free=true;
                foreach($availArr as $slot)
                {
                    if($day==$slot->date)
                    {
                        if($slot->startTime<=$time && $slot->endTime>$time)
                        {
                            $free=false;
                            break;
                        }
                    }
                }
                if($free){return true;}
            }
        }
    }

    public function shortTermini()
    {
        $res = [];
        foreach ($this->manuelnoZauzetiTermini as $termin) 
        {
            $obj=new stdClass();
            $dt=new DateTime($termin->datumVremePocetka);
            $obj->date = $dt->format('Y-m-d');
            $obj->startTime = $dt->format('H')*60 + $dt->format('i');
            $obj->endTime = $obj->startTime + $termin->trajanje;

            array_push($res,$obj);
        }
        return $res;
    }

    public function overlap($start,$end)
    {
        date_default_timezone_set('Europe/Belgrade');
        $startDate= new DateTime();
        $startDate->setTimestamp($start);
        $startHM = $startDate->format('H')*60 + $startDate->format('i');
        $endDate= new DateTime();
        $endDate->setTimestamp($end);
        $endHM = $endDate->format('H')*60 + $endDate->format('i');
        if($startHM<8*60 || $endHM>20*60){return true;}
        $this->linkManuelnoZauzetiTermini();
        foreach($this->manuelnoZauzetiTermini as $slot)
        {
            $dt=new DateTime($slot->datumVremePocetka);
            $ts=intval($dt->format('U'));
            if(($ts+$slot->trajanje) > $start and $end > $ts)
            {
                return true;
            }
        }
        return false;
    }

    public function getReviews()
    {
        $zahtevM = new ZahtevModel();
        $reviews = $this->upuceniZahtevi=$zahtevM->where('idKorisnika',$this->idKorisnika)->where('stanje',4)->orderBy('idZahteva','DSC')->findAll();
        foreach ($reviews as $review) {
            $review->linkTermini();
            $review->linkPruzalac();
        }
        return $reviews;
    }

    public static function getProviders($cat=null)
    {    
        $korisnikM = new KorisnikModel();
        $korisnikM = $korisnikM->where('pruzalac',1);
        if($cat!=null && $cat!=-1)
        {
            $korisnikM = $korisnikM->where('idKategorije',$cat);
        }
        $providers = $korisnikM->findAll();
        return $providers;
    }

    public function getRequestsUser($st)
    {
        $zahtevM = new ZahtevModel();
        $requests = $zahtevM->where('idKorisnika',$this->idKorisnika)->where('stanje', $st)->orderBy('idZahteva','DSC')->findAll();

        foreach ($requests as $request) {
            $request->linkTermini();
            $request->linkPruzalac();
            $request->linkKorisnik();
            $request->linkKategorijaPruzaoca();
        }

        return $requests;
    }

    public function getRequestsProvider($st)
    {
        $zahtevM = new ZahtevModel();
        $requests = $zahtevM->where('idPruzaoca',$this->idKorisnika)->where('stanje', $st)->orderBy('idZahteva','DSC')->findAll();
        foreach ($requests as $request) {
            $request->linkTermini();
            $request->linkPruzalac();
            $request->linkKorisnik();
            $request->linkKategorijaPruzaoca();
        }
        return $requests;
    }

    public static function findById($id)
    {
        $korisnikM = new KorisnikModel();
        return $korisnikM->find($id);
    }
}