<?php

namespace App\Command;
use App\Service\DocumentService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FetchDocumentsCommand extends Command
{
    private $documentService;

    public function __construct(DocumentService $documentService)
    {
        parent::__construct();
        $this->documentService = $documentService;
    }

    protected function configure()
    {
        $this->setName('app:fetch-documents')
             ->setDescription('Fetch and store documents from an external API');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $this->documentService->fetchAndStoreDocuments('https://educhain.free.beeceptor.com/get-documents');
            $output->writeln('Documents fetched and stored successfully.');
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $output->writeln('Error: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
?>
