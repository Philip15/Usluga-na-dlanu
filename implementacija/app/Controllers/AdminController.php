<?php

namespace App\Controllers;

use App\Models\KategorijaModel;
use App\Models\KorisnikModel;

class AdminController extends BaseController
{
    public function categories()
    {   
        $kategorijaModel = new KategorijaModel();
        $data['categories'] = $kategorijaModel->findAll();
        //$data['categories'] = KategorijaModel::getAll();
        return view('categories', $data);
    }

    public function OPAddCategory()
    {
        $kategorijaModel = new KategorijaModel();
        $kategorijaNaziv = $this->request->getVar('category');
        $kategorijaModel->save([
            'naziv' => $kategorijaNaziv
        ]);

        return redirect()->to(base_url('AdminController/categories'));
    }

    public function OPRemoveCategory()
    {
       
        $kategorijaModel = new KategorijaModel();
        $korisnikModel = new KorisnikModel();
        $pruzaoci = $korisnikModel->where('idKategorije',$this->request->getVar('id'))->findAll();
        if($pruzaoci == null) {
            $kategorijaModel->delete($this->request->getVar('id'));
        }
        else {
            $this->session->setFlashdata('errorText', 'Ne možete ukloniti kategoriju koja ima pružaoce.');
            return redirect()->to(base_url('AdminController/categories'));
        }
        
        return redirect()->to(base_url('AdminController/categories'));
    }

    public function accountRequests() 
    {
        $korisnikModel = new KorisnikModel();
        $requests = $korisnikModel->where('pruzalac', 2)->findAll();
        foreach ($requests as $req) {
            $req->linkKategorija();
        }
        $data['requests'] = $requests;
        return view('accountrequests', $data);
    }

    public function OPApproveRequest()
    {
        $korisnikModel = new KorisnikModel();
        $korisnik = $korisnikModel->find($this->request->getVar('id'));
        $korisnikModel->update($korisnik->idKorisnika, ['pruzalac' => 1]);
        return redirect()->to(base_url('AdminController/accountrequests'));
    }

    public function OPDenyRequest()
    {
        $korisnikModel = new KorisnikModel();
        $korisnik = $korisnikModel->find($this->request->getVar('id'));
        $korisnikModel->update($korisnik->idKorisnika, ['pruzalac' => 0]);
        return redirect()->to(base_url('AdminController/accountrequests'));
    }
}