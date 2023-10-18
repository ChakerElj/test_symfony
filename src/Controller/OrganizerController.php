<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Organizer;
use App\Form\EventFormType;
use App\Form\OrganizerFormType;
use App\Repository\EventRepository;
use App\Repository\OrganizerRepository;
use \Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
class OrganizerController extends AbstractController
{
    #[Route('/organizer', name: 'app_organizer')]
    public function index(): Response
    {
        return $this->render('organizer/index.html.twig', [
            'controller_name' => 'OrganizerController',
        ]);
    }
   #[Route("/organizer_add",name:"app_organizer_add")]
    public function ajoutOrganizer(ManagerRegistry $managerRegistry, OrganizerRepository $organizerRepository, Request $request):Response{
        $em = $managerRegistry->getManager();
        $organizer = new Organizer();
        $organizerForm = $this->createForm(OrganizerFormType::class,$organizer);
        $organizerForm->handleRequest($request);
        if ($organizerForm->isSubmitted()){

            $em->persist($organizer);
            $em->flush();
        }
        return $this->renderForm("organizer/organizerAddForm.html.twig", [
            "form" => $organizerForm
        ]);


    }
    #[Route("/organizers",name : "app_organizer_list")]
    public function listOrganizers(OrganizerRepository $organizerRepository):Response{
        $organizers = $organizerRepository->findAll();
        return $this->render("organizer/organizers.html.twig", [
            "organizers" => $organizers
        ]);
    }
    #[Route("/event_add",name : "app_event_add")]
    public function eventAdd(ManagerRegistry $managerRegistry, OrganizerRepository $organizerRepository, Request $request): Response {
        $em = $managerRegistry->getManager();
        $event = new Event();
        $eventForm = $this->createForm(EventFormType::class,$event);
        $eventForm->handleRequest($request);
        if ($eventForm->isSubmitted()){
            $em->persist($event);
            $em->flush();
        }
        return $this->renderForm("organizer/eventAddForm.html.twig", [
            "form" => $eventForm
        ]);
    }
    #[Route("/events",name : "app_events_list")]
    public function listevents(EventRepository $eventsRepository):Response{

        $events = $eventsRepository->findAll();
        return $this->render("event/events.html.twig", [
            "events" => $events
        ]);
    }

}
