<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;

class ApiController extends AbstractController
{
    #[Route('/app/example/{system}', name: 'app_example', methods: ['POST', 'GET'])]
    public function example(Request $request, string $system): JsonResponse
    {
        // Проверяем параметр system
        if (!in_array($system, ['aci', 'shift4'])) {
            return new JsonResponse(['error' => 'Invalid system parameter. Use "aci" or "shift4".'], 400);
        }

        // Получаем параметры из тела запроса
        $data = json_decode($request->getContent(), true);

        $requiredFields = ['amount', 'currency', 'card_number', 'card_exp_year', 'card_exp_month', 'card_cvv'];
        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                return new JsonResponse(['error' => "Missing required field: $field"], 400);
            }
        }

        // Заглушка для отправки запроса и формирования ответа
        $response = $this->sendRequestToSystem($system, $data);

        return new JsonResponse($response);
    }

    private function sendRequestToSystem(string $system, array $data): array
    {
        // Заглушка для реального запроса
        return [
            'transaction_id' => uniqid('trx_', true),
            'date_of_creation' => date('Y-m-d H:i:s'),
            'amount' => $data['amount'],
            'currency' => $data['currency'],
            'card_bin' => substr($data['card_number'], 0, 6),
            'system' => $system
        ];
    }
}
