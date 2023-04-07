<?php
namespace App\Command;

use App\Entity\User;
use App\Entity\Objet;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'create_object',
    description: 'Create a object',
)]
class CreateObjectCommand extends Command
{
    // ...

    private $entityManager;

    public function __construct(ManagerRegistry $doctrine) {
        $this->doctrine = $doctrine;
        parent::__construct();
    }  

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $entityManager = $this->doctrine->getManager();
        for ($i = 1; $i < 10; $i++) {
            $object = new Objet();
            $object->setTitre("samsung " .$i);
            $object->setDescription("samsung ".$i);
            $object->setPrix($i*200);

            $entityManager->persist($object);
        }

        $entityManager->flush();

        $output->writeln('10 objets ont été créés.');

        return Command::SUCCESS;
    }
}