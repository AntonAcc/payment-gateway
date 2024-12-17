<?php

declare(strict_types=1);

namespace App\Command;

use App\Dto\TransactionData;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[AsCommand(
    name: 'app:example',
    description: 'Add a short description for your command',
)]
class CliCommand extends Command
{
    public function __construct(
        readonly private ValidatorInterface $validator
    ) {
        parent::__construct();
    }

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

        $dto = new TransactionData();
        $dto->amount = $input->getOption('amount');
        $dto->currency = $input->getOption('currency');
        $dto->cardNumber = $input->getOption('card_number');
        $dto->cardExpYear = $input->getOption('card_exp_year');
        $dto->cardExpMonth = $input->getOption('card_exp_month');
        $dto->cardCvv = $input->getOption('card_cvv');

        $errors = $this->validator->validate($dto);
        if (count($errors) > 0) {
            foreach ($errors as $error) {
                $output->writeln("<error>{$error->getPropertyPath()}: {$error->getMessage()}</error>");
            }
            return Command::FAILURE;
        }

        $response = $this->sendRequestToSystem($system, $dto);

        $output->writeln(json_encode($response, JSON_PRETTY_PRINT));

        return Command::SUCCESS;
    }

    private function sendRequestToSystem(string $system, TransactionData $dto): array
    {
        return [
            'transaction_id' => uniqid('trx_', true),
            'date_of_creation' => date('Y-m-d H:i:s'),
            'amount' => $dto->amount,
            'currency' => $dto->currency,
            'card_bin' => substr($dto->cardNumber, 0, 6),
            'system' => $system
        ];
    }
}
