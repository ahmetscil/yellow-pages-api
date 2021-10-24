<?php
/**
 * KİŞİ LİSTESİ yönetimi
 * Tüm CRUD işlemleri gerçekleştirilir.
 * 
 * POST     http://localhost:8080/people        Yeni Kayıt
 * GET      http://localhost:8080/people        Tüm Liste
 * GET      http://localhost:8080/people/{id}   USER ID rehberi
 * PUT      http://localhost:8080/people/{id}   Update
 * DELETE   http://localhost:8080/people/{id}   Delete
 * 
 */

namespace App\Controller;

use App\Entity\User;
use App\Entity\People;
use App\Entity\Phone;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class PeopleController extends AbstractController
{
    /**
     * @Route("/people", name="people-index", methods={"GET"})
     */
    public function index(Request $request): Response
    {
        $eM = $this->getDoctrine()->getManager();
        $data = $request->query;

        if ($data->get('user')) {
            $userID = $data->get('user');
            $list = $this->getDoctrine()->getRepository(People::class)->findByUser($userID);
        } else {
            $list = $eM->getRepository(People::class)->findAll();
        }

        if(!$list){
            $this->createNotFoundException('Kayıt Bulunamadı!');
        }

        $data = array();
        foreach ($list as $l) {
            $item = [];
            $item['id'] = $l->getId();
            $item['name'] = $l->getName();
            $item['surname'] = $l->getSurname();
            $item['company'] = $l->getCompany();
            $phones = $l->getPhones();
            $phone = array();
            foreach ($phones as $p) {
                $i = [];
                $i['id'] = $p->getId();
                $i['number'] = $p->getNumber();
                array_push($phone, $i);
            }
            $item['phone'] = $phone;
            array_push($data, $item);
        }
        return new JsonResponse(['status' => true, 'data' => $data]);
    }


    /**
     * @Route("/people/{id}", name="people-show", methods={"GET"})
     */
    public function show(People $people)
    {
      $data = array();
      $item = [];
      $item['id'] = $people->getId();
      $user = $people->getUser();
      $item['user'] = $user->getEmail();
      $item['user_id'] = $user->getId();
      $item['name'] = $people->getName();
      $item['surname'] = $people->getSurname();
      $item['company'] = $people->getCompany();
      $phones = $people->getPhones();
      $phone = [];
      foreach ($phones as $p) {
        $l = [];
        $l['id'] = $p->getId();
        $l['number'] = $p->getNumber();
        array_push($phone, $l);
      }
      $item['phone'] = $phone;
      array_push($data, $item);
      return new JsonResponse(['status' => true, 'data' => $data]);
    }
  

    /**
     * @Route("/people", name="people-store", methods={"POST"})
     */
    public function store(Request $request): Response
    {
        $eM = $this->getDoctrine()->getManager();
        $data = json_decode($request->getContent(), true);
        
        $userID = $data['user'];
        $user = $this->getDoctrine()->getRepository(User::class)->find($userID);

        $people = new People();
        $people->setUser($user);
        $people->setName($data['name']);
        $people->setSurname($data['surname']);
        $people->setCompany($data['company']);
        if ($data['phone']) {
            foreach ($data['phone'] as $p) {
                $phone = new Phone();
                $phone->setPeople($people);
                $phone->setNumber($p);
                $eM->persist($phone);
            }
        }
    
        $eM->persist($people);
    
        $eM->flush();
        return new JsonResponse(['status' => true, 'message' => $people->getName() . ' kayıt edildi.']);
    }


    /**
     * @Route("/people/{id}", name="people-update", methods={"PUT"})
     */
    public function update($id, Request $request): Response
    {
        $eM = $this->getDoctrine()->getManager();
        $people = $eM->getRepository(People::class)->find($id);
        $data = json_decode($request->getContent(), true);

        $people->setName($data['name'])->setSurname($data['surname'])->setCompany($data['company']);

        $eM->persist($people);

        $eM->flush();

        return new JsonResponse(['status' => true, 'message' => $people->getName() . ' güncellendi.']);
    }

    /**
     * @Route("/people/{id}", name="people-delete", methods={"DELETE"})
     */
    public function delete($id)
    {
        $eM = $this->getDoctrine()->getManager();

        $people = $eM->getRepository(People::class)->find($id);

        if(!$people){
            $this->createNotFoundException('Kayıt Bulunamadı!');
        }

        $eM->remove($people);
        $eM->flush();
        return new JsonResponse(['status' => true, 'message' => $people->getName() . ' silindi.']);
    }
}
