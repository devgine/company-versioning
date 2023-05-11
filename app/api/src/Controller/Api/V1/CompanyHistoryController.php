<?php

namespace App\Controller\Api\V1;

use App\Entity\AddressVersion;
use App\Entity\Company;
use App\Entity\CompanyVersion;
use App\Manager\AddressManager;
use App\Manager\AddressVersionManager;
use App\Manager\CompanyManager;
use App\Manager\CompanyVersionManager;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

#[Rest\Route('companyHistories')]
class CompanyHistoryController extends AbstractController
{
    public const ID_IN_PATH = '/{id}/{datetime}';
    public const GET_GROUPS = ['get', 'get-company', 'get-company-addresses'];

    public function __construct(
        protected CompanyVersionManager $companyVersionManager,
        protected AddressVersionManager $addressVersionManager,
        protected CompanyManager $companyManager,
        protected AddressManager $addressManager,
        protected DenormalizerInterface $denormalizer
    ) {
    }

    // todo add request requirement regex for id and datetime
    #[Rest\Get(path: self::ID_IN_PATH, name: 'api_v1_company_histories_get')]
    #[Rest\Head(path: self::ID_IN_PATH, name: 'api_v1_company_histories_head')]
    public function get(int $id, DateTimeInterface $datetime): JsonResponse
    {
        $company = $this->companyManager->find($id);

        if (!$company instanceof Company) {
            throw new NotFoundHttpException('Company not found.');
        }

        $companyVersions = $this->companyVersionManager->getLogEntriesByDate($company, $datetime);

        if (!$companyVersions instanceof CompanyVersion) {
            return $this->json(data: [], status: Response::HTTP_NO_CONTENT);
        }

        $this->companyVersionManager->revert($company, $companyVersions->getVersion() ?? 1);

        $addresses = new ArrayCollection();

        foreach ($company->getAddresses() as $address) {
            $addressVersions = $this->addressVersionManager->getLogEntriesByDate($address, $datetime);

            if ($addressVersions instanceof AddressVersion) {
                $this->addressVersionManager->revert($address, $addressVersions->getVersion() ?? 1);
                $addresses->add($address);
            }
        }

        $company->setAddresses($addresses);

        return $this->json(data: $company, context: ['groups' => self::GET_GROUPS]);
    }
}
