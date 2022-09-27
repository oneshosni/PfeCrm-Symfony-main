<?php

namespace App\Controller;

use App\Entity\DemandeInfo;
use App\Form\DemandeInfoType;
use App\Repository\DemandeInfoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/demande/info")
 */
class DemandeInfoController extends AbstractController
{
    /**
     * @Route("/", name="app_demande_info_index", methods={"GET"})
     */
    public function index(DemandeInfoRepository $demandeInfoRepository): Response
    {
        return $this->render('demande_info/index.html.twig', [
            'demande_infos' => $demandeInfoRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_demande_info_new", methods={"GET", "POST"})
     */
    public function new(Request $request, DemandeInfoRepository $demandeInfoRepository): Response
    {
        $demandeInfo = new DemandeInfo();
        $form = $this->createForm(DemandeInfoType::class, $demandeInfo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $demandeInfoRepository->add($demandeInfo);
            return $this->redirectToRoute('app_demande_info_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('demande_info/new.html.twig', [
            'demande_info' => $demandeInfo,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_demande_info_show", methods={"GET"})
     */
    public function show(DemandeInfo $demandeInfo): Response
    {
        return $this->render('demande_info/show.html.twig', [
            'demande_info' => $demandeInfo,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_demande_info_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, DemandeInfo $demandeInfo, DemandeInfoRepository $demandeInfoRepository): Response
    {
        $form = $this->createForm(DemandeInfoType::class, $demandeInfo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $demandeInfoRepository->add($demandeInfo);
            return $this->redirectToRoute('app_demande_info_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('demande_info/edit.html.twig', [
            'demande_info' => $demandeInfo,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_demande_info_delete", methods={"POST"})
     */
    public function delete(Request $request, DemandeInfo $demandeInfo, DemandeInfoRepository $demandeInfoRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$demandeInfo->getId(), $request->request->get('_token'))) {
            $demandeInfoRepository->remove($demandeInfo);
        }

        return $this->redirectToRoute('app_demande_info_index', [], Response::HTTP_SEE_OTHER);
    }
}
