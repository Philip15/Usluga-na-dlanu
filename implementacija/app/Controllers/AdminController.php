<?php
 /**
  * @author Lazar Premović  2019/0091
  * @author Jana Pašajlić   2019/0132
  */

namespace App\Controllers;

use App\Models\KategorijaModel;
use App\Models\KorisnikModel;

/**
 * AdminController - kontroler za administratora
 */
class AdminController extends BaseController
{
    /**
     * Prikaz liste kategorija
     * 
     * @return Response 
     */
    public function categories()
    {   
        $data['categories']=KategorijaModel::getAll();
        return view('categories', $data);
    }

    /**
     * Funkcija za dodavanje kategorije
     * 
     * @postParam string category kategorija
     * 
     * @return Response
     */
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

    
    /**
     * Funkcija za uklanjanje kategorije
     * 
     * @getParam int id idKategorije
     * 
     * @return Response
     */
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

    /**
     * Prikaz zahteva za konverziju pruzaoca
     * 
     * @return Response 
     */
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

    /**
     * Funkcija za odobravanje konverzije u pruzaoca
     * 
     * @getParam int id idKorisnika
     * 
     * @return Response
     */
    public function OPApproveRequest()
    {
        $korisnikModel = new KorisnikModel();
        $korisnikModel->update($this->request->getVar('id'), ['pruzalac' => 1]);
        return redirect()->to(base_url('AdminController/accountrequests'));
    }

    /**
     * Funkcija za odbijanje konverzije u pruzaoca
     * 
     * @getParam int id idKorisnika
     * 
     * @return Response
     */
    public function OPDenyRequest()
    {
        $korisnikModel = new KorisnikModel();
        $korisnikModel->update($this->request->getVar('id'), ['pruzalac' => 0]);
        return redirect()->to(base_url('AdminController/accountrequests'));
    }
}