<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\ExternalSystemManager;
use App\Service\ExternalSystemManager\RequestDto;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ApiController extends AbstractController
{
    #[Route('/app/example/{system}', name: 'app_example', methods: ['POST', 'GET'])]
    public function example(
        Request $request,
        string $system,
        ValidatorInterface $validator,
        ExternalSystemManager $externalSystemManager,
    ): JsonResponse {
        if (!$externalSystemManager->isAvailable($system)) {
            $error = sprintf(
                'Invalid system parameter. Available systems: %s.',
                implode(', ', $externalSystemManager->getAvailableIdList())
            );
            return new JsonResponse(['error' => $error], 400);
        }

        $requestDto = new RequestDto();
        $requestDto->amount = $request->query->get('amount');
        $requestDto->currency = $request->query->get('currency');
        $requestDto->cardNumber = $request->query->get('card_number');
        $requestDto->cardExpYear = $request->query->get('card_exp_year');
        $requestDto->cardExpMonth = $request->query->get('card_exp_month');
        $requestDto->cardCvv = $request->query->get('card_cvv');

        $errors = $validator->validate($requestDto);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[$error->getPropertyPath()] = $error->getMessage();
            }
            return new JsonResponse(['errors' => $errorMessages], 400);
        }

        try {
            $response = $externalSystemManager->process($system, $requestDto);
        } catch (\Throwable $e) {
            return new JsonResponse(['errors' => [$e->getMessage()]], 400);
        }

        return new JsonResponse($response->toArray());
    }
}
