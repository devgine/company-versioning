<?php

namespace App\Controller\Api\V1;

use App\Entity\Company;
use App\Manager\CompanyManager;
use App\Validator\Validator;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

#[Rest\Route('companies')]
class CompanyController extends AbstractController
{
    public const ID_IN_PATH = '/{id}';
    public const GET_GROUPS = ['get', 'get-company', 'get-company-addresses'];

    public function __construct(
        protected CompanyManager $companyManager,
        protected Validator $validator,
        protected DenormalizerInterface $denormalizer
    ) {
    }

    #[Rest\QueryParam(
        name: 'search', description: 'Search', strict: true, nullable: true
    )]
    #[Rest\QueryParam(
        name: 'q', description: 'Search', strict: true, nullable: true
    )]
    #[Rest\QueryParam(
        name: '_start', requirements: '\d+', default: 0, description: 'Start list', strict: true, nullable: true
    )]
    #[Rest\QueryParam(
        name: '_end', requirements: '\d+', default: 50, description: 'End list', strict: true, nullable: true
    )]
    #[Rest\QueryParam(
        name: '_order',
        requirements: '(ASC|DESC|asc|desc)',
        default: 'ASC',
        description: 'Order list',
        strict: true,
        nullable: true
    )]
    #[Rest\QueryParam(
        name: '_sort',
        description: 'Sort field',
        strict: true,
        nullable: true
    )]
    #[Rest\Get(name: 'api_v1_companies_get_collection')]
    public function getCollection(ParamFetcher $paramFetcher): JsonResponse
    {
        $start = $paramFetcher->get('_start');
        $end = $paramFetcher->get('_end');
        $order = $paramFetcher->get('_order');
        $sort = $paramFetcher->get('_sort');
        $search = $paramFetcher->get('search') ?? $paramFetcher->get('q');

        return $this->json(
            data: $this->companyManager->search(
                search: $search,
                order: $order,
                sort: $sort,
                limit: $end - $start,
                offset: $start
            ),
            headers: ['X-Total-Count' => $this->companyManager->total($search)],
            context: ['groups' => self::GET_GROUPS]
        );
    }

    #[Rest\Get(path: self::ID_IN_PATH, name: 'api_v1_companies_get')]
    #[Rest\Head(path: self::ID_IN_PATH, name: 'api_v1_companies_head')]
    public function get(int $id): JsonResponse
    {
        if (null === $company = $this->companyManager->find($id)) {
            throw new NotFoundHttpException('Company not found.');
        }

        return $this->json(data: $company, context: ['groups' => self::GET_GROUPS]);
    }

    #[Rest\Delete(path: self::ID_IN_PATH, name: 'api_v1_companies_delete')]
    public function delete(int $id): JsonResponse
    {
        if (null === $company = $this->companyManager->find($id)) {
            throw new NotFoundHttpException('Company not found.');
        }

        $this->companyManager->remove($company);

        return $this->json(data: [], status: Response::HTTP_NO_CONTENT);
    }

    #[Rest\Post(name: 'api_v1_companies_post')]
    public function post(Request $request): JsonResponse
    {
        $company = $this->denormalizer->denormalize(
            data: $request->request->all(),
            type: Company::class,
            context: ['groups' => ['set-company']]
        );

        if (null !== $violations = $this->validator->validate(object: $company, groups: 'set-company')) {
            throw new BadRequestHttpException(json_encode($violations));
        }

        $this->companyManager->save($company);

        return $this->json(data: $company, status: Response::HTTP_CREATED, context: ['groups' => ['get', 'get-company']]);
    }

    #[Rest\Put(path: self::ID_IN_PATH, name: 'api_v1_companies_put')]
    #[Rest\Patch(path: self::ID_IN_PATH, name: 'api_v1_companies_patch')]
    public function update(int $id, Request $request): JsonResponse
    {
        if (null === $company = $this->companyManager->find($id)) {
            throw new NotFoundHttpException(sprintf('Company not found with id %d', $id));
        }

        $this->denormalizer->denormalize(
            data: $request->request->all(),
            type: Company::class,
            context: [
                'groups' => ['set-company'],
                AbstractNormalizer::OBJECT_TO_POPULATE => $company,
                AbstractObjectNormalizer::DEEP_OBJECT_TO_POPULATE => true,
            ]
        );

        if (null !== $violations = $this->validator->validate(object: $company, groups: ['set-company'])) {
            throw new BadRequestHttpException(json_encode($violations));
        }

        $this->companyManager->save($company);

        return $this->json(data: $company, context: ['groups' => self::GET_GROUPS]);
    }
}
