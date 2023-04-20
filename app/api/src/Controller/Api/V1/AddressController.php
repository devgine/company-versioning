<?php

namespace App\Controller\Api\V1;

use App\Entity\Address;
use App\Entity\Company;
use App\Validator\Validator;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

#[Rest\Route('addresses')]
class AddressController extends AbstractController
{
    public const ID_IN_PATH = '/{id}';
    public const GET_GROUPS = ['get', 'get-address', 'get-address-company'];

    public function __construct(
        protected EntityManagerInterface $em,
        protected Validator $validator,
        protected DenormalizerInterface $denormalizer
    )
    {
    }

    #[Rest\Get(path: self::ID_IN_PATH, name: 'api_v1_addresses_get')]
    #[Rest\Head(path: self::ID_IN_PATH, name: 'api_v1_addresses_head')]
    public function get(int $id): JsonResponse
    {
        if (null === $address = $this->em->find(Address::class, $id)) {
            throw new NotFoundHttpException('Address not found.');
        }

        return $this->json(data: $address, context: ['groups' => self::GET_GROUPS]);
    }

    #[Rest\Delete(path: self::ID_IN_PATH, name: 'api_v1_addresses_delete')]
    public function delete(int $id): JsonResponse
    {
        if (null === $address = $this->em->find(Company::class, $id)) {
            throw new NotFoundHttpException('Address not found.');
        }

        $this->em->remove($address);

        return $this->json(data: [], status: Response::HTTP_NO_CONTENT);
    }

    #[Rest\Post('/company/{id}', name: 'api_v1_addresses_post')]
    public function post(Request $request, int $id): JsonResponse
    {
        if (null === $company = $this->em->find(Company::class, $id)) {
            throw new NotFoundHttpException('Company not found.');
        }

        $address = $this->denormalizer->denormalize(
            data: $request->request->all(),
            type: Address::class,
            context: ['groups' => ['set-address', 'set']]
        );

        if (null !== $violations = $this->validator->validate(object: $address, groups: 'set-address')) {
            throw new BadRequestHttpException(json_encode($violations));
        }

        $this->em->persist($address->setCompany($company));
        $this->em->flush();

        return $this->json(data: $address, status: Response::HTTP_CREATED, context: ['groups' => 'get-address']);
    }

    #[Rest\Put(path: self::ID_IN_PATH, name: 'api_v1_addresses_put')]
    #[Rest\Patch(path: self::ID_IN_PATH, name: 'api_v1_addresses_patch')]
    public function update(int $id, Request $request): JsonResponse
    {
        if (null === $address = $this->em->find(Address::class, $id)) {
            throw new NotFoundHttpException(sprintf('Company not found with id %d', $id));
        }

        $this->denormalizer->denormalize(
            data: $request->request->all(),
            type: Address::class,
            context: [
                'groups' => ['set-address'],
                AbstractNormalizer::OBJECT_TO_POPULATE => $address,
                AbstractObjectNormalizer::DEEP_OBJECT_TO_POPULATE => true
            ]
        );

        if (null !== $violations = $this->validator->validate(object: $address, groups: ['set-address'])) {
            throw new BadRequestHttpException(json_encode($violations));
        }

        $this->em->persist($address);
        $this->em->flush();

        return $this->json(data: $address, context: ['groups' => self::GET_GROUPS]);
    }
}
