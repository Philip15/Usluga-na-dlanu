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
        $pruzaoci = $korisnikModel->where('idKategorije',$this->request->getVar('id'))->find();
        if($pruzaoci == null) {
            $kategorijaModel->delete($this->request->getVar('id'));
        }
        
        return redirect()->to(base_url('AdminController/categories'));
    }

    public function accountRequests() 
    {
        $korisnikModel = new KorisnikModel();
        $requests = $korisnikModel->where('pruzalac', 2)->findAll();
        $data['requests'] = $requests;
        $kategorijaModel = new KategorijaModel();
        foreach ($requests as $req) {
            $naziviKategorija[] = $kategorijaModel->find($req->idKategorije)->naziv;
        }
        $data['naziviKategorija'] = $naziviKategorija;
        return view('accountrequests', $data);
    }

    public function OPApproveRequest()
    {
        $korisnikModel = new KorisnikModel();
        $korisnik = $korisnikModel->where('korisnickoIme', $this->request->getVar('korisnickoime'));
        $korisnikModel->update($korisnik->idKorisnika, ['pruzalac' => 1]);
        return redirect()->to(base_url('AdminController/accountrequests'));
    }

    public function OPDenyRequest()
    {
        $korisnikModel = new KorisnikModel();
        $korisnik = $korisnikModel->where('korisnickoIme', $this->request->getVar('korisnickoime'));
        $korisnikModel->update($korisnik->idKorisnika, ['pruzalac' => 0]);
        return redirect()->to(base_url('AdminController/accountrequests'));
    }
}