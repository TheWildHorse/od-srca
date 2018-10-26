<?php

namespace App\Controller;

use App\Entity\Wishes;
use App\Form\GrantFormType;
use App\Form\WishFormType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends Controller
{

    /**
     * @Route("/", name="home", methods={"GET"})
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getHomepage()
    {
        return $this->render('homepage.html.twig');
    }

    /**
     * @Route(path="/wishes", name="wish.list", methods={"GET"})
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function wishList()
    {
        $entries = $this->getDoctrine()
            ->getRepository('App:Wishes')
            ->findAll();
        $data = [];

        foreach ($entries as $entry) {

            if($entry->getWishImage() != null)
                $img = (stream_get_contents($entry->getWishImage()));
            else
                $img = "N/A";

            array_push($data,[
                'id' => $entry->getId(),
                'name' => $entry->getName(),
                'location' => $entry->getLocation(),
                'wish' => $entry->getWish(),
                'wishImage' => $img,
                'isGranted' => $entry->getIsGranted()
                ]);
        }
        return $this->render('wish/list.html.twig', [
            'entries' => $data
        ]);
    }

    /**
     * @Route(path="/wish/create", name="wish.create")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function wishEntryCreate(Request $request)
    {
        $entry = new Wishes();
        $form = $this->createForm(WishFormType::class);
        $form->setData($entry);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $this->getDoctrine()
                ->getRepository('App:Wishes')
                ->createWish($entry);
            return $this->redirectToRoute('wish.list');
        }

        return $this->render('wish/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route(path="/wish/realize/{entryId}", name="wish.realize")
     * @param $entryId
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function wishEntryRealize($entryId, Request $request)
    {
        $entry = $this->getDoctrine()
            ->getRepository('App:Wishes')
            ->findOneBy([
                'id' => $entryId
            ]);
        if ($entry === null) {
            return $this->redirectToRoute('wish.list');
        }

        $form = $this->createForm(GrantFormType::class);
        $form->setData($entry);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $this->getDoctrine()->getManager()->persist($entry);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('wish.list');
        }

        return $this->render('wish/realize.html.twig', [
            'form' => $form->createView()
        ]);
    }
}