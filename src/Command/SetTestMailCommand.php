<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsCommand(
    name: 'app:set-test-mail',
    description: 'Add a short description for your command',
)]
class SetTestMailCommand extends Command
{

    public function __construct(
        private HttpClientInterface $httpClient
    ){
        parent::__construct();
    }
    
    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $response = $this->httpClient->request('POST', "https://api.brevo.com/v3/smtp/email/", [
            "headers" => [
                "accept" => "application/json",
                "api-key" => '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',
                "content-type" => "application/json",
            ],
            'json' => [
                "sender" => [
                    'name' => 'Joris Bertier',
                    'email' =>  'jorisbertier@gmailcom', 
                    ] //jules@drosalys.fr
                ],
                "to" => [
                   ['name' => 'Jules Pauly',
                    'email' => 'paulyjules@gmail.com',]
                ],
                "subject" => 'Yo tu as bien reçu?',
                "htmlContent" => "<p>Yo tu as bien reçu?</p>",
        ]);

        return Command::SUCCESS;
    }
}
