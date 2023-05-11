<?php

namespace App\Controller\Api\V1;

use App\Manager\LegalStatusManager;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Rest\Route('legal-statuses')]
class LegalStatusController extends AbstractController
{
    public function __construct(
        protected LegalStatusManager $legalStatusManager
    ) {
    }

    #[Rest\QueryParam(
        name: 'q', requirements: '\s+', description: 'Search', strict: true, nullable: true
    )]
    #[Rest\Get(name: 'api_v1_legal_statuses_get_collection')]
    public function getCollection(ParamFetcher $paramFetcher): JsonResponse
    {
        /** @psalm-var string $search */ $search = $paramFetcher->get('q');
        $legalStatuses = $this->legalStatusManager->search(
            search: $search
        );

        return $this->json(
            data: $legalStatuses,
            headers: ['X-Total-Count' => count($legalStatuses)],
            context: ['groups' => ['get-legal-status', 'get']]
        );
    }
}
