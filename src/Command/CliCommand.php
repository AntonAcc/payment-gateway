<?php

declare(strict_types=1);

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:example',
    description: 'Add a short description for your command',
)]
class CliCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->setDescription('Send transaction to ACI or Shift4.')
            ->addArgument('system', InputArgument::REQUIRED, 'The target system (aci|shift4)')
            ->addOption('amount', null, InputOption::VALUE_REQUIRED, 'Transaction amount')
            ->addOption('currency', null, InputOption::VALUE_REQUIRED, 'Transaction currency')
            ->addOption('card_number', null, InputOption::VALUE_REQUIRED, 'Card number')
            ->addOption('card_exp_year', null, InputOption::VALUE_REQUIRED, 'Card expiry year')
            ->addOption('card_exp_month', null, InputOption::VALUE_REQUIRED, 'Card expiry month')
            ->addOption('card_cvv', null, InputOption::VALUE_REQUIRED, 'Card CVV');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $system = $input->getArgument('system');
        if (!in_array($system, ['aci', 'shift4'])) {
            $output->writeln('<error>Invalid system parameter. Use "aci" or "shift4".</error>');
            return Command::INVALID;
        }

        $requiredFields = ['amount', 'currency', 'card_number', 'card_exp_year', 'card_exp_month', 'card_cvv'];
        foreach ($requiredFields as $field) {
            if (!$input->getOption($field)) {
                $output->writeln("<error>Missing required option: --$field</error>");
                return Command::FAILURE;
            }
        }

        // Формируем данные
        $data = [
            'amount' => $input->getOption('amount'),
            'currency' => $input->getOption('currency'),
            'card_number' => $input->getOption('card_number'),
            'card_exp_year' => $input->getOption('card_exp_year'),
            'card_exp_month' => $input->getOption('card_exp_month'),
            'card_cvv' => $input->getOption('card_cvv'),
        ];

        // Заглушка для отправки запроса
        $response = $this->sendRequestToSystem($system, $data);

        $output->writeln(json_encode($response, JSON_PRETTY_PRINT));
        return Command::SUCCESS;
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
