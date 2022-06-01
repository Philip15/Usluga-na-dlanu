<?php
/**
  * @author Jana Pašajlić   2019/0132
  */

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class ProviderFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $korisnik = session('user');
        if ($korisnik == null || $korisnik->role() != 'provider')
        {
            return redirect()->to(base_url());
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}