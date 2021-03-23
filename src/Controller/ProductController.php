<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProductController extends AbstractController
{
    private $productRepository;

    private $validator;

    /**
     * ProductController constructor.
     *
     * @param  \App\Repository\ProductRepository  $productRepository
     * @param  \Symfony\Component\Validator\Validator\ValidatorInterface  $validator
     */
    public function __construct(ProductRepository $productRepository, ValidatorInterface $validator)
    {
        $this->productRepository = $productRepository;
        $this->validator = $validator;
    }

    /**
     * @Route("/api/products", name="api.product.index", methods={"GET"})
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(): Response
    {
        $products = $this->productRepository->findAll();

        return new JsonResponse(
            $products,
            Response::HTTP_OK
        );
    }

    /**
     * @Route("/api/products", name="api.product.store", methods={"POST"})
     * @param  \Symfony\Component\HttpFoundation\Request  $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function store(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        $constraints = new Assert\Collection([
            'name' => [ new Assert\NotBlank(), new Assert\Length(['max' => 255])],
            'price' => new Assert\NotBlank(),
            'rating' => new Assert\NotBlank(),
            'variations' => new Assert\Optional()
        ]);

        $errors = $this->validator->validate($data, $constraints);

        $errorMessages = [];

        foreach ($errors as $error) {
            $errorMessages[substr($error->getPropertyPath(), 1, -1)] = $error->getMessage();
        }

        if (0 !== count($errors)) {
            return new JsonResponse($errorMessages, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $product = $this->productRepository->save($data);

        return new JsonResponse(
            [
                'message' => 'Product created successfully',
                'product' => $product->toArray()
            ],
            Response::HTTP_CREATED
        );
    }

    /**
     * @Route("/api/products/{id}", name="api.products.update", methods={"PUT"})
     * @param  \App\Entity\Product  $product
     * @param  \Symfony\Component\HttpFoundation\Request  $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function update(Product $product, Request $request)
    {
        $data = json_decode($request->getContent(), true);

        $constraints = new Assert\Collection([
            'name' => [ new Assert\NotBlank(), new Assert\Length(['max' => 255])],
            'price' => new Assert\NotBlank(),
            'rating' => new Assert\NotBlank(),
            'variations' => new Assert\Optional()
        ]);

        $errors = $this->validator->validate($data, $constraints);

        $errorMessages = [];

        foreach ($errors as $error) {
            $errorMessages[substr($error->getPropertyPath(), 1, -1)] = $error->getMessage();
        }

        if (0 !== count($errors)) {
            return new JsonResponse($errorMessages, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $product = $this->productRepository->update($product, $data);

        return new JsonResponse(
            [
                'message' => 'Product updated successfully',
                'product' => $product->toArray()
            ],
            Response::HTTP_OK
        );
    }

    /**
     * @Route("/api/products/{id}", name="api.products.delete", methods={"DELETE"})
     * @param  \App\Entity\Product  $product
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function delete(Product $product)
    {
        $this->productRepository->delete($product);

        return $this->json('Product successfully deleted');
    }
}
