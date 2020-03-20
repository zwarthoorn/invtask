<?php

namespace App\Controller;

use App\Entity\Person;
use App\Form\PersonType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(Request $request)
    {

        $person = new Person();

        $form = $this->createForm(PersonType::class, $person, [
            'entityManager' => $this->getDoctrine()->getManager(),
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $person = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($person);
            $entityManager->flush();

            return $this->redirectToRoute('success');
        }

        return $this->render('home/index.html.twig', [
            'form' => $form->createView(),
            'controller_name' => 'HomeController',
        ]);
    }

    /**
     * @Route("/succes", name="success")
     */
    public function succes()
    {
        return $this->render('home/succes.html.twig', [
        ]);
    }

    /**
     * @Route("/allpersons", name="allperson")
     */
    public function allpersons()
    {
        $persons = $this->getDoctrine()->getManager()->getRepository(Person::class)->findAll();


        $response = [];

        foreach ($persons as $person) {
            $response[] = [
                'voornaam' => $person->getFirstname(),
                'tussenvoegsel' => $person->getSuffix(),
                'achternaam' => $person->getLastname(),
                'email' => $person->getEmail(),
                'country' => $person->getCountry()->getName()
            ];
        }

        return new JsonResponse(json_encode($response));
    }
}
