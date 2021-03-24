<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Repository\RatingRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RatingController extends AbstractController
{
    private $validator;

    private $ratingRepository;

    private $userRepository;

    private $productRepository;

    /**
     * RatingController constructor.
     *
     * @param  \App\Repository\RatingRepository  $ratingRepository
     * @param  \App\Repository\UserRepository  $userRepository
     * @param  \App\Repository\ProductRepository  $productRepository
     * @param  \Symfony\Component\Validator\Validator\ValidatorInterface  $validator
     */
    public function __construct(
        RatingRepository $ratingRepository,
        UserRepository $userRepository,
        ProductRepository $productRepository,
        ValidatorInterface $validator
    )
    {
        $this->ratingRepository = $ratingRepository;
        $this->userRepository = $userRepository;
        $this->productRepository = $productRepository;
        $this->validator = $validator;
    }

    /**
     * @Route("/api/ratings", name="api.ratings.store", methods={"POST"})
     *
     * @param  \Symfony\Component\HttpFoundation\Request  $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function store(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        $constraints = new Assert\Collection([
            'user_id' => new Assert\NotBlank(),
            'product_id' => new Assert\NotBlank(),
            'rating' => new Assert\NotBlank()
        ]);

        $errors = $this->validator->validate($data, $constraints);

        $errorMessages = [];
        foreach ($errors as $error) {
            $errorMessages[substr($error->getPropertyPath(), 1, -1)] = $error->getMessage();
        }

        if (0 !== count($errors)) {
            return new JsonResponse($errorMessages, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $user = $this->userRepository->find($data['user_id']);
        if (! $user) {
            return new JsonResponse([ 'error' => 'User does not exist.' ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $product = $this->productRepository->find($data['product_id']);
        if (! $product) {
            return new JsonResponse([ 'error' => 'Product does not exist.' ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // check if a rating already exists
        if ($this->ratingRepository->checkRatingExists($user, $product)) {
            return new JsonResponse([ 'error' => 'User has already rated the product.' ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $this->ratingRepository->save($user, $product, $data['rating']);

        return new JsonResponse(
            [ 'message' => 'Product rated successfully' ],
            Response::HTTP_OK
        );
    }
}
