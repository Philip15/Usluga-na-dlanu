<?php

namespace App\Controllers;

use App\Models\KategorijaModel;
use App\Models\KorisnikModel;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use stdClass;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var array
     */
    protected $helpers = ['form','string'];

    /**
     * Constructor.
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.
        $this->session = \Config\Services::session();
    }

    public function index()
    {
        $data['jsinit']='mainPage';
        $data['categories']=KategorijaModel::getAll();
        
        return view('mainpage',$data);
    }

    public function AJAXGetProviders()
    {
        $cat=$this->request->getGet('cat');
        $tFrom=$this->request->getGet('tFrom');
        $tTo=$this->request->getGet('tTo');
        $dFrom=$this->request->getGet('dFrom');
        $dTo=$this->request->getGet('dTo');
        

        $providers=KorisnikModel::getProviders($cat);
        $result = [];

        foreach ($providers as $provider) 
        {
            if($tFrom==null || $tTo==null || $dFrom==null || $dTo==null || $provider->available($tFrom,$tTo,$dFrom,$dTo))
            {
                $resobj=new stdClass();
                if(isset($provider->profilnaSlika) && $provider->profilnaSlika !== null)
                {
                    $resobj->profilnaSlika = 'data:image/jpeg;base64,'.base64_encode($provider->profilnaSlika);
                }
                else
                {
                    $resobj->profilnaSlika = base_url('res/placeholder-avatar.jpg');
                }
                $resobj->idKorisnika = esc($provider->idKorisnika);
                $resobj->ime = esc($provider->ime);
                $resobj->prezime = esc($provider->prezime);
                $provider->linkKategorija();
                $resobj->kategorija = esc(ucfirst($provider->kategorija->naziv));
                $resobj->lat = esc($provider->lat);
                $resobj->lon = esc($provider->lon);
                $resobj->opis = esc($provider->opis);
                $resobj->ocena = esc($provider->rating());

                array_push($result,$resobj);
            }
        }

        return $this->response
            ->setContentType('application/json')
            ->setStatusCode(200)
            ->setJSON(json_encode($result));
    }

    public static function safeRedirectBack()
    {
        if(str_contains(previous_url(),'/AJAX') || str_contains(previous_url(),'/OP'))
        {
            return redirect()->to(base_url('/'));
        }
        else
        {
            return redirect()->back();
        }
    }
}
