<?php

namespace App\Controller;

use App\Entity\Author;
use App\Form\AuthorFormType;
use App\Repository\AuthorRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthorController extends AbstractController
{
    private $authors = array(
        array('id' => 1, 'picture' => '/images/Victor-Hugo.jpg', 'username' => 'Victor Hugo', 'email' =>
            'victor.hugo@gmail.com ', 'nb_books' => 100),
        array('id' => 2, 'picture' => '/images/william-shakespeare.jpg', 'username' => ' William Shakespeare', 'email' =>
            ' william.shakespeare@gmail.com', 'nb_books' => 200),
        array('id' => 3, 'picture' => '/images/Taha_Hussein.jpg', 'username' => 'Taha Hussein', 'email' =>
            'taha.hussein@gmail.com', 'nb_books' => 300),
    );
    #[Route('/authors', name: 'app_author')]
    public function listAuthors(AuthorRepository $authorRepository): Response
    {
        $authors_data = $authorRepository ->findAll();
        return $this->render('author/authors.html.twig', [
            'authors' => $authors_data,
        ]);
    }
    #[Route("/author_add_static",name: "app_author_add_static")]
     public function addAuthorStatic(AuthorRepository $authorRepository, ManagerRegistry $managerRegistry):Response{
       $entityManager =  $managerRegistry->getManager();
        $author = new Author();
        $author->setUsername("mootez");
        $author->setEmail("mootezelj@gmail.com");
        $entityManager->persist($author);
        $entityManager->flush();
        return $this->render('author/authorAddedSuccess.html.twig', [
            'msg' => 'author added successfully'
        ]);
    }
    #[Route("/author_add", name:"app_author_add")]
    public function addAuthor(ManagerRegistry $managerRegistry, AuthorRepository $authorRepository, Request $req): Response {
        $authorForm = $this->getAuthorForm();
        $authorForm->handleRequest($req);
        $author = new Author();
        $entityManager = $managerRegistry->getManager();
        if ($authorForm->isSubmitted()){
          $author->setUsername($authorForm->get("username"));
          $author->setEmail($authorForm->get("email"));
          $entityManager->persist($author);
          $entityManager->flush();
        }
        return $this->renderForm("author/authorForm.html.twig", [
            "form" => $authorForm
        ]);
    }
    public function getAuthorForm(): \Symfony\Component\Form\FormInterface
    {
        $author = new Author();
        return $this->createForm(AuthorFormType::class,$author);
    }

}
