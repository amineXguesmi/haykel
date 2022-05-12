<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
#[Route('/exam')]
class ExamController extends AbstractController
{
    #[Route('/acc', name: 'acc_exam')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $repositery=$doctrine->getRepository(Article::class);
        $a= $repositery->findBy([],['id'=>'DESC']);
        return $this->render('exam/index.html.twig',[
            'article'=>$a
        ]);
    }
    #[Route('/detail/{id}', name: 'detail_exam')]
    public function detail(ManagerRegistry $doctrine,Article $article): Response
    {
        return $this->render('exam/detail.html.twig',[
            'article'=>$article
        ]);
    }
    #[Route('/supp/{id}', name: 'del_exam')]
    public function DeleteArticle(ManagerRegistry $doctrine,Article $article=null): RedirectResponse
    {
        if($article){
            $Manager=$doctrine->getManager();
            $Manager->remove($article);
            $Manager->flush();
            $this->addFlash('success',"article supprimer avec success");
        }
        else{
            $this->addFlash('error',"tu ne peux pas supp ce id car il n'existe pas");
        }
        return $this->redirectToRoute('acc_exam');

    }
    #[Route('/add', name: 'add_exam')]
    public function addArticle(ManagerRegistry $doctrine,Request $request): Response
    {  $entityManager=$doctrine->getManager();
        $e=new Article();
        $form=$this->createForm(ArticleType::class,$e);
        $form->handleRequest($request);
        if(!$form->isSubmitted()){
            return $this->render('exam/formulaire.html.twig',[
                'form'=>$form->createView(),
            ]);}
        else{
            $entityManager->persist($e);
            $entityManager->flush();
            $this->addFlash('success',$e->getNom().' est ajouté avec success');
            return $this->redirectToRoute('acc_exam');
        }
    }
    #[Route('/edit/{id?0}', name: 'edit_exam')]
    public function add(ManagerRegistry $doctrine,Article $article= null,Request $request): Response
    {  $entityManager=$doctrine->getManager();
        $new=false;
        if(!$article){
            $article=new Article();
            $new=true;
        }
        $form=$this->createForm(ArticleType::class,$article);
        $form->handleRequest($request);

        if(!$form->isSubmitted()){
            return $this->render('exam/formulaire.html.twig',[
                'form'=>$form->createView(),
            ]);}
        else{
            $entityManager->persist($article);
            $entityManager->flush();
            if(!$new){
                $this->addFlash('success',$article->getNom().' est edité avec success');}
            else{$this->addFlash('success',$article->getNom().' est ajouté avec success');}
        }
        return $this->redirectToRoute('acc_exam');
    }
}
