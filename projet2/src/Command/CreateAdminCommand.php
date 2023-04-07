<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'create_admin',
    description: 'Create a admin account',
)]
class CreateAdminCommand extends Command
{
    private UserPasswordHasherInterface $passwordHasher;
    private ManagerRegistry $doctrine;

    public function __construct(UserPasswordHasherInterface $passwordHasher, ManagerRegistry $doctrine) {
        $this->passwordHasher = $passwordHasher;
        $this->doctrine = $doctrine;
        parent::__construct();
    }  
    
    protected function configure(): void
    {
        $this
            ->addArgument('login', InputArgument::REQUIRED, 'Argument login')
            ->addArgument('password', InputArgument::REQUIRED, 'Argument password')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $admin = new User();
        $login = $input->getArgument('login');
        $password = $input->getArgument('password');
        $hashedpassword = $this->passwordHasher->hashPassword(
            $admin,
            $password
        );
    
        $admin->setLogin($login);
        $admin->setMDP($hashedpassword);
        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($admin);
        $entityManager->flush();

        $output->writeln([
            'Création Adminstrateur',
            '============',
            '',
        ]);
        
        return Command::SUCCESS;

        /*$io = new SymfonyStyle($input, $output);
        $arg1 = $input->getArgument('arg1');

        if ($arg1) {
            $io->note(sprintf('You passed an argument: %s', $arg1));
        }

        if ($input->getOption('option1')) {
            // ...
        }

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;*/
        
    }
   
}
