<?php
/**
  * @author Lazar Premović  2019/0091
  * @author Jana Pašajlić   2019/0132
  * @author Filip Janjić    2019/0116
  */

namespace App\Models;
use CodeIgniter\Model;
use DateTime;
use DateInterval;
use DatePeriod;
use Error;
use Exception;
use stdClass;

/**
 * KorisnikaModel - model za tabelu Korisnici
 */
class KorisnikModel extends Model
{
    protected $table      = 'korisnici';
    protected $primaryKey = 'idKorisnika';
    protected $useAutoIncrement = true;

    protected $returnType     = 'App\Models\KorisnikModel';

    protected $allowedFields = ['korisnickoIme', 'lozinka', 'email', 'ime', 'prezime', 'profilnaSlika',  'opis', 
            'pruzalac', 'adresa', 'lat', 'lon', 'idKategorije', 'administrator'];

    /**
     * Funckija koja pronalazi kategoriju za korisnika
     * 
     * @return void
     */
    public function linkKategorija()
    {
        if($this->idKategorije!=null)
        {
            $kategorijaM = new KategorijaModel();
            $this->kategorija=$kategorijaM->find($this->idKategorije);
        }
        else
        {
            $this->kategorija=null;
        }
    }

    /**
     * Funckija koja pronalazi upucene zahteve za korisnika
     * 
     * @return void
     */
    public function linkUpuceniZahtevi()
    {
        $zahtevM = new ZahtevModel();
        $this->upuceniZahtevi=$zahtevM->where('idKorisnika',$this->idKorisnika)->findAll();
    }

    /**
     * Funckija koja pronalazi primljene zahteve za korisnika
     * 
     * @return void
     */
    public function linkPrimljeniZahtevi()
    {
        $zahtevM = new ZahtevModel();
        $this->primljeniZahtevi=$zahtevM->where('idPruzaoca',$this->idKorisnika)->findAll();
    }

    /**
     * Funckija koja pronalazi sve termine za korisnika
     * 
     * @return void
     */
    public function linkManuelnoZauzetiTermini()
    {
        $terminM = new TerminModel();
        $this->manuelnoZauzetiTermini=$terminM->where('idPruzaoca',$this->idKorisnika)->findAll();
    }

    /**
     * Funkcija koja odredjuje ulogu korisnika
     * 
     * @return string
     */
    public function role() 
    {
        if ($this->administrator == 1) { return 'admin';    }
        elseif  ($this->pruzalac == 1) { return 'provider'; }
        else                           { return 'user';     }
    }

    /**
     * Funkcija koja dohvata broj zahteva koji imaju datu ponudu
     * 
     * @return int
     */
    public function requestNotifications()
    {
        $zahtevM = new ZahtevModel();
        return $zahtevM->where('idKorisnika',$this->idKorisnika)->where('stanje',2)->countAllResults();
    }

    /**
     * Funkcija koja dohvata broj recenzija koje korisnik moze da ostavi 
     * 
     * @return int
     */
    public function reviewNotifications()
    {
        $zahtevM = new ZahtevModel();
        return $zahtevM->where('idKorisnika',$this->idKorisnika)->where('stanje',4)->countAllResults();
    }

    /**
     * Funkcija koja dohvata broj odbijenih zahteva od strane pruzaoca
     * 
     * @return int
     */
    public function rejectedNotifications()
    {
        $zahtevM = new ZahtevModel();
        return $zahtevM->where('idKorisnika',$this->idKorisnika)->where('stanje',6)->countAllResults();
    }

    /**
     * Funkcija koja dohvata broj upucenih zahteva od korisnika ka pruzaocu
     * 
     * @return int
     */
    public function incomingNotifications()
    {
        $zahtevM = new ZahtevModel();
        return $zahtevM->where('idPruzaoca',$this->idKorisnika)->where('stanje',1)->countAllResults();
    }

    /**
     * Funkcija koja dohvata broj prihvacenih ponuda
     * 
     * @return int
     */
    public function acceptedNotifications()
    {
        $zahtevM = new ZahtevModel();
        return $zahtevM->where('idPruzaoca',$this->idKorisnika)->where('stanje',3)->countAllResults();
    }

    /**
     * Funkcija koja dohvata broj odbijenih zahteva od strane korisnika
     * 
     * @return int
     */
    public function rejectedProviderNotifications()
    {
        $zahtevM = new ZahtevModel();
        return $zahtevM->where('idPruzaoca',$this->idKorisnika)->where('stanje',7)->countAllResults();
    }

    /**
     * Funkcija koja dohvata broj zatrazenih konverzija profila iz korisnika u pruzaoca
     * 
     * @return int
     */
    public function accountNotifications()
    {
        $korisnikM = new KorisnikModel();
        return $korisnikM->where('pruzalac',2)->countAllResults();
    }

    /**
     * Funkcija koja proverava da li korisnik ima obavestenja
     * 
     * @return boolean
     */
    public function hasNotifications()
    {
        $res = $this->requestNotifications() + $this->reviewNotifications() + $this->rejectedNotifications();
        if($this->role()=='provider')
        {
            $res = $res + $this->incomingNotifications() + $this->acceptedNotifications() + $this->rejectedProviderNotifications();
        }
        if($this->role()=='admin')
        {
            $res = $res + $this->accountNotifications();
        }

        return $res!=0;
    }

    /**
     * Funkcija koja izracunava prosecnu ocenu pruzaoca
     * 
     * @return double
     */
    public function rating()
    {
        $zahtevM = new ZahtevModel();
        return doubleval($zahtevM->selectAvg('ocena')->where('idPruzaoca',$this->idKorisnika)->first()->ocena);
    }

    /**
     * Funkcija koja proverava zauzetost pruzaoca u datom periodu
     * 
     * @param string $tFrom vreme pocetka
     * @param string $tTo vreme kraja
     * @param string $dFrom datum pocetka
     * @param string $dTo datum kraja
     * 
     * @return bool
     */
    public function available($tFrom,$tTo,$dFrom,$dTo)
    {
        if(substr_count($dFrom,'-')!=2 || substr_count($dTo,'-')!=2)
        {
            return false;
        }
        $this->linkManuelnoZauzetiTermini();
        $availArr = $this->shortTermini();
        try
        {
            $tFrom = intval(explode(":",$tFrom)[0]*60) + intval(explode(":",$tFrom)[1]);
            $tTo = intval(explode(":",$tTo)[0]*60) + intval(explode(":",$tTo)[1]);

            $begin = new DateTime($dFrom);
            $end = new DateTime($dTo);
        }
        catch (Error  $ex)
        {
            return false;
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

    /**
     * Funkcija koja dohvata datum, vreme pocetka i vreme kraja termina
     * 
     * @return stdClass[]
     */
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

    /**
     * Funkcija koja proverava da li se dati vremenski interval preklapa sa nekim od termina datog korisnika
     * 
     * @param int $start pocetak intervala UNIXTS
     * @param int $end kraj intervala UNIXTS
     * 
     * @return bool
     */
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
            if(($ts+($slot->trajanje*60)) > $start and $end > $ts)
            {
                return true;
            }
        }
        return false;
    }

    /**
     * Funkcija koja dohvata sve recenzije
     * 
     * @return ZahtevModel[]
     */
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

    /**
     * Funkcija koja dohvata sve pruzaoce usluga za datu kategoriju
     * 
     * @param int $cat id kategorije, dohvata sve pruzaoce ukoliko je null ili -1
     * 
     * @return KorisnikModel
     */
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

    /**
     * Funkcija koja dohvata sve zahteve koje je korisnik uputio a da su u datom stanju
     * 
     * @param int $st trazeno stanje zahteva
     * 
     * @return ZahtevModel[]
     */
    public function getRequestsUser($st)
    {
        $zahtevM = new ZahtevModel();
        $requests = $zahtevM->where('idKorisnika',$this->idKorisnika)->where('stanje', $st)->orderBy('idZahteva','DESC')->findAll();

        foreach ($requests as $request) {
            $request->linkTermini();
            $request->linkPruzalac();
            $request->linkKorisnik();
            $request->linkKategorijaPruzaoca();
        }

        return $requests;
    }

    /**
     * Funkcija koja dohvata sve zahteve koji su upuceni pruzaocu a da su u datom stanju
     * 
     * @param int $st trazeno stanje zahteva
     * 
     * @return ZahtevModel[]
     */
    public function getRequestsProvider($st)
    {
        $zahtevM = new ZahtevModel();
        $requests = $zahtevM->where('idPruzaoca',$this->idKorisnika)->where('stanje', $st)->orderBy('idZahteva','DESC')->findAll();
        foreach ($requests as $request) {
            $request->linkTermini();
            $request->linkPruzalac();
            $request->linkKorisnik();
            $request->linkKategorijaPruzaoca();
        }
        return $requests;
    }

    /**
     * Funkcija koja pronalazi korisnika po njegovom identifikatoru
     * 
     * @param int $id identifikator
     * 
     * @return KorisnikModel
     */
    public static function findById($id)
    {
        $korisnikM = new KorisnikModel();
        return $korisnikM->find($id);
    }
}