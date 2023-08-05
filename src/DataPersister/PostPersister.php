<?php

namespace App\DataPersister;


class PostPersister implements DataPersisterInterface {

    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function supports($data): bool
    {
        return $data instanceof Post;
    }

    public function persist($data){

        //Mettre une date de création
        $data->setCreatedAt(new \DateTime());

        //inserer réellement les données
        $this->em->persist($data);
        $this->em->flush();

    }

    public function remove($data){
        $this->em->remove($data);
        $this->em->flush();
    }

}