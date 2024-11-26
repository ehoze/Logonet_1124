<?php

namespace App\Twig\Components;

use App\Entity\Template;
use App\Repository\PostRepository;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent(name: 'template_search')]
final class TemplateSearchComponent
{
    use DefaultActionTrait;

    #[LiveProp]
    public Template $template;

    #[LiveProp(writable: true)]
    public array $query = [];

    public function __construct(
        private readonly PostRepository $postRepository,
    ) {
    }

    public function getPosts(): array
    {
        return $this->postRepository->findByTemplateAndCriteria(
            $this->template,
            $this->query
        );
    }
} 