<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Post;


class ApiPostController extends AbstractController
{

//================================================================================================================
    
 #[Route('/api/post', name: 'api_get_index', methods: ['GET'])]
 public function index(PostRepository $postRepository): Response
 {
     return $this->json($postRepository->findAll(), 200, [], ['groups' => 'post:read']);
 }
 
//================================================================================================================

 #[Route('/api/post/{id}', name:'api_get_show', methods: ['GET'])]
 public function show(Post $post):Response 
 {
     return $this->json($post, 200, [], ['groups' => 'post:read']);
 }

//================================================================================================================

 #[Route('/api/post', name: 'api_post_new', methods: ['POST'])]
 public function new(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, ValidatorInterface $validator): Response
 {
     $jsonRecu = $request->getContent();

     try {
         $post = $serializer->deserialize($jsonRecu, Post::class, 'json');
         $post->setCreateAt(new \DateTime());

         $errors = $validator->validate($post);
         if(count($errors) > 0){
             return $this->json($errors, 400);
         }

         $em->persist($post);
         $em->flush();
         return $this->json($post, 201, [], ['groups' => 'post:read']);

     } catch (NotEncodableValueException $e) {
         return $this->json([
             'status' => 400,
             'message' => $e->getMessage()
         ], 400);
     }
 }


  /*  #[Route('api/post', name:'api_post_new', methods:['POST'])]
    public function new(Request $request, EntityManagerInterface $em, SerializerInterface $serializer, ValidatorInterface $validator): Response
    {

        $jsonReq = $request->getContent();
        try {
            
            $post = $serializer->deserialize($jsonReq, Post::class , 'json');
            $post->setCreateAt(new \DateTime());

            $errors = $validator->validate($post);
            if(count($errors) > 0){
                return $this->json($errors, 400);
            }

            $em->persist($post);
            $em->flush();
            return $this->json($post);

        } catch(NotEncodableValueException $e) {
            return $this->json([
                'status' => 400,
                'Message' => 'insert failed'
            ], 400);
        }
    }
*/
//================================================================================================================


 #[Route('/api/post/{id}', name: 'api_post_delete', methods: ['POST'])]
 public function delete(Request $request, Post $post, EntityManagerInterface $entityManager): Response
 {
        
    $entityManager->remove($post);
    $entityManager->flush();

    return $this->json($post, 201, [], ['groups' => 'post:read']);
 }
 

   
//================================================================================================================

}
