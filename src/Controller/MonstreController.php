<?php
namespace App\Controller;

use App\Entity\Monstre;
use App\Entity\Royaume;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class MonstreController extends AbstractController
{
    public function sayHello()
    {
        return new Response("Hello motherfuckers !!");
    }

    public function ajouterMonstre(Request $request)
    {
        $monstre = new Monstre;
        $form = $this->createFormBuilder($monstre)
            ->add('nom', TextType::class)
            ->add('type', TextType::class)
            ->add('puissance', IntegerType::class)
            ->add('taille', IntegerType::class)
            ->add('royaume', EntityType::class, [
                'class' => Royaume::class,
                'choice_label' => 'nom', // replace 'name' with the property you want to display
            ])
            ->add('Ajouter', SubmitType::class)
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $monstre->getRoyaume()->addMonstre($monstre);
            $entityManager->persist($monstre);
            $entityManager->flush();
            return new Response('Monstre ajouté');
        }
        return $this->render(
            'ajouterMonstre.html.twig',
            array('monFormulaire' => $form->createView())
        );

    }
    public function ajouterRoyaume(Request $request)
    {
        $royaume = new Royaume;
        $form = $this->createFormBuilder($royaume)
            ->add('nom', TextType::class)
            ->add('Ajouter', SubmitType::class)
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($royaume);
            $entityManager->flush();
            return new Response('Royaume ajouté');
        }
        return $this->render(
            'ajouterMonstre.html.twig',
            array('monFormulaire' => $form->createView())
        );

    }
    public function afficherMonstre($id)
    {
        $repo = $this->getDoctrine()->getRepository(Monstre::class);
        $monstre = $repo->find($id);
        return $this->render("showMonsterByID.html.twig", array("monstre" => $monstre));
    }
    public function afficherRoyaume($id)
    {
        $repo = $this->getDoctrine()->getRepository(Royaume::class);
        $royaume = $repo->find($id);
        return $this->render("showRoyaumeByID.html.twig", array("royaume" => $royaume));
    }

    public function afficherTousMonstres()
    {
        $repo = $this->getDoctrine()->getRepository(Monstre::class);
        $monstres = $repo->findAll();
        return $this->render("tous_les_monstres.html.twig", array("monstres" => $monstres));
    }

    public function afficherTousRoyaumes()
    {
        $repo = $this->getDoctrine()->getRepository(Royaume::class);
        $royaumes = $repo->findAll();
        $data = [];

        foreach ($royaumes as $royaume) {
            $monstresCount = [];
            foreach ($royaume->getMonstres() as $monstre) {
                $type = $monstre->getType();
                if (!isset($monstresCount[$type])) {
                    $monstresCount[$type] = 1;
                } else {
                    $monstresCount[$type]++;
                }
            }
            $data[] = [
                "nom" => $royaume->getNom(),
                "monstresCount" => $monstresCount
            ];
        }
        return $this->render("tous_les_royaumes.html.twig", array("data" => $data));
    }

    public function modifierMonstre($id, Request $request)
    {
        $repo = $this->getDoctrine()->getRepository(Monstre::class);
        $monstre = $repo->find($id);
        $form = $this->createFormBuilder($monstre)
            ->add("nom", TextType::class)
            ->add("type", TextType::class)
            ->add("taille", IntegerType::class)
            ->add("puissance", IntegerType::class)
            ->add('royaume', EntityType::class, [
                'class' => Royaume::class,
                'choice_label' => 'nom',
            ])
            ->add("modifier", SubmitType::class)
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $monstre->getRoyaume()->addMonstre($monstre);
            $em->flush();
            return new Response("Monstre modifié");
        }
        return $this->render("modifier_monstre.html.twig", array("monFormulaire" => $form->createView()));
    }

    public function modifierRoyaume($id, Request $request)
    {
        $repo = $this->getDoctrine()->getRepository(Royaume::class);
        $royaume = $repo->find($id);
        $form = $this->createFormBuilder($royaume)
            ->add("nom", TextType::class)
            ->add("modifier", SubmitType::class)
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return new Response("Royaume modifié");
        }
        return $this->render("modifier_monstre.html.twig", array("monFormulaire" => $form->createView()));
    }

    public function supprimerMonstre($id)
    {
        $repo = $this->getDoctrine()->getRepository(Monstre::class);
        $monstre = $repo->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($monstre);
        $em->flush();
        return new Response("Monstre supprimé");
    }
    public function supprimerRoyaume($id)
    {
        $repo = $this->getDoctrine()->getRepository(Royaume::class);
        $royaume = $repo->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($royaume);
        $em->flush();
        return new Response("Royaume supprimé");
    }

    public function monstresParType($type)
    {
        $repo = $this->getDoctrine()->getRepository(Monstre::class);
        $monstres = $repo->findBy(array("type" => $type));
        return $this->render("tous_les_monstres.html.twig", array("monstres" => $monstres));
    }

    public function transferMonstres($royaumeA, $royaumeB)
    {
        $repo = $this->getDoctrine()->getRepository(Royaume::class);
        $em = $this->getDoctrine()->getManager();
        $royaumeA = $repo->find($royaumeA);
        $royaumeB = $repo->find($royaumeB);
        foreach ($royaumeA->getMonstres() as $monstre) {
            $monstre->setRoyaume($royaumeB);
        }
        foreach ($royaumeB->getMonstres() as $monstre) {
            $monstre->setRoyaume($royaumeA);
        }

        $em->flush();
        return new Response("Monstres transmit!");
    }

    public function action15()
    {
        $repo = $this->getDoctrine()->getManager()->getRepository(Monstre::class);
        $monstresRoyaumes = $repo->getMonstresEventuellementRoyaume();
        $result = ' ';
        foreach ($monstresRoyaumes as $monstre)
            $result .= $monstre['nom'] . ' : ' . $monstre['id'] . '<br />';
        return new Response('<html><body>' . $result . '</body></html>');
    }

    public function action16($nom)
    {
        $repo = $this->getDoctrine()->getManager()->getRepository(Monstre::class);
        $monstresRoyaumes = $repo->action16($nom);
        $result = ' ';
        foreach ($monstresRoyaumes as $monstre)
            $result .= $monstre['nom'] . ' : ' . $monstre['id'] . '<br />';
        return new Response('<html><body>' . $result . '</body></html>');
    }

}