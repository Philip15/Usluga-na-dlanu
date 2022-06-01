<?php
/**
  * @author Lazar Premović  2019/0091
  */

namespace App\Libraries;

use DateInterval;
use DateTime;

class RequestInfoLib
{
    public static function slotInfo($slot,$provider,$requests)
    {
        $reserved=$slot->idZahteva==null;
        $res ='<div class="row">';
        if($reserved)
        {
            $res.=self::infoLine(lang('App.type'),lang('App.manuallyReservedSlot'));
            $res.=self::infoLine(lang('App.date'),date('d/m/Y',strtotime($slot->datumVremePocetka)));
            $res.=self::infoLine(lang('App.time'),self::timeSpan($slot->datumVremePocetka,$slot->trajanje));
        }
        else
        {
            $res.=self::infoLine(lang('App.date'),date('d/m/Y',strtotime($slot->datumVremePocetka)));
            $res.=self::infoLine(lang('App.time'),self::timeSpan($slot->datumVremePocetka,$slot->trajanje));
            $slot->linkZahtev();
            $slot->zahtev->linkKorisnik();
            $slot->zahtev->linkPruzalac();
            $slot->zahtev->linkKategorijaPruzaoca();
            if($provider)
            {
                $res.=self::infoLine(lang('App.client'),$slot->zahtev->korisnik->ime.' '.$slot->zahtev->korisnik->prezime.' ('.$slot->zahtev->korisnik->email.')');
            }
            else
            {
                $res.=self::infoLine(lang('App.provider'),$slot->zahtev->pruzalac->ime.' '.$slot->zahtev->pruzalac->prezime.' ('.$slot->zahtev->pruzalac->email.') '.ucfirst($slot->zahtev->kategorija->naziv));
            }
            $res.=self::infoLine(lang('App.state'),$slot->zahtev->descriptiveState());
            $res.=self::infoLine(lang('App.desc'),$slot->zahtev->opis);
            $res.=self::infoLine(lang('App.urgent'),$slot->zahtev->hitno?lang('App.yes'):lang('App.no'));
            $res.=self::infoLine(lang('App.price'),$slot->zahtev->cena);
            $res.=self::infoLine(lang('App.comment'),$slot->zahtev->komentar);
        }
        $res.='</div>';
        if($requests)
        {
            $res.=self::requestButtons($slot->zahtev->idZahteva,$slot->zahtev->stanje,$provider);
        }
        else
        {
            if($reserved)
            {
                $res.=self::deleteButton($slot->idTermina);
            }
            else
            {
                $res.=self::seeMoreButton($slot->idTermina);
            }
        }
        return $res;
    }

    public static function requestInfo($zahtev,$provider)
    {
        $res ='<div class="row">';
        $zahtev->linkKorisnik();
        $zahtev->linkPruzalac();
        $zahtev->linkKategorijaPruzaoca();
        if($provider)
        {
            $res.=self::infoLine(lang('App.client'),$zahtev->korisnik->ime.' '.$zahtev->korisnik->prezime.' ('.$zahtev->korisnik->email.')');
        }
        else
        {
            $res.=self::infoLine(lang('App.provider'),$zahtev->pruzalac->ime.' '.$zahtev->pruzalac->prezime.' ('.$zahtev->pruzalac->email.') '.ucfirst($zahtev->kategorija->naziv));
        }
        $res.=self::infoLine(lang('App.state'),$zahtev->descriptiveState());
        $res.=self::infoLine(lang('App.desc'),$zahtev->opis);
        $res.=self::infoLine(lang('App.urgent'),$zahtev->hitno?lang('App.yes'):lang('App.no'));
        $res.=self::infoLine(lang('App.price'),$zahtev->cena);
        $res.='</div>';
        $res.=self::requestButtons($zahtev->idZahteva,$zahtev->stanje,$provider);
        return $res;
    }

    public static function timeSpan($start,$length)
    {
        $startDate=new DateTime($start);
        $startTime=$startDate->format(('G:i'));
        $startDate->add(new DateInterval('PT' . $length . 'M'));
        $endTime=$startDate->format(('G:i'));
        $len=$length/60;
        return $startTime.' - '.$endTime.'  ('.$len.'h)';
    }

    public static function infoLine($label,$text)
    {
        if($label==null || $text==null){return '';}
        return '<p class="form-label fs-6 fw-bold">'.esc($label).': '.esc($text).'</p>';
    }

    public static function seeMoreButton($id)
    {
        return '<div class="d-flex mt-3 justify-content-end">
                    <a class="btn btn-primary mx-1" href="'.esc(base_url('ProviderController/requests#'.$id)).'">'.lang('App.seeMore').'</a>
                </div>';
    }

    public static function deleteButton($id)
    {
        return '<div class="d-flex mt-3 justify-content-end">
                    <a class="btn btn-danger mx-1" href="'.esc(base_url('ProviderController/OPFreeTime?id='.$id)).'">'.lang('App.removeSlot').'</a>
                </div>';
    }

    public static function requestButtons($id,$state,$provider)
    {
        if(!$provider && $state==2)
        {
            return '<div class="d-flex mt-3 justify-content-end">
                        <a class="btn btn-success mx-1" href="'.esc(base_url('UserController/OPAcceptRequest?id='.$id)).'">'.lang('App.approve').'</a>
                        <a class="btn btn-danger mx-1" href="'.esc(base_url('UserController/OPRejectRequest?id='.$id)).'">'.lang('App.deny').'</a>
                    </div>';
        }
        if(!$provider && $state==6)
        {
            return '<div class="d-flex mt-3 justify-content-end">
                        <a class="btn btn-primary mx-1" href="'.esc(base_url('UserController/OPCheckRejection?id='.$id)).'">'.lang('App.ok').'</a>
                    </div>';
        }
        if($provider && $state==1)
        {   
            return '<div class="d-flex mt-3 justify-content-end">
                        <button type="button" class="btn btn-success mx-1" data-bs-toggle="modal" data-bs-target="#newOfferModal"  onclick="document.getElementById(\'idZ\').value='.$id.'">'.lang('App.approve').'</button>
                        <a class="btn btn-danger mx-1" href="'.esc(base_url('ProviderController/OPRejectRequest?id='.$id)).'">'.lang('App.deny').'</a>
                    </div>';
        }
        if($provider && $state==3)
        {
            return '<div class="d-flex mt-3 justify-content-end">
                        <a class="btn btn-success mx-1" href="'.esc(base_url('ProviderController/OPRealizeRequest?id='.$id)).'">'.lang('App.realise').'</a>
                        <a class="btn btn-danger mx-1" href="'.esc(base_url('ProviderController/OPRejectRequest?id='.$id)).'">'.lang('App.deny').'</a>
                    </div>';
        }
        if($provider && $state==7)
        {
            return '<div class="d-flex mt-3 justify-content-end">
                        <a class="btn btn-primary mx-1" href="'.esc(base_url('ProviderController/OPCheckRejection?id='.$id)).'">'.lang('App.ok').'</a>
                    </div>';
        }
        return '';
    }
}