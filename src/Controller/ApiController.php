<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\TransactionData;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ApiController extends AbstractController
{
    #[Route('/app/example/{system}', name: 'app_example', methods: ['POST', 'GET'])]
    public function example(
        Request $request,
        string $system,
        ValidatorInterface $validator
    ): JsonResponse {
        if (!in_array($system, ['aci', 'shift4'])) {
            return new JsonResponse(['error' => 'Invalid system parameter. Use "aci" or "shift4".'], 400);
        }

        $dto = new TransactionData();
        $dto->amount = $request->query->get('amount');
        $dto->currency = $request->query->get('currency');
        $dto->cardNumber = $request->query->get('card_number');
        $dto->cardExpYear = $request->query->get('card_exp_year');
        $dto->cardExpMonth = $request->query->get('card_exp_month');
        $dto->cardCvv = $request->query->get('card_cvv');

        $errors = $validator->validate($dto);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[$error->getPropertyPath()] = $error->getMessage();
            }
            return new JsonResponse(['errors' => $errorMessages], 400);
        }

        // Заглушка ответа
        return new JsonResponse([
            'transaction_id' => uniqid('trx_', true),
            'date_of_creation' => date('Y-m-d H:i:s'),
            'amount' => $dto->amount,
            'currency' => $dto->currency,
            'card_bin' => substr($dto->cardNumber, 0, 6),
            'system' => $system
        ]);
    }
}
