<?php

namespace App\Command;

use App\Entity\LegalStatus;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Serializer\SerializerInterface;

#[AsCommand(
    name: 'app:legal-statuses:import',
    description: 'Import legal statuses list from csv file to database',
)]
class LegalStatusImportCommand extends Command
{
    public const FILE_PATH = __DIR__.'/legal_statuses.csv';

    public function __construct(protected SerializerInterface $serializer, protected EntityManagerInterface $entityManager, string $name = null)
    {
        parent::__construct($name);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $io = new SymfonyStyle($input, $output);

            $data = file_get_contents(self::FILE_PATH);
            $import = $this->serializer->deserialize($data, LegalStatus::class.'[]', 'csv');

            foreach ($import as $item) {
                $this->entityManager->persist($item);
            }

            $this->entityManager->flush();

            $io->success('Legal statuses list is successfully imported.');

            return Command::SUCCESS;
        } catch (\Throwable $e) {
            $io->error(sprintf('An error occurred when processing import. %s', $e->getMessage()));

            return Command::FAILURE;
        }
    }
}
