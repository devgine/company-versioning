<?php

namespace App\Controller\Api\V1;

use App\Validator\Validator;
use App\Entity\Company;
use Doctrine\ORM\EntityManagerInterface;
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

    public function __construct(
        protected EntityManagerInterface $em,
        protected Validator $validator,
        protected DenormalizerInterface $denormalizer
    )
    {
    }

    #[Rest\QueryParam(
        name: 'search', description: 'Search', strict: true, nullable: true
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
    #[Rest\Get(name: 'api_v1_get_companies_collection')]
    public function getCollection(ParamFetcher $paramFetcher): JsonResponse
    {
        $start = $paramFetcher->get('_start');
        $end = $paramFetcher->get('_end');
        $order = $paramFetcher->get('_order');
        $sort = $paramFetcher->get('_sort');
        $search = $paramFetcher->get('search');

        return $this->json(
            data: $this->em->getRepository(Company::class)->search(
                search: $search,
                order: $order,
                sort: $sort,
                limit: $end - $start,
                offset: $start
            ),
            headers: ['X-Total-Count' => $this->em->getRepository(Company::class)->total($search)],
            context: ['groups' => 'get']
        );
    }

    #[Rest\Get(path: self::ID_IN_PATH, name: 'api_v1_companies_get')]
    #[Rest\Head(path: self::ID_IN_PATH, name: 'api_v1_companies_head')]
    public function get(int $id): JsonResponse
    {
        if (null === $company = $this->em->find(Company::class, $id)) {
            throw new NotFoundHttpException('Company not found.');
        }

        return $this->json(data: $company, context: ['groups' => 'get']);
    }

    #[Rest\Delete(path: self::ID_IN_PATH, name: 'api_v1_companies_delete')]
    public function delete(int $id): JsonResponse
    {
        if (null === $company = $this->em->find(Company::class, $id)) {
            throw new NotFoundHttpException('Company not found.');
        }

        $this->em->remove($company);

        return $this->json(data: [], status: Response::HTTP_NO_CONTENT);
    }

    #[Rest\Post(name: 'api_v1_companies_post')]
    public function post(Request $request): JsonResponse
    {
        $company = $this->denormalizer->denormalize(
            data: $request->request->all(),
            type: Company::class,
            context: ['groups' => ['set']]
        );

        if (null !== $violations = $this->validator->validate(object: $company, groups: 'set')) {
            throw new BadRequestHttpException(json_encode($violations));
        }

        $this->em->persist($company);
        $this->em->flush();

        return $this->json(data: $company, status: Response::HTTP_CREATED, context: ['groups' => 'get']);
    }

    #[Rest\Put(path: self::ID_IN_PATH, name: 'api_v1_companies_put')]
    #[Rest\Patch(path: self::ID_IN_PATH, name: 'api_v1_companies_patch')]
    public function update(int $id, Request $request): JsonResponse
    {
        if (null === $company = $this->em->find(Company::class, $id)) {
            throw new NotFoundHttpException(sprintf('Company not found with id %d', $id));
        }

        $this->denormalizer->denormalize(
            data: $request->request->all(),
            type: Company::class,
            context: [
                'groups' => ['set'],
                AbstractNormalizer::OBJECT_TO_POPULATE => $company,
                AbstractObjectNormalizer::DEEP_OBJECT_TO_POPULATE => true
            ]
        );

        if (null !== $violations = $this->validator->validate(object: $company, groups: 'set')) {
            throw new BadRequestHttpException(json_encode($violations));
        }

        $this->em->persist($company);
        $this->em->flush();

        return $this->json(data: $company, context: ['groups' => 'get']);
    }
}
