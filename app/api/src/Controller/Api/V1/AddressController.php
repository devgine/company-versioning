<?php

declare(strict_types=1);

namespace App\Controller\Api\V1;

use App\Entity\Address;
use App\Entity\Company;
use App\Manager\AddressManager;
use App\Manager\CompanyManager;
use App\Validator\Validator;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcher;
use LogicException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

#[Rest\Route('addresses')]
class AddressController extends AbstractController
{
    public const ID_IN_PATH = '/{id}';
    public const GET_GROUPS = ['get', 'get-address', 'get-address-company'];

    public function __construct(
        protected AddressManager $addressManager,
        protected Validator $validator,
        protected DenormalizerInterface $denormalizer
    ) {
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
    #[Rest\Get(name: 'api_v1_addresses_get_collection')]
    public function getCollection(ParamFetcher $paramFetcher): JsonResponse
    {
        /** @psalm-var int $start */ $start = $paramFetcher->get('_start');
        /** @psalm-var int $end */ $end = $paramFetcher->get('_end');
        /** @psalm-var string $order */ $order = $paramFetcher->get('_order');
        /** @psalm-var string $sort */ $sort = $paramFetcher->get('_sort');
        /** @psalm-var string $search */ $search = $paramFetcher->get('search');

        return $this->json(
            data: $this->addressManager->search(
                search: $search,
                order: $order,
                sort: $sort,
                limit: $end - $start,
                offset: $start
            ),
            headers: ['X-Total-Count' => $this->addressManager->total($search)],
            context: ['groups' => self::GET_GROUPS]
        );
    }

    #[Rest\Get(path: self::ID_IN_PATH, name: 'api_v1_addresses_get')]
    #[Rest\Head(path: self::ID_IN_PATH, name: 'api_v1_addresses_head')]
    public function get(int $id): JsonResponse
    {
        $address = $this->addressManager->find($id);

        if (!$address instanceof Address) {
            throw new NotFoundHttpException('Address not found.');
        }

        return $this->json(data: $address, context: ['groups' => self::GET_GROUPS]);
    }

    #[Rest\Delete(path: self::ID_IN_PATH, name: 'api_v1_addresses_delete')]
    public function delete(int $id): JsonResponse
    {
        $address = $this->addressManager->find($id);

        if (null === $address) {
            throw new NotFoundHttpException('Address not found.');
        }

        $this->addressManager->remove($address);

        return $this->json(data: [], status: Response::HTTP_NO_CONTENT);
    }

    /**
     * @throws ExceptionInterface
     */
    #[Rest\Post(name: 'api_v1_addresses_post')]
    public function post(Request $request, CompanyManager $companyManager): JsonResponse
    {
        $address = $this->denormalizer->denormalize(
            data: $request->request->all(),
            type: Address::class,
            context: ['groups' => ['set-address']]
        );

        if (!$address instanceof Address) {
            throw new LogicException('Error Address denormalization');
        }

        if (!array_key_exists('company_id', $request->request->all())) {
            throw new BadRequestHttpException('company_id is required.');
        }

        $violations = $this->validator->validate(object: $address, groups: ['set-address']);

        if (null !== $violations) {
            $jsonMessage = json_encode($violations);

            throw new BadRequestHttpException(
                is_string($jsonMessage) ? $jsonMessage : '[Create address] Address is not valid.'
            );
        }

        $companyId = $request->request->all()['company_id'];
        $company = $companyManager->find($companyId);

        if (!$company instanceof Company) {
            throw new NotFoundHttpException('Company not found.');
        }

        $this->addressManager->save($address->setCompany($company));

        return $this->json(data: $address, status: Response::HTTP_CREATED, context: ['groups' => 'get-address']);
    }

    /**
     * @throws ExceptionInterface
     */
    #[Rest\Put(path: self::ID_IN_PATH, name: 'api_v1_addresses_put')]
    #[Rest\Patch(path: self::ID_IN_PATH, name: 'api_v1_addresses_patch')]
    public function update(int $id, Request $request): JsonResponse
    {
        $address = $this->addressManager->find($id);

        if (!$address instanceof Address) {
            throw new NotFoundHttpException(sprintf('Company not found with id %d', $id));
        }

        $this->denormalizer->denormalize(
            data: $request->request->all(),
            type: Address::class,
            context: [
                'groups' => ['set-address'],
                AbstractNormalizer::OBJECT_TO_POPULATE => $address,
                AbstractObjectNormalizer::DEEP_OBJECT_TO_POPULATE => true,
            ]
        );

        $violations = $this->validator->validate(object: $address, groups: ['set-address']);

        if (null !== $violations) {
            $jsonMessage = json_encode($violations);

            throw new BadRequestHttpException(
                is_string($jsonMessage) ? $jsonMessage : '[Update address] Address is not valid.'
            );
        }

        $this->addressManager->save($address);

        return $this->json(data: $address, context: ['groups' => self::GET_GROUPS]);
    }
}
