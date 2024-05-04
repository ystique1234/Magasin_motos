<?php

namespace App\Controller;

use App\Entity\Moto;
use App\Form\MotoType;
use App\Repository\MotoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MotoController extends AbstractController
{
    #[Route('/moto', name: 'app_moto_index')]
    public function index(Request $request,MotoRepository $repository,EntityManagerInterface $em): Response
    {

        $motos = $em->getRepository(Moto::class)->findAll();
        $moto = new Moto;
        $moto->setNom('Harley Davidson Fat Boy');
        $moto->setMarque('harley-davidson');
        $moto->setCouleur('bleu');
        $moto->setAnnee(1903);
        $moto-> setPrix (2000);
        $em ->persist($moto);
        // $em->flush();

        $moto = new Moto;
        $moto->setNom('Honda Cb');
        $moto->setMarque('honda');
        $moto->setCouleur('gris');
        $moto->setAnnee(1910);
        $moto-> setPrix (1500);
        $em ->persist($moto);
        // $em->flush();

        
        $moto = new Moto;
        $moto->setNom('Yamaha Mt');
        $moto->setMarque('yamaha');
        $moto->setCouleur('noir');
        $moto->setAnnee(1905);
        $moto-> setPrix (2300);
        $em ->persist($moto);
        // $em->flush();
          
         
        $moto = new Moto;
        $moto->setNom('Ducati Panigale');
        $moto->setMarque('ducati');
        $moto->setCouleur('Rouge');
        $moto->setAnnee(1920);
        $moto-> setPrix (3300);
        $em ->persist($moto);
        // $em->flush();

        return $this->render('moto/index.html.twig', ['motos'=>$motos]);
    }
    #[Route('/moto/{slug}-{id}', name: 'app_moto_show',requirements : ['id'=>'\d+','slug'=>'[a-z0-9-]+'])]
    public function show(Request $request,string $slug, int $id,MotoRepository $repository): Response
    {

        $moto =$repository->find($id);
        if($moto->getMarque() !== $slug){
            return $this->redirectToRoute('app_moto_show',['id' => $moto->getId(),'slug' => $moto->getMarque()]);
        }
    return $this->render('moto/show.html.twig', [
        // 'slug' => $slug,
        // 'id' => $id,
        // 'user' =>[
        //     "Firstname" => "Rose",
        //     "Lastname" => "Mystique"
        'moto'=>$moto

     ]);
    }

    #[Route(path: '/moto/{id}/edit', name: 'app_moto_edit')]
    public function edit(moto $moto,Request $request,EntityManagerInterface $em):Response
    {
        // dd($moto);
        // cette methode prend en premier parametre le formulaire que l'on souhaite utiliser
        // en second parametre elle prend les données
        $form= $this->createForm(MotoType::class, $moto);
        $form->handleRequest($request);
        // dd($moto);
        if ($form->isSubmitted() && $form->isValid()){
            $em->flush();
            $this->addFlash('success', 'La moto a bien été modifiée');
            // return $this->redirectToRoute('app_moto_index');
            return $this->redirectToRoute('app_moto_show',['id' =>
            $moto->getId(), 'slug' =>
            $moto->getMarque()]);
        } else {
            // Afficher les erreurs de validation
            $errors = $form->getErrors(true);
            foreach ($errors as $error) {
                $this->addFlash('error', $error->getMessage());
            }
        }
        return $this->render('moto/edit.html.twig',[
            'moto' => $moto,
            'monForm' =>$form
        ]);
        //  dd($moto);

    
    }
    #[Route(path: '/moto/create', name: 'app_moto_create')]
    public function create(Request $request,EntityManagerInterface $em):Response{
        $moto = new moto;
        $form = $this->createForm(MotoType::class,$moto);
        $form->handleRequest($request);
        if ($form->isSubmitted()&& $form->isValid()){
            $em->persist($moto);
            $em->flush();
            $this->addFlash('success','*La moto'.$moto->getMarque() . 'a bien été crééé');
            return $this->redirectToRoute('app_moto_index');
        }
        return $this->render('moto/create.html.twig', [
            'form' =>$form
        ]);

}
#[Route(path: '/moto/{id}/{delete}', name: 'app_moto_delete')]
    public function delete(moto $moto,EntityManagerInterface $em):Response{
        $titre = $moto->getMarque();
        $em->remove($moto);
        $em->flush();
        $this->addFlash('info', 'La moto'  .$titre.  'a bien été supprimée');
        return $this->redirectToRoute('app_moto_index');

}
}



