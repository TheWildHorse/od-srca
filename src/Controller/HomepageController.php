<?php

namespace App\Controller;

use App\Entity\Wishes;
use App\Form\GrantFormType;
use App\Form\WishFormType;
use Geocoder\ProviderAggregator;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Geocoder\Query\GeocodeQuery;


class HomepageController extends Controller
{
    /**
     * Calculates the great-circle distance between two points, with
     * the Vincenty formula.
     * @param float $latitudeFrom Latitude of start point in [deg decimal]
     * @param float $longitudeFrom Longitude of start point in [deg decimal]
     * @param float $latitudeTo Latitude of target point in [deg decimal]
     * @param float $longitudeTo Longitude of target point in [deg decimal]
     * @param float $earthRadius Mean earth radius in [m]
     * @return float Distance between points in [m] (same as earthRadius)
     */
    public function getDistance(
        $latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000)
    {
        // convert from degrees to radians
        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);
        $latTo = deg2rad($latitudeTo);
        $lonTo = deg2rad($longitudeTo);

        $lonDelta = $lonTo - $lonFrom;
        $a = pow(cos($latTo) * sin($lonDelta), 2) +
            pow(cos($latFrom) * sin($latTo) - sin($latFrom) * cos($latTo) * cos($lonDelta), 2);
        $b = sin($latFrom) * sin($latTo) + cos($latFrom) * cos($latTo) * cos($lonDelta);

        $angle = atan2(sqrt($a), $b);
        return ($angle * $earthRadius) / 1000;
    }

    /**
     * @Route("/", name="home", methods={"GET"})
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getHomepage()
    {
        $entries = $this->getDoctrine()
            ->getRepository('App:Wishes')
            ->findAll();

        $ordered = $this->getDoctrine()
            ->getRepository('App:Wishes')
            ->orderByCity();

        $prevCity = "";
        $count = 0;
        foreach ($ordered as $item) {
            if ($item->getLocation() != $prevCity) {
                $count++;
                $prevCity = $item->getLocation();
            }
        }

        $numberGifts = $numberKids = sizeof($entries);
        $numberCities = $count;

        return $this->render('homepage.html.twig', [
            'numberKids' => $numberKids,
            'numberGifts' => $numberGifts,
            'numberCities' => $numberCities,
        ]);
    }

    /**
     * @Route(path="/wishes", name="wish.list")
     * @param Request $request
     * @param ProviderAggregator $geocoder
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Geocoder\Exception\Exception
     */
    public function wishList(Request $request, ProviderAggregator $geocoder)
    {
        $city = $request->get('city');
        $clientDistance = $request->get('filter');
        if ($city == null or $clientDistance == null) {
            return $this->redirectToRoute('wish.location');
        }
        $geocoder->getProviders();
        $result = $geocoder->geocodeQuery(GeocodeQuery::create($city));
        $coords = $result->first()->getCoordinates();

        //get all entries from DB
        $entries = $this->getDoctrine()
            ->getRepository('App:Wishes')
            ->findBy([
                'isGranted' => false
            ]);

        //filter entries by distance
        $ids = [];
        foreach ($entries as $entry) {
            $dbCity = $geocoder->geocodeQuery(GeocodeQuery::create($entry->getLocation()));
            $dbCoords = $dbCity->first()->getCoordinates();
            $distance = $this->getDistance($coords->getLatitude(), $coords->getLongitude(), $dbCoords->getLatitude(), $dbCoords->getLongitude());
            if (floatval($distance) <= floatval($clientDistance))
                array_push($ids, $entry->getId());
        }

        //get entries by client distance
        $query = $entries = $this->getDoctrine()
            ->getRepository('App:Wishes')
            ->getByIds($ids);


        //show entries
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            4 /*limit per page*/
        );

        return $this->render('wish/list.html.twig', [
            'pagination' => $pagination
        ]);
    }

    /**
     * @Route(path="/wish/create", name="wish.create")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function wishEntryCreate(Request $request)
    {
        $entry = new Wishes();
        $form = $this->createForm(WishFormType::class);
        $form->setData($entry);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $file = $entry->getWishImage();
            if ($file != null) {
                $fileName = md5(uniqid()) . '.' . $file->guessExtension();
                $file->move(
                    $this->getParameter('image_directory'), $fileName
                );
                $entry->setWishImage($fileName);
            }

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
            $realizeEmail = $entry->getRealizeEmail();
            $wishEmail = $entry->getEmail();

            $entry->setIsGranted(true);
            $this->getDoctrine()->getManager()->persist($entry);
            $this->getDoctrine()->getManager()->flush();

            $currentHost = $request->getSchemeAndHttpHost();
            $this->sendEmail($realizeEmail, $wishEmail, $entry, $currentHost);
            return $this->redirectToRoute('wish.list');
        }

        return $this->render('wish/realize.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route(path="/wishes/location", name="wish.location")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getCity()
    {
        return $this->render('wish/geolocation.html.twig');
    }

    /**
     * @Route(path="/wish/email", name="wish.send_email")
     *
     */
    public function sendEmail($from, $to, $wish, $currentHost)
    {
        $message = (new \Swift_Message('Hvala Vam'))
            ->setFrom($from)
            ->setTo($to)
            ->setBody(
                $this->renderView(
                    'email.html.twig',
                    array(
                        'wishId' => $wish->getId(),
                        'currentHost' => $currentHost,
                        'name' => $wish->getName(),
                        'lastname' => $wish->getLastname(),
                        'email' => $wish->getEmail(),
                        'address' => $wish->getAddress(),
                        'phone' => $wish->getWishPhone(),
                        'wish' => $wish->getWish(),
                        'wishImage' => $wish->getWishImage(),
                    )
                ),
                'text/html'
            );
        $this->get('mailer')->send($message);
    }

    /**
     * @Route(path="/wish/undo/{entryId}", name="wish.undo")
     * @param $entryId
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function undoWish($entryId, Request $request)
    {
        $entry = $this->getDoctrine()
            ->getRepository('App:Wishes')
            ->findOneBy([
                'id' => $entryId
            ]);
        $entry->setIsGranted(false);
        $this->getDoctrine()->getManager()->persist($entry);
        $this->getDoctrine()->getManager()->flush();
        return $this->redirectToRoute('home');
    }
}