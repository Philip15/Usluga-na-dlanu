<?php

namespace App\Controllers;

use App\Libraries\RequestInfoLib;
use App\Models\TerminModel;

use function PHPUnit\Framework\returnSelf;

class ProviderController extends BaseController
{
    public function timetable()
    {
        $user = session('user');
        $data['calendarid']=$user->idKorisnika;
        $data['calendaranon']='false';
        $data['calendarfree']='newReservedSlot';
        $data['calendarbusy']='slotInfo';
        
        return view('timetable',$data);
    }

    public function AJAXGetSlotInfo()
    {
        $slotId=$this->request->getGet('slot');
        if($slotId==null)
        {
            //invalid request, fail silently
            return;
        }
        $slot = TerminModel::findById($slotId);
        if($slot==null || $slot->idPruzaoca!=session('user')->idKorisnika)
        {
            //invalid request, fail silently
            return;
        }
        return RequestInfoLib::slotInfo($slot);
    }

    public function OPReserveTime()
    {
        date_default_timezone_set('Europe/Belgrade');
        $data['trajanje']=intval($this->request->getPost('duration'));
        $data['datumVremePocetka']=intval($this->request->getPost('startTime'));
        $data['idPruzaoca']=intval(session('user')->idKorisnika);
        
        if($data['trajanje']==null || $data['datumVremePocetka']==null || session('user')->role()!='provider')
        {
            //invalid request, fail silently
            return redirect()->to(base_url('ProviderController/timetable'));
        }
        if($data['trajanje']<30 || $data['trajanje']%30!=0 || session('user')->overlap($data['datumVremePocetka'],$data['datumVremePocetka']+$data['trajanje']*60))
        {
            //invalid request, fail silently
            return redirect()->to(base_url('ProviderController/timetable'));
        }
        $data['datumVremePocetka']=date('Y-m-d H:i:s',$data['datumVremePocetka']);
        $TerminM = new TerminModel();
        $TerminM->insert($data);
        return redirect()->to(base_url('ProviderController/timetable'));
    }

    public function OPFreeTime()
    {
        $id=$this->request->getGet('id');
        if($id==null)
        {
            //invalid request, fail silently
            return;
        }
        $slot = TerminModel::findById($id);
        if($slot==null || $slot->idPruzaoca!=session('user')->idKorisnika || $slot->idZahteva!=null)
        {
            //invalid request, fail silently
            return;
        }
        $TerminM = new TerminModel();
        $TerminM->delete($id);
        return redirect()->to(base_url('ProviderController/timetable'));
    }
}