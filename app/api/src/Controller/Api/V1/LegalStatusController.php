<?php

namespace App\Controller\Api\V1;

use App\Entity\LegalStatus;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Rest\Route('legalStatuses')]
class LegalStatusController extends AbstractController
{
    public function __construct(
        protected EntityManagerInterface $em
    )
    {
    }

    #[Rest\Get(name: 'api_v1_legal_statuses_get_collection')]
    public function getCollection(): JsonResponse
    {
        $legalStatuses = $this->em->getRepository(LegalStatus::class)->findAll();
        return $this->json(
            data: $legalStatuses,
            headers: ['X-Total-Count' => count($legalStatuses)],
            context: ['groups' => 'get-legal-status']
        );
    }
}
