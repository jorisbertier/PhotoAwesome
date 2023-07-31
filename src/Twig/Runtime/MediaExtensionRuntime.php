<?php

namespace App\Twig\Runtime;

use App\Repository\MediaRepository;
use Twig\Extension\RuntimeExtensionInterface;

class MediaExtensionRuntime implements RuntimeExtensionInterface
{
    public function __construct(private MediaRepository $mediaRepository)
    {
    
    }

    public function getLastFiveMedia()
    {
        return $this->mediaRepository->findLastFiveMedia();
    }
}
