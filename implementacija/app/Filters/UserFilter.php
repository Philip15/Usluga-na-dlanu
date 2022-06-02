<?php
/**
  * @author Jana Pašajlić   2019/0132
  */

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

/**
 * UserFilter - filter za user kontroler 
 */
class UserFilter implements FilterInterface
{
    /**
     * Funkcija koja se poziva pre izvrsenja user kontrolera
     * 
     * @param RequestInterface $request Request
     * 
     * @return Response
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $korisnik = session('user');
        if ($korisnik == null)
        {
            return redirect()->to(base_url());
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}