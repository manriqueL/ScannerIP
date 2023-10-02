<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Service\IpScannerService;
use App\Entity\Ip;

class IpController extends AbstractController
{
    private $ipScannerService;

    public function __construct(IpScannerService $ipScannerService)
    {
        $this->ipScannerService = $ipScannerService;
    }

    public function list(): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $ips = $entityManager->getRepository(Ip::class)->findAll();

        // Renderiza la plantilla Twig con los datos de las IPs
        return $this->render('ips/list.html.twig', [
            'ips' => $ips,
            
        ]);
        
    }

    public function actualizarIps()
    {
        // Realiza la exploración de IPs antes de obtener los datos
        

        $entityManager = $this->getDoctrine()->getManager();
        $ips = $entityManager->getRepository(Ip::class)->findAll();
        $this->ipScannerService->scanIps();
        return $this->render('ips/list_table.html.twig', [
            'ips' => $ips,
        ]);
    }
}
