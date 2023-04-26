<?php

namespace App\Controller\Api\V1;

use App\Entity\AddressVersion;
use App\Entity\Company;
use App\Entity\CompanyVersion;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
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
        protected EntityManagerInterface $em,
        protected DenormalizerInterface $denormalizer
    )
    {
    }

    // todo add request requirement regex for id and datetime
    #[Rest\Get(path: self::ID_IN_PATH, name: 'api_v1_company_histories_get')]
    #[Rest\Head(path: self::ID_IN_PATH, name: 'api_v1_company_histories_head')]
    public function get(int $id, \DateTimeInterface $datetime): JsonResponse
    {
        if (null === $company = $this->em->find(Company::class, $id)) {
            throw new NotFoundHttpException('Company not found.');
        }

        $companyVersionRepository = $this->em->getRepository(CompanyVersion::class);
        $companyVersions = $companyVersionRepository->getLogEntriesByDate($company, $datetime);

        if (null === $companyVersions) {
            return $this->json(data: [], status: Response::HTTP_NO_CONTENT);
        }

        $companyVersionRepository->revert($company, $companyVersions->getVersion());

        $addressVersionRepository = $this->em->getRepository(AddressVersion::class);

        $addresses = New ArrayCollection();

        foreach ($company->getAddresses() as $address) {
            $addressVersions = $addressVersionRepository->getLogEntriesByDate($address, $datetime);

            if (null !== $addressVersions) {
                $addressVersionRepository->revert($address, $addressVersions->getVersion());
                $addresses->add($address);
            }
        }

        $company->setAddresses($addresses);


        return $this->json(data: $company, context: ['groups' => self::GET_GROUPS]);
    }
}
