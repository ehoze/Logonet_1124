<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\TemplateType;
use App\Entity\Template;
use App\Repository\TemplateRepository;
use App\Security\TemplateVoter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\SubmitButton;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * Controller used to manage blog contents in the backend.
 *
 * Please note that the application backend is developed manually for learning
 * purposes. However, in your real Symfony application you should use any of the
 * existing bundles that let you generate ready-to-use backends without effort.
 * See https://symfony.com/bundles
 *
 * @author Ryan Weaver <weaverryan@gmail.com>
 * @author Javier Eguiluz <javier.eguiluz@gmail.com>
 */
#[Route('/admin/template')]
#[IsGranted(User::ROLE_ADMIN)]
final class TemplateController extends AbstractController
{
    // Template
    #[Route('/', name: 'admin_template_index', methods: ['GET'])]
    public function index(
        TemplateRepository $templates,
    ): Response {
        $templates = $templates->returnAllTemplates();

        return $this->render('admin/template/index.html.twig', ['templates' => $templates]);
    }

    /**
     * Creates a new Template entity.
     *
     * NOTE: the Method annotation is optional, but it's a recommended practice
     * to constraint the HTTP methods each controller responds to (by default
     * it responds to all methods).
     */
    #[Route('/new', name: 'admin_template_new', methods: ['GET', 'POST'])]
    public function newTemplate(
        #[CurrentUser] User $user,
        Request $request,
        EntityManagerInterface $entityManager,
    ): Response {
        $template = new Template();

        // See https://symfony.com/doc/current/form/multiple_buttons.html
        $form = $this->createForm(TemplateType::class, $template)
            ->add('saveAndCreateNew', SubmitType::class);

        $form->handleRequest($request);

        // The isSubmitted() call is mandatory because the isValid() method
        // throws an exception if the form has not been submitted.
        // See https://symfony.com/doc/current/forms.html#processing-forms
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($template);
            $entityManager->flush();

            // Flash messages are used to notify the user about the result of the
            // actions. They are deleted automatically from the session as soon
            // as they are accessed.
            // See https://symfony.com/doc/current/controller.html#flash-messages
            $this->addFlash('success', 'template.created_successfully');

            /** @var SubmitButton $submit */
            // $submit = $form->get('saveAndCreateNew');

            // if ($submit->isClicked()) {
            //     return $this->redirectToRoute('admin_template_new', [], Response::HTTP_SEE_OTHER);
            // }

            return $this->redirectToRoute('admin_template_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/template/new.html.twig', [
            'template' => $template,
            'form' => $form,
        ]);
    }

    /**
     * Finds and displays a Post entity.
     */
    #[Route('/{id<\d+>}', name: 'admin_template_show', methods: ['GET'])]
    public function showTemplate(Template $template): Response
    {
        // This security check can also be performed
        // using a PHP attribute: #[IsGranted('show', subject: 'post', message: 'Posts can only be shown to their authors.')]
        // $this->denyAccessUnlessGranted(TemplateVoter::SHOW, $template, 'Templates can only be shown to their authors.');
        // Można to w przyszłości zmienić

        return $this->render('admin/template/show.html.twig', [
            'template' => $template,
            'fields' => $template->getFields(),
        ]);
    }

    /**
     * Displays a form to edit an existing Post entity.
     */
    #[Route('/{id<\d+>}/template/edit', name: 'admin_template_edit', methods: ['GET', 'POST'])]
    // #[IsGranted('edit', subject: 'template', message: 'Templates can only be edited by their authors.')]
    public function editTemplate(Request $request, Template $template, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TemplateType::class, $template);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'post.updated_successfully');

            return $this->redirectToRoute('admin_template_edit', ['id' => $template->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/template/edit.html.twig', [
            'template' => $template,
            'form' => $form,
        ]);
    }

    /**
     * Deletes a Post entity.
     */
    #[Route('/{id}/delete', name: 'admin_template_delete', methods: ['POST'])]
    #[IsGranted('delete', subject: 'template')]
    public function deleteTemplate(Request $request, Template $template, EntityManagerInterface $entityManager): Response
    {
        /** @var string|null $token */
        $token = $request->request->get('token');

        if (!$this->isCsrfTokenValid('delete', $token)) {
            return $this->redirectToRoute('admin_template_index', [], Response::HTTP_SEE_OTHER);
        }

        // Delete the tags associated with this blog post. This is done automatically
        // by Doctrine, except for SQLite (the database used in this application)
        // because foreign key support is not enabled by default in SQLite
        $template->getFields()->clear();

        $entityManager->remove($template);
        $entityManager->flush();

        $this->addFlash('success', 'post.deleted_successfully');

        return $this->redirectToRoute('admin_template_index', [], Response::HTTP_SEE_OTHER);
    }
}
