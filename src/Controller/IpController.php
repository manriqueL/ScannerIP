<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\IpScannerService;
use App\Entity\Ip; // Asegúrate de importar la entidad Ip

class IpController extends AbstractController
{
    private $ipScannerService;

    public function __construct(IpScannerService $ipScannerService)
    {
        $this->ipScannerService = $ipScannerService;
    }


    public function list(): Response
    {
        // Recupera las IPs desde la base de datos
        $entityManager = $this->getDoctrine()->getManager();
        $ips = $entityManager->getRepository(Ip::class)->findAll();
        $this->ipScannerService->scanIps();
        // Renderiza la plantilla Twig con los datos de las IPs
        return $this->render('ips/list.html.twig', [
            'ips' => $ips,
            
        ]);
        
    }

    public function actualizarIps()
    {
        // Obtén los datos actualizados de las IPs (puedes ajustar esta parte según tu lógica)
        $ips = $this->getDoctrine()->getRepository(Ip::class)->findAll();

        // Formatea los datos para la respuesta JSON
        $data = [];
        foreach ($ips as $ip) {
            $data[] = [
                'id' => $ip->getId(),
                'ipAddress' => $ip->getIpAddress(),
                'isActive' => $ip->getIsActive(),
                'lastDeactivatedAt' => $ip->getLastDeactivatedAt() ? $ip->getLastDeactivatedAt()->format('Y-m-d H:i:s') : null,
            ];
        }

        return new JsonResponse($data);
    }
}

