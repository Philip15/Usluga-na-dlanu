<?php
/**
  * @author Lazar Premović  2019/0091
  * @author Filip Janjić    2019/0116
  */

namespace App\Controllers;

use App\Models\ZahtevModel;
use App\Libraries\RequestInfoLib;
use App\Models\TerminModel;

/**
 * ProviderController - kontroler za pruzaoca
 */
class ProviderController extends BaseController
{
    /**
     * Prikaz dolaznih zahteva
     * 
     * @return Response
     */
    public function requests()
    {
        // prikaz view-a provider-requests.php
        $korisnik = $this->session->get('user');
        $data['jsinit']='request';
        $data['requests'][0] = $korisnik->getRequestsProvider(1);
        $data['requests'][1] = $korisnik->getRequestsProvider(2);
        $data['requests'][2] = $korisnik->getRequestsProvider(3);
        $data['requests'][3] = $korisnik->getRequestsProvider(7);
        $data['provider'] = true;
        return view('requests',$data);
    }

    /**
     * Funkcija za slanje ponude na pristigli zahtev
     * 
     * @postVar int idZ id zahteva
     * @postVar int priceVal predlozena cena zahteva
     * @postVar string offerDesc opcioni opis
     * 
     * @return Response
     */
    public function OPCreateOffer()
    {
        // kreiranje ponude iz primljenog zahteva                                       ( prelazak 1 -> 2 )

        $id = $this->request->getVar('idZ');
        $zahtev = ZahtevModel::findById($id);
        if($zahtev==null || $zahtev->stanje!=1 || $zahtev->idPruzaoca!=session('user')->idKorisnika)
        {
            //invalid request, fail silently
            return redirect()->to(base_url('ProviderController/requests'));
        }

        $cena = intval($this->request->getVar('priceVal'));
        $opis = $this->request->getVar('offerDesc');
        if($cena == null || !is_numeric($this->request->getVar('priceVal')))
        {
            $this->session->setFlashdata('errorTextPrice', lang('App.errMsgPrice'));
            $this->session->setFlashdata('errorId', $id);
            return redirect()->to(base_url('ProviderController/requests'));
        }  
        else
        {
            $zahtevModel = new ZahtevModel();
            $zahtevModel->update($id, ['stanje' => 2, 'cena' => $cena, 'komentar' => $opis]);
            return redirect()->to(base_url('ProviderController/requests'));
        }      

    }

    /**
     * Funckija za odbijanje zahteva
     * 
     * @getParam int id id zahteva
     * 
     * @return Response
     */
    public function OPRejectRequest()
    {
        // odbijanje zahteva u bilo kom trenutku                                        ( prelazak 1 -> 6, 3 -> 6 )
        $id = $this->request->getGet('id');
        $zahtev = ZahtevModel::findById($id);
        if($zahtev==null || ($zahtev->stanje!=1 && $zahtev->stanje!=3) || $zahtev->idPruzaoca!=session('user')->idKorisnika)
        {
            //invalid request, fail silently
            return redirect()->to(base_url('ProviderController/requests'));
        }

        $zahtevModel = new ZahtevModel();
        $zahtevModel->update($id, ['stanje' => 6]);
        $terminM = new TerminModel();
        $terminM->where('idZahteva', $id)->delete();
        return redirect()->to(base_url('ProviderController/requests'));
    }

    /**
     * Funckija za realizovanje zahteva
     * 
     * @getParam int id id zahteva
     * 
     * @return Response
     */
    public function OPRealizeRequest()
    {
        // oznacavanje zahteva kao realizovanog, slanje na recenziju                    ( prelazak 3 -> 4 )
        $id = $this->request->getGet('id');
        $zahtev = ZahtevModel::findById($id);
        if($zahtev==null || $zahtev->stanje!=3 || $zahtev->idPruzaoca!=session('user')->idKorisnika)
        {
            //invalid request, fail silently
            return redirect()->to(base_url('ProviderController/requests'));
        }

        $zahtevModel = new ZahtevModel();
        $zahtevModel->update($id, ['stanje' => 4]);
        return redirect()->to(base_url('ProviderController/requests'));
    }

    /**
     * Funckija za potvrdjivanje odbijenog zahteva
     * 
     * @getParam int id id zahteva
     * 
     * @return Response
     */
    public function OPCheckRejection()
    {
        // oznacavanje notifikacije odbijenog zahteva kao pregledane                ( prelazak 7 -> 8 )
        $id = $this->request->getGet('id');
        $zahtev = ZahtevModel::findById($id);
        if($zahtev==null || $zahtev->stanje!=7 || $zahtev->idPruzaoca!=session('user')->idKorisnika)
        {
            //invalid request, fail silently
            return redirect()->to(base_url('ProviderController/requests'));
        }

        $zahtevModel = new ZahtevModel();
        $zahtevModel->update($id, ['stanje' => 8]);
        return redirect()->to(base_url('ProviderController/requests'));
    }

    /**
     * Prikaz kalendara pruzaoca
     * 
     * @return Response
     */
    public function timetable()
    {
        $user = session('user');
        $data['calendarid']=$user->idKorisnika;
        $data['calendaranon']='false';
        $data['calendarfree']='newReservedSlot';
        $data['calendarbusy']='slotInfo';
        
        return view('timetable',$data);
    }

    /**
     * Funckija za prikaz informacija o terminu
     * 
     * @getParam int slot id termina
     * 
     * @return Response
     */
    public function AJAXGetSlotInfo()
    {
        $slotId=$this->request->getGet('slot');
        if($slotId==null)
        {
            //invalid request, fail silently
            return $this->response->setStatusCode(400);
        }
        $slot = TerminModel::findById($slotId);
        if($slot==null || $slot->idPruzaoca!=session('user')->idKorisnika)
        {
            //invalid request, fail silently
            return $this->response->setStatusCode(400);
        }
        return RequestInfoLib::slotInfo($slot,true,false);
    }

    /**
     * Funckija za rucno zauzimanje termina
     * 
     * @postParam int duration trajanje termina
     * @postParam int startTime pocetak termina
     * 
     * @return Response
     */
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

    /**
     * Funckija za uklanajnje rucno zauzetog termina
     * 
     * @postParam int id id termina
     * 
     * @return Response
     */
    public function OPFreeTime()
    {
        $id=$this->request->getGet('id');
        if($id==null)
        {
            //invalid request, fail silently
            return $this->response->setStatusCode(400);
        }
        $slot = TerminModel::findById($id);
        if($slot==null || $slot->idPruzaoca!=session('user')->idKorisnika || $slot->idZahteva!=null)
        {
            //invalid request, fail silently
            return $this->response->setStatusCode(400);
        }
        $TerminM = new TerminModel();
        $TerminM->delete($id);
        return redirect()->to(base_url('ProviderController/timetable'));
    }
}