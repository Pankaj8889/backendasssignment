<?php
namespace App\Service;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;

class DocumentService
{
    private $httpClient;
    private $filesystem;
    private $storagePath;

    public function __construct(HttpClientInterface $httpClient, Filesystem $filesystem, string $storagePath = 'documents')
    {
        $this->httpClient = $httpClient;
        $this->filesystem = $filesystem;
        $this->storagePath = $storagePath;
    }

    public function fetchAndStoreDocuments(string $url): void
    {
        $response = $this->httpClient->request('GET', $url);
        $documents = $response->toArray();

        $this->filesystem->mkdir($this->storagePath);

        foreach ($documents as $document) {
            $decoded = base64_decode($document['certificate']);
            $filename = sprintf('%s_%s.pdf', $document['description'], $document['doc_no']);
            file_put_contents($this->storagePath . '/' . $filename, $decoded);
        }
    }
}
?>