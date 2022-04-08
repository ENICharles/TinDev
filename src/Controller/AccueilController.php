<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{
    #[Route('/accueil', name: 'accueil')]
    public function index(): Response
    {
        return $this->render('accueil/index.html.twig');
    }

    #[Route('/', name: 'accueil_liste')]
    public function liste(UserRepository $ur): Response
    {
        $listeDevLoveur = $ur->findAll();

        return $this->render('accueil/all.html.twig',compact('listeDevLoveur'));
    }

    #[Route('/detail/{id}', name: 'accueil_detail')]
    public function detail(UserRepository $ur,User $usr): Response
    {
        return $this->render('accueil/detail.html.twig',compact('usr'));
    }

    #[Route('/filter/{param}', name: 'accueil_filter')]
    public function filter(UserRepository $ur,string $param): Response
    {
        $listeDevLoveur = $ur->findBy([$param => 1]);

        return $this->render('accueil/all.html.twig',compact('listeDevLoveur'));
    }


    #[Route('/friend/{id}', name: 'accueil_setFriend')]
    public function setFriend(UserRepository $ur,EntityManagerInterface $em,Request $request,User $usr): Response
    {
        $usrCurr = $ur->findBy(['email'=> $this->getUser()->getUserIdentifier()]);

        if($usrCurr[0]->isMyFriend($usr))
        {
            $usrCurr[0]->removeLstAmi($usr);
        }
        else
        {
            $usrCurr[0]->addLstAmi($usr);
        }

        $em->persist($usrCurr[0]);

        /* envoie en base */
        $em->flush();

        return $this->redirectToRoute('accueil_liste');
    }
}
