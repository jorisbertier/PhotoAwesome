<?php

namespace App\Command;

use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:create-category',
    description: 'Add a short description for your command',
)]
class CreateCategoryCommand extends Command
{
    public function __construct(private EntityManagerInterface $entityManager) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('categoryName', InputArgument::REQUIRED, 'nom de la categorie');
        // $this
        //     ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
        //     ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        // ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // $io = new SymfonyStyle($input, $output);
        // $arg1 = $input->getArgument('arg1');

        // if ($arg1) {
        //     $io->note(sprintf('You passed an argument: %s', $arg1));
        // }

        // if ($input->getOption('option1')) {
        //     // ...
        // }

        // $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        $categoryName = $input->getArgument('categoryName');
        $output->writeln($categoryName);

        $categoryEntity  = new Category();
        $categoryEntity->setLabel($categoryName);

        $this->entityManager->persist($categoryEntity);
        $this->entityManager->flush();

        $output->writeln("L'entité ".$categoryName." a été envoyé en bdd");
        
        return Command::SUCCESS;
    }
}
