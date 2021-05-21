<?php

namespace App\Controller;

use App\Entity\OrderContent;
use App\Form\OrderContentType;
use App\Repository\OrderContentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/order/content")
 */
class OrderContentController extends AbstractController
{
    /**
     * @Route("/", name="order_content_index", methods={"GET"})
     */
    public function index(OrderContentRepository $orderContentRepository): Response
    {
        return $this->render('order_content/index.html.twig', [
            'order_contents' => $orderContentRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="order_content_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $orderContent = new OrderContent();
        $form = $this->createForm(OrderContentType::class, $orderContent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($orderContent);
            $entityManager->flush();

            return $this->redirectToRoute('order_content_index');
        }

        return $this->render('order_content/new.html.twig', [
            'order_content' => $orderContent,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="order_content_show", methods={"GET"})
     */
    public function show(OrderContent $orderContent): Response
    {
        return $this->render('order_content/show.html.twig', [
            'order_content' => $orderContent,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="order_content_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, OrderContent $orderContent): Response
    {
        $form = $this->createForm(OrderContentType::class, $orderContent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('order_content_index');
        }

        return $this->render('order_content/edit.html.twig', [
            'order_content' => $orderContent,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="order_content_delete", methods={"DELETE"})
     */
    public function delete(Request $request, OrderContent $orderContent): Response
    {
        if ($this->isCsrfTokenValid('delete'.$orderContent->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($orderContent);
            $entityManager->flush();
        }

        return $this->redirectToRoute('order_content_index');
    }
}
