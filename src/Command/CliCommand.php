<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\ExternalSystemManager;
use App\Service\ExternalSystemManager\RequestDto;
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
        readonly private ValidatorInterface    $validator,
        readonly private ExternalSystemManager $externalSystemManager,
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
        if (!$this->externalSystemManager->isAvailable($system)) {
            $output->writeln(
                sprintf(
                    '<error>Invalid system parameter. Available systems: %s.</error>',
                    implode(', ', $this->externalSystemManager->getAvailableIdList())
                )
            );
            return Command::INVALID;
        }

        $requestDto = new RequestDto();
        $requestDto->amount = $input->getOption('amount');
        $requestDto->currency = $input->getOption('currency');
        $requestDto->cardNumber = $input->getOption('card_number');
        $requestDto->cardExpYear = $input->getOption('card_exp_year');
        $requestDto->cardExpMonth = $input->getOption('card_exp_month');
        $requestDto->cardCvv = $input->getOption('card_cvv');

        $errors = $this->validator->validate($requestDto);
        if (count($errors) > 0) {
            foreach ($errors as $error) {
                $output->writeln(sprintf(
                    "<error>%s: %s</error>",
                    $error->getPropertyPath(),
                    $error->getMessage(),
                ));
            }
            return Command::FAILURE;
        }

        try {
            $response = $this->externalSystemManager->process($system, $requestDto);
        } catch (\Throwable $e) {
            $output->writeln(sprintf("<error>%s</error>", $e->getMessage()));

            return Command::FAILURE;
        }

        $output->writeln(json_encode($response->toArray(), JSON_PRETTY_PRINT));

        return Command::SUCCESS;
    }
}
