<?php

namespace App\Controller;

use App\Entity\Livre;
use App\Form\LivreType;
use App\Repository\CategoryRepository;
use App\Repository\LivreRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class LivreController extends AbstractController
{
    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->parameterBag = $parameterBag;
    }
    
    #[Route('/livre', name: 'app_livre_index', methods: ['GET'])]
    public function index(LivreRepository $livreRepository, CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();
        return $this->render('maleklivre/index.html.twig', [
            'livres' => $livreRepository->findAll(),
            'categories' => $categories,
        ]);
    }

    #[Route('livre/{id}', name: 'app_livre_show', methods: ['GET'])]
    public function show(Livre $livre): Response
    {
        return $this->render('livre/show.html.twig', [
            'livre' => $livre,
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('admin/livre', name: 'admin_app_livre_index', methods: ['GET'])]
    public function adminIndex(LivreRepository $livreRepository): Response
    {
        return $this->render('livre/index.html.twig', [
            'livres' => $livreRepository->findAll(),
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('admin/livre/new', name: 'app_livre_new', methods: ['GET', 'POST'])]
    public function new(Request $request, LivreRepository $livreRepository): Response
    {
        $livre = new Livre();
        $form = $this->createForm(LivreType::class, $livre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $img = $form['image']->getData();
            $imgNewName = explode('.', $img->getClientOriginalName())[0] . '-' . strtotime(date('Y-m-d H:i:s')) . '.' . $img->guessExtension();
            $directory = $this->parameterBag->get('kernel.project_dir') . "\public\img\livre";

            $img->move($directory, $imgNewName);

            $livre->setImage($imgNewName);

            $livreRepository->save($livre, true);

            return $this->redirectToRoute('admin_app_livre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('livre/new.html.twig', [
            'livre' => $livre,
            'form' => $form,
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('admin/livre/{id}/edit', name: 'app_livre_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Livre $livre, LivreRepository $livreRepository): Response
    {
        $form = $this->createForm(LivreType::class, $livre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $img = $form['image']->getData();
            $imgNewName = explode('.', $img->getClientOriginalName())[0] . '-' . strtotime(date('Y-m-d H:i:s')) . '.' . $img->guessExtension();
            $directory = $this->parameterBag->get('kernel.project_dir') . "\public\img\livre";

            $img->move($directory, $imgNewName);

            $livre->setImage($imgNewName);
            
            $livreRepository->save($livre, true);

            return $this->redirectToRoute('admin_app_livre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('livre/edit.html.twig', [
            'livre' => $livre,
            'form' => $form,
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('admin/livre/{id}', name: 'app_livre_delete', methods: ['POST'])]
    public function delete(Request $request, Livre $livre, LivreRepository $livreRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$livre->getId(), $request->request->get('_token'))) {
            $livreRepository->remove($livre, true);
        }

        return $this->redirectToRoute('admin_app_livre_index', [], Response::HTTP_SEE_OTHER);
    }
}
