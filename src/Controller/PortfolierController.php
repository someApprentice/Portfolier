<?php
namespace Portfolier\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class PortfolierController extends Controller
{
    /**
    * Saying Hello
    * 
    * @return Response 
    */
    public function hello(): Response
    {
        return new Response("Hello");
    }
}