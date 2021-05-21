<?php

namespace App\Controller;

use App\Entity\Whishlist;
use App\Form\WhishlistType;
use App\Repository\WhishlistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/whishlist")
 */
class WhishlistController extends AbstractController
{
    /**
     * @Route("/", name="whishlist_index", methods={"GET"})
     */
    public function index(WhishlistRepository $whishlistRepository): Response
    {
        return $this->render('whishlist/index.html.twig', [
            'whishlists' => $whishlistRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="whishlist_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $whishlist = new Whishlist();
        $form = $this->createForm(WhishlistType::class, $whishlist);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($whishlist);
            $entityManager->flush();

            return $this->redirectToRoute('whishlist_index');
        }

        return $this->render('whishlist/new.html.twig', [
            'whishlist' => $whishlist,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="whishlist_show", methods={"GET"})
     */
    public function show(Whishlist $whishlist): Response
    {
        return $this->render('whishlist/show.html.twig', [
            'whishlist' => $whishlist,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="whishlist_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Whishlist $whishlist): Response
    {
        $form = $this->createForm(WhishlistType::class, $whishlist);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('whishlist_index');
        }

        return $this->render('whishlist/edit.html.twig', [
            'whishlist' => $whishlist,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="whishlist_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Whishlist $whishlist): Response
    {
        if ($this->isCsrfTokenValid('delete'.$whishlist->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($whishlist);
            $entityManager->flush();
        }

        return $this->redirectToRoute('whishlist_index');
    }
}
