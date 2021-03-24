<?php

namespace App\Controller;

use App\Repository\ApiTokenRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ApiTokenController extends AbstractController
{
    private $userRepository;

    private $apiTokenRepository;

    private $validator;

    /**
     * ApiTokenController constructor.
     *
     * @param  \App\Repository\UserRepository  $userRepository
     * @param  \Symfony\Component\Validator\Validator\ValidatorInterface  $validator
     */
    public function __construct(UserRepository $userRepository, ApiTokenRepository $apiTokenRepository, ValidatorInterface $validator)
    {
        $this->userRepository = $userRepository;
        $this->validator = $validator;
        $this->apiTokenRepository = $apiTokenRepository;
    }


    /**
     * @Route("/api/api-tokens", name="api.api_token.store", methods={"POST"})
     *
     * @param  \Symfony\Component\HttpFoundation\Request  $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function store(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        $constraints = new Assert\Collection([
            'email' => [ new Assert\NotBlank(), new Assert\Email() ],
            'password' => [ new Assert\NotBlank(), new Assert\NotCompromisedPassword() ]
        ]);

        $errors = $this->validator->validate($data, $constraints);

        $errorMessages = [];
        foreach ($errors as $error) {
            $errorMessages[substr($error->getPropertyPath(), 1, -1)] = $error->getMessage();
        }

        if (0 !== count($errors)) {
            return new JsonResponse($errorMessages, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $user = $this->userRepository->getUserByEmail($data);

        if (! $user) {
            $user = $this->userRepository->save($data);
        }

        $apiToken = $this->apiTokenRepository->save($user);

        return new JsonResponse(
            [
                'message' => 'Token created successfully',
                'token' => $apiToken->getToken()
            ],
            Response::HTTP_CREATED
        );
    }
}
