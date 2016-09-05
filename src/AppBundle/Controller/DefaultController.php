<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route as Route;
use AppBundle\Entity\Trip;
use AppBundle\Form\TripType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $tripRepository = $this->getDoctrine()->getRepository('AppBundle:Trip');
        $trips = $tripRepository->findByUser($this->getUser());

        return $this->render('default/index.html.twig', [
            'trips' => $trips
        ]);

    }

    /**
     * @Route("/trip/new", name="app_trip_new")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        $trip = new Trip();
        $form = $this->createForm(TripType::class, $trip);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $file = $trip->getFile();

            $fileName = md5(uniqid()).'.'.$file->guessExtension();

            $file->move(
                $this->getParameter('gpx_directory'),
                $fileName
            );

            $trip->setFile($fileName);

            $trip->setUser($this->getUser());

            $em = $this->getDoctrine()->getManager();
            $em->persist($trip);
            $em->flush();

            return $this->redirect($this->generateUrl('homepage'));
        }

        return $this->render('trip/new.html.twig', array(
            'form' => $form->createView(),
        ));

    }

    /**
     * @Route("/trip/show/{tripId}", name="app_trip_show")
     * @param $tripId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction($tripId)
    {
        $trip = $this->getDoctrine()
            ->getRepository('AppBundle:Trip')
            ->find($tripId);

        if (!$trip) {
            throw $this->createNotFoundException(
                'No trip found for id '. $trip
            );
        }

        return $this->render('trip/show.html.twig', array(
            'trip' => $trip,
        ));
    }
}
