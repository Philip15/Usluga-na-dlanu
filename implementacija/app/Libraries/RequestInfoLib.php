<?php

namespace App\Libraries;

use DateInterval;
use DateTime;

class RequestInfoLib
{
    public static function slotInfo($slot)
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
            $res.=self::infoLine(lang('App.client'),$slot->zahtev->korisnik->ime.' '.$slot->zahtev->korisnik->prezime.' ('.$slot->zahtev->korisnik->email.')');
            $res.=self::infoLine(lang('App.state'),$slot->zahtev->descriptiveState());
            $res.=self::infoLine(lang('App.desc'),$slot->zahtev->opis);
            $res.=self::infoLine(lang('App.urgent'),$slot->zahtev->hitno?lang('App.yes'):lang('App.no'));
            $res.=self::infoLine(lang('App.price'),$slot->zahtev->cena);
            $res.=self::infoLine(lang('App.comment'),$slot->zahtev->komentar);
        }
        $res.='</div>';
        if($reserved)
        {
            $res.=self::deleteButton($slot->idTermina);
        }
        else
        {
            $res.=self::seeMoreButton($slot->idTermina);
        }
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
}