<?php
// src/Service/IpScannerService.php
// ...
namespace App\Service;

use App\Entity\Ip;
use App\Entity\ScanLog;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class IpScannerService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function scanIps()
    {
        $ips = $this->entityManager->getRepository(Ip::class)->findAll();
        $results = [];
        
        foreach ($ips as $ip) {
            $ipAddress = $ip->getIpAddress();
            $result = shell_exec("ping -n 2 $ipAddress");
            $isActive = strpos($result, "(0% loss)" ) !== false;
            
            if ($result === null) {
                echo "Error executing ping command.";
            } 
            
            $ip->setIsActive($isActive);
            
            if ($ip->getIsActive()) {
                $timezone = new \DateTimeZone('America/Argentina/La_Rioja');
                $lastDeactivatedAt = new \DateTime('now', $timezone);
                $ip->setLastDeactivatedAt($lastDeactivatedAt);
            }
            
            $scanLog = $this->entityManager->getRepository(ScanLog::class)->findOneBy(['ip' => $ip]);
            
            if ($scanLog) {
                $scanLog->setIsActive($isActive);
                $scanLog->setScannedAt(new \DateTime());
            } else {
                $scanLog = new ScanLog();
                $scanLog->setIp($ip);
                $scanLog->setIsActive($isActive);
                $scanLog->setScannedAt(new \DateTime());
                $this->entityManager->persist($scanLog);
            }
            
            $this->entityManager->persist($ip);

            $results[] = [
                'ipAddress' => $ip->getIpAddress(),
                'isActive' => $ip->getIsActive(),
                // Add any other data you want to include in the JSON response
            ];
        }
        
        $this->entityManager->flush();

        return new JsonResponse($results);
    }
}