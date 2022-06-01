<?php
/**
  * @author Lazar Premović  2019/0091
  * @author Jana Pašajlić   2019/0132
  * @author Filip Janjić    2019/0116
  */

namespace App\Controllers;

use App\Models\KategorijaModel;
use App\Models\KorisnikModel;
use App\Models\ZahtevModel;
use App\Models\TerminModel;

class UserController extends BaseController
{
    public function OPlogout() 
    {
        $this->session->destroy();
        return self::safeRedirectBack();
    }

    public function editProfile()
    {
        $data['categories'] = KategorijaModel::getAll();
        $data['jsinit'] = 'editProfile';
        $korisnikModel = new KorisnikModel();
        $korisnik = $korisnikModel->find(session('user')->idKorisnika);
        $korisnik->linkKategorija();
        $this->session->set('user', $korisnik);
        return view('editprofile', $data);
    }

    public function OPsaveChanges()
    {
        $korisnikPodaci = [
            'ime' =>  $this->request->getVar('ime'),
            'prezime' =>  $this->request->getVar('prezime'),
            'korisnickoIme' => $this->request->getVar('username'),
            'email' =>  $this->request->getVar('email'),
            'opis' => $this->request->getVar('dodatneInformacije')
        ];
        if(session('user')->role() == "provider")
        {
            $korisnikPodaci['adresa']=$this->request->getVar('adresa');
            $korisnikPodaci['idKategorije']=$this->request->getVar('kategorija');
            $korisnikPodaci['lat']=$this->request->getVar('lat');
            $korisnikPodaci['lon']=$this->request->getVar('lon');
        }
        
        $korisnikModel = new KorisnikModel();
        if (!$this->validate(['email'=>'required', 'ime'=>'required', 'prezime'=> 'required', 'username'=>'required']))
        {
            $this->session->setFlashdata('podaci', $korisnikPodaci);
            $this->session->setFlashdata('errorText', lang('App.errRequiredFields'));
            return redirect()->to(site_url('UserController/editProfile'));
        }

        if (!$this->validate(['email'=>'valid_email'])) 
        {
            $this->session->setFlashdata('podaci', $korisnikPodaci);
            $this->session->setFlashdata('errorText', lang('App.errInvalidEmail'));
            return redirect()->to(site_url('UserController/editProfile'));
        }
        $korisnikModel = new KorisnikModel();
        $korisnik = $korisnikModel->where('email', $korisnikPodaci['email'])->where('idKorisnika !=',session('user')->idKorisnika)->findAll();
        if ($korisnik != null) {
            $this->session->setFlashdata('podaci', $korisnikPodaci);
            $this->session->setFlashdata('errorText', lang('App.errEmailAlreadyExists'));
            return redirect()->to(site_url('UserController/editProfile'));
        }
        $korisnik = $korisnikModel->where('korisnickoIme', $korisnikPodaci['korisnickoIme'])->where('idKorisnika !=',session('user')->idKorisnika)->findAll();
        if ($korisnik != null) 
        {
            $this->session->setFlashdata('podaci', $korisnikPodaci);
            $this->session->setFlashdata('errorText', lang('App.errUserAlreadyExists'));
            return redirect()->to(site_url('UserController/editProfile'));
        }

        if(empty($korisnikPodaci['opis'])){$korisnikPodaci['opis']=null;}
        if(session('user')->role() == "provider")
        {
            if (!$this->validate(['kategorija'=>'required', 'adresa'=>'required','lat'=>'required','lon'=>'required']) || !$this->verifyLatLon($korisnikPodaci['lat'],$korisnikPodaci['lon']) || KategorijaModel::findById($korisnikPodaci['idKategorije'])==null)
            {
                $this->session->setFlashdata('podaci', $korisnikPodaci);
                $this->session->setFlashdata('errorText', lang('App.errEmptyFieldsConversion'));
                return redirect()->to(site_url('UserController/editProfile'));
            }
        }

        $korisnikModel->update(session('user')->idKorisnika, $korisnikPodaci);
        $this->session->setFlashdata('alertErrorText', lang('App.successfulProfileUpdate'));
        return redirect()->to(site_url('UserController/editProfile'));
    }

    public function OPchangeProfilePicture()
    {
        $code = $this->verifyFile('profilePicture');
        if ($code==0) 
        {
            $img = file_get_contents($_FILES['profilePicture']['tmp_name']);
            $korisnikModel = new KorisnikModel();
            $korisnikModel->update(session('user')->idKorisnika, ['profilnaSlika' => $img]);
            $korisnik = $korisnikModel->find(session('user')->idKorisnika);
            $this->session->set('user', $korisnik);
            $this->session->setFlashdata('alertErrorText', lang('App.successfulPictureChange'));
        }
        elseif ($code==-1)
        {
            $this->session->setFlashdata('alertErrorText', lang('App.errNoPicture'));
        }
        elseif ($code==-2)
        {
            $this->session->setFlashdata('alertErrorText', lang('App.errTooBig'));
        }
        elseif ($code==-3)
        {
            $this->session->setFlashdata('alertErrorText', lang('App.errWrongType'));
        }
        return redirect()->to(site_url('UserController/editProfile'));
    }

    public function AJAXpicturePreview()
    {
        $code = $this->verifyFile('profilePicture');
        if ($code==0) 
        {
            $img = file_get_contents($_FILES['profilePicture']['tmp_name']);
            return '<img src="data:image/jpeg;base64,'.base64_encode($img).'" width="300" height="300" class="rounded-circle border border-success border-5">';
        }
        elseif($code==-1)
        {
            return lang('App.errNoPicture');
        }
        elseif($code==-2)
        {
            return lang('App.errTooBig');
        }
        elseif($code==-3)
        {
            return lang('App.errWrongType');
        }
    }

    private function verifyFile($key)
    {
        //actuallyUploaded
        //size:<3MB
        //type:image
        if($_FILES[$key]['tmp_name'] == "")
        {
            return -1;
        }
        if($_FILES[$key]['size'] > 3145728)
        {
            return -2;
        }
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $type=finfo_file($finfo, $_FILES[$key]['tmp_name']);
        finfo_close($finfo);
        if(!str_starts_with($type,'image/'))
        {   
            return -3;
        }
        return 0;
    }

    public function OPconvertProfile()
    {
        $korisnikPodaci = [
            'idKategorije' => $this->request->getVar('kategorija'),
            'adresa' => $this->request->getVar('adresaPoslovanja'),
            'lat' => $this->request->getVar('lat'),
            'lon' => $this->request->getVar('lon'),
            'pruzalac' => 2
        ];

        if (!$this->validate(['kategorija'=>'required', 'adresaPoslovanja'=>'required','lat'=>'required','lon'=>'required']) || !$this->verifyLatLon($korisnikPodaci['lat'],$korisnikPodaci['lon']) || KategorijaModel::findById($korisnikPodaci['idKategorije'])==null)
        {
            $this->session->setFlashdata('podaciKonverzija', $korisnikPodaci);
            $this->session->setFlashdata('errorTextConversion', lang('App.errEmptyFieldsConversion'));
            return redirect()->to(site_url('UserController/editProfile'));
        }
        $korisnikModel = new KorisnikModel();
        $korisnikModel->update(session('user')->idKorisnika, $korisnikPodaci);
        $this->session->setFlashdata('alertErrorText', lang('App.successfulConversion'));
        return redirect()->to(site_url('UserController/editProfile'));
    }

    private function verifyLatLon($lat,$lon)
    {
        if(is_numeric($lat) && is_numeric($lon))
        {
            $lat=floatval($lat);
            $lat=floatval($lon);
            if($lat >=-90 && $lat<=90 && $lon >=-180 && $lon<=180)
            {
                return true;
            }
        }
        return false;
    }

    public function OPupdatePassword()
    {
        $korisnikPodaci = [
            'staraLozinka' => $this->request->getVar('oldPassword'),
            'lozinka' => $this->request->getVar('newPassword'),
            'lozinka2' => $this->request->getVar('newPasswordAgain')
        ];
        if (!$this->validate(['oldPassword' => 'required', 'newPassword'=>'required', 'newPasswordAgain'=>'required']))
        {
            $this->session->setFlashdata('podacilozinka', $korisnikPodaci);
            $this->session->setFlashdata('errorTextNewPassword', lang('App.errFieldEmpty'));
            return redirect()->to(site_url('UserController/editProfile'));
        }
        if (!password_verify($korisnikPodaci['staraLozinka'], session('user')->lozinka))
        {
            $this->session->setFlashdata('podacilozinka', $korisnikPodaci);
            $this->session->setFlashdata('errorTextNewPassword', lang('App.errOldPassword'));
            return redirect()->to(site_url('UserController/editProfile'));
        }
        if ($korisnikPodaci['lozinka'] != $korisnikPodaci['lozinka2']) 
        {
            $this->session->setFlashdata('podacilozinka', $korisnikPodaci);
            $this->session->setFlashdata('errorTextNewPassword', lang('App.errPasswordConfirmation'));
            return redirect()->to(site_url('UserController/editProfile'));
        }
        unset($korisnikPodaci['staraLozinka']);
        unset($korisnikPodaci['lozinka2']);
        $korisnikPodaci['lozinka'] = password_hash($korisnikPodaci['lozinka'], PASSWORD_DEFAULT);

        $korisnikModel = new KorisnikModel();
        $korisnikModel->update(session('user')->idKorisnika, $korisnikPodaci);
        $this->session->setFlashdata('alertErrorText', lang('App.successfulPasswordChange'));
        return redirect()->to(site_url('UserController/editProfile'));
    }

    public function reviews()
    {
        $korisnik = $this->session->get('user');
        $data['jsinit']='reviews';
        $data['reviews'] = $korisnik->getReviews();
        return view('reviews',$data);
    }   
    
    public function OPpostReview()                                                                  //( prelazak 4 -> 5 )
    {
        $id = $this->request->getPost('id');
        $rating = $this->request->getPost('rating');
        $comment = $this->request->getPost('comment');
        if($id === null || $rating === null || $comment === null)
        {
            //Invalid request, fail silently
            return redirect()->to(base_url('UserController/reviews'));
        }
        if(!is_numeric($id) || !is_numeric($rating))
        {
            //Invalid request, fail silently
            return redirect()->to(base_url('UserController/reviews'));
        }
        $zahtev = ZahtevModel::findById($id);
        if($zahtev==null || $zahtev->idKorisnika!=session('user')->idKorisnika || $zahtev->stanje != 4)
        {
            //Invalid request, fail silently
            return redirect()->to(base_url('UserController/reviews'));
        }
        if($rating>5)
        {
            //Invalid request, fail silently
            return redirect()->to(base_url('UserController/reviews'));
        }
        if($rating==0)
        {
            $this->session->setFlashdata('alertErrorText',lang('App.mandatoryRating'));
            return redirect()->to(base_url('UserController/reviews'));
        }
        if(empty($comment))
        {
            $comment=null;
        }
        $zahtevM = new ZahtevModel();
        $zahtevM->update($id,['stanje'=>5,'ocena'=>$rating,'recenzija'=>$comment]);
        
        $this->session->setFlashdata('alertErrorText',lang('App.tankYouReview'));
        return redirect()->to(base_url('UserController/reviews'));
    }

    public function OPremoveReview()
    {
        $id = $this->request->getGet('id');
        if($id === null)
        {
            //Invalid request, fail silently
            return redirect()->to(base_url('UserController/reviews'));
        }
        if(!is_numeric($id))
        {
            //Invalid request, fail silently
            return redirect()->to(base_url('UserController/reviews'));
        }
        $zahtev = ZahtevModel::findById($id);
        if($zahtev==null || $zahtev->idKorisnika!=session('user')->idKorisnika || $zahtev->stanje != 4)
        {
            //Invalid request, fail silently
            return redirect()->to(base_url('UserController/reviews'));
        }
        $zahtevM = new ZahtevModel();
        $zahtevM->update($id,['stanje'=>5]);
        
        return redirect()->to(base_url('UserController/reviews'));
    }

    public function requests()
    {
        // prikaz view-a user-requests.php
        $korisnik = $this->session->get('user');
        $data['requests'][0] = $korisnik->getRequestsUser(1);
        $data['requests'][1] = $korisnik->getRequestsUser(2);
        $data['requests'][2] = $korisnik->getRequestsUser(3);
        $data['requests'][3] = $korisnik->getRequestsUser(6);
        $data['provider'] = false;
        return view('requests',$data);
    }

    public function OPCreateRequest()
    {
        // kreiranje zahteva za uslugom od strane korisnika                         ( kreiranje u stanju 1 )
        $newRequest = new ZahtevModel();

        $data = [
            'idKorisnika' => (int)session('user')->idKorisnika,
            'idPruzaoca' => (int)$this->request->getVar('providerId'),
            'stanje'      => 1,
            'opis'        => $this->request->getVar('requestDesc'),
            'hitno'       => $this->request->getVar('urgentBox') == "on",
        ];
        $dataT['trajanje']=intval($this->request->getPost('duration'));
        $dataT['datumVremePocetka']=intval($this->request->getPost('startTime'));
        $dataT['idPruzaoca'] = (int)$this->request->getVar('providerId');

        if(!$this->validate(['providerId'=>'required']))
        {
            return self::safeRedirectBack();
        }
        if(!$this->validate(['requestDesc'=>'required']))
        {
            $this->session->setFlashdata('errorTextCreate', lang('App.errDesc'));
            $this->session->setFlashdata('errorData', $dataT);
            return redirect()->to(base_url('profile?id='.$data['idPruzaoca']));
        }
        $provider = KorisnikModel::findById($data['idPruzaoca']);
        if($provider==null || $provider->pruzalac!=1)
        {
            //invalid request, fail silently
            return redirect()->to(base_url('profile?id='.$data['idPruzaoca']));
        }
        if($provider->idKorisnika==session('user')->idKorisnika)
        {
            $this->session->setFlashdata('errorTextCreate', lang('App.errSelfRequest'));
            $this->session->setFlashdata('errorData', $dataT);
            return redirect()->to(base_url('profile?id='.$data['idPruzaoca']));
        }

        date_default_timezone_set('Europe/Belgrade');

        if($dataT['trajanje']==null || $dataT['datumVremePocetka']==null)
        {
            return redirect()->to(base_url('profile?id='.$data['idPruzaoca']));
        }
        if($dataT['trajanje']<30 || $dataT['trajanje']%30!=0 || $provider->overlap($dataT['datumVremePocetka'],$dataT['datumVremePocetka']+$dataT['trajanje']*60))
        {
            return redirect()->to(base_url('profile?id='.$data['idPruzaoca']));
        }

        $newRequest->insert($data);
        $idZahteva = $newRequest->getInsertID();
        
        $dataT['datumVremePocetka']=date('Y-m-d H:i:s',$dataT['datumVremePocetka']);
        $dataT['idZahteva'] = $idZahteva;
        $TerminM = new TerminModel();
        $TerminM->insert($dataT);
        $this->session->setFlashdata('alertErrorText', lang('App.successfulRequest'));
        return redirect()->to(base_url('profile?id='.$data['idPruzaoca']));     
    }

    public function OPAcceptRequest()
    {
        // prihvatanje ponude                                                       ( prelazak 2 -> 3 )
        $id = $this->request->getGet('id');
        $zahtev = ZahtevModel::findById($id);
        if($zahtev==null || $zahtev->stanje!=2 || $zahtev->idKorisnika!=session('user')->idKorisnika)
        {
            //invalid request, fail silently
            return redirect()->to(base_url('UserController/requests'));
        }

        $zahtevModel = new ZahtevModel();
        $zahtevModel->update($id, ['stanje' => 3]);
        return redirect()->to(base_url('UserController/requests'));
    }

    public function OPRejectRequest()
    {
        // odbijanje zahteva u bilo kom trenutku                                    ( prelazak 2 -> 7 )
        $id = $this->request->getGet('id');
        $zahtev = ZahtevModel::findById($id);
        if($zahtev==null || $zahtev->stanje!=2 || $zahtev->idKorisnika!=session('user')->idKorisnika)
        {
            //invalid request, fail silently
            return redirect()->to(base_url('UserController/requests'));
        }

        $zahtevModel = new ZahtevModel();
        $zahtevModel->update($id, ['stanje' => 7]);
        $zahtev->linkTermini();
        $terminM = new TerminModel();
        $terminM->where('idZahteva', $id)->delete();
        return redirect()->to(base_url('UserController/requests'));
    }

    public function OPCheckRejection()
    {
        // oznacavanje notifikacije odbijenog zahteva kao pregledane                ( prelazak 6 -> 8 )
        $id = $this->request->getGet('id');
        $zahtev = ZahtevModel::findById($id);
        if($zahtev==null || $zahtev->stanje!=6 || $zahtev->idKorisnika!=session('user')->idKorisnika)
        {
            //invalid request, fail silently
            return redirect()->to(base_url('UserController/requests'));
        }

        $zahtevModel = new ZahtevModel();
        $zahtevModel->update($id, ['stanje' => 8]);
        return redirect()->to(base_url('UserController/requests'));
    }

}