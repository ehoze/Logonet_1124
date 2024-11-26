<?php

namespace App\Controller;

use App\Repository\TemplateRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class TemplateSearchController extends AbstractController
{
    #[Route('/admin/template/{systemName}/search', name: 'template_search', methods: ['GET'])]
    public function search(
        string $systemName,
        Request $request,
        TemplateRepository $templateRepository
    ): Response {
        $template = $templateRepository->findOneBy(['systemName' => $systemName]);

        $templates = $this->getTemplates($templateRepository);

        if (!$template) {
            if ($systemName !== '__start') {
                $this->addFlash('danger', 'Template not found. Showing first available template.');
            }
            return $this->redirectToFirstTemplate($templateRepository);
        }

        return $this->render('template/search.html.twig', [
            'template' => $template,
            'templates' => $templates,
            'query' => $request->query->all()
        ]);
    }

    public function getTemplates(TemplateRepository $templateRepository): array
    {
        return $templateRepository->findAll();
    }

    public function redirectToFirstTemplate(TemplateRepository $templateRepository): Response
    {
        $templates = $this->getTemplates($templateRepository);
        return $this->redirectToRoute('template_search', ['systemName' => $templates[0]->getSystemName()]);
    }
} 