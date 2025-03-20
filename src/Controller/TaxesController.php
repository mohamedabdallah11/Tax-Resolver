<?php

namespace App\Controller;

use App\Service\TaxService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

readonly class TaxesController
{
    public function __construct(private readonly TaxService $taxService) {}

    #[Route('/taxes', methods: ['GET'])]
    public function getTaxes(Request $request): JsonResponse
    {
        $country = $request->query->get('country');
        $state = $request->query->get('state');
        
        if (!$country) {
            return new JsonResponse(['error' => 'Country is required'], Response::HTTP_BAD_REQUEST);
        }
        
        $taxes = $this->taxService->getTaxes($country, $state);
        
        return new JsonResponse($taxes);
    }
}
