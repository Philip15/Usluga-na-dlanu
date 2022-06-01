<?php
 /**
  * @author Lazar Premović  2019/0091
  * @author Jana Pašajlić   2019/0132
  */

namespace App\Controllers;

use App\Models\KategorijaModel;
use App\Models\KorisnikModel;

class AdminController extends BaseController
{
    public function categories()
    {   
        $data['categories']=KategorijaModel::getAll();
        return view('categories', $data);
    }

    public function OPAddCategory()
    {
        if (!$this->validate(['category'=>'required'])) 
        {
            $this->session->setFlashdata('alertErrorText', lang('App.errEmptyCategory'));
            return self::safeRedirectBack();
        }
        $kategorijaNaziv = strtolower($this->request->getVar('category'));
        if(KategorijaModel::get($kategorijaNaziv)!==null)
        {
            $this->session->setFlashdata('alertErrorText', lang('App.errCategoryAlreadyExists'));
            return self::safeRedirectBack();
        }
        $kategorijaModel = new KategorijaModel();
        $kategorijaModel->save([
            'naziv' => $kategorijaNaziv
        ]);

        return redirect()->to(base_url('AdminController/categories'));
    }

    public function OPRemoveCategory()
    {
        $kategorijaModel = new KategorijaModel();
        $korisnikModel = new KorisnikModel();
        $pruzaoci = $korisnikModel::getProviders($this->request->getVar('id'));
        if($pruzaoci == null) 
        {
            $kategorijaModel->delete($this->request->getVar('id'));
        }
        else 
        {
            $this->session->setFlashdata('alertErrorText', lang('App.errCategoryHasProviders'));
            return redirect()->to(base_url('AdminController/categories'));
        }
        
        return redirect()->to(base_url('AdminController/categories'));
    }

    public function accountRequests() 
    {
        $korisnikModel = new KorisnikModel();
        $requests = $korisnikModel->where('pruzalac', 2)->findAll();
        foreach ($requests as $req) 
        {
            $req->linkKategorija();
        }
        $data['requests'] = $requests;
        return view('accountrequests', $data);
    }

    public function OPApproveRequest()
    {
        $korisnikModel = new KorisnikModel();
        $korisnikModel->update($this->request->getVar('id'), ['pruzalac' => 1]);
        return redirect()->to(base_url('AdminController/accountrequests'));
    }

    public function OPDenyRequest()
    {
        $korisnikModel = new KorisnikModel();
        $korisnikModel->update($this->request->getVar('id'), ['pruzalac' => 0]);
        return redirect()->to(base_url('AdminController/accountrequests'));
    }
}