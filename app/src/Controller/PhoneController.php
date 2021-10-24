<?php
/**
 * TELEFON numaraları yönetimi
 * People tablosundaki her bir kullanıcıya sınırsız telefon numarası tanımlanabilir
 * Tüm CRUD işlemleri gerçekleştirilir.
 * 
 * POST     http://localhost:8080/phone         Yeni Kayıt
 * GET      http://localhost:8080/phone         Tüm Liste
 * GET      http://localhost:8080/phone/{id}    Seçili Kayıt
 * PUT      http://localhost:8080/phone/{id}    Update
 * DELETE   http://localhost:8080/phone/{id}    Delete
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

class PhoneController extends AbstractController
{
    /**
     * @Route("/phone", name="phone-index", methods={"GET"})
     */
    public function index(RequestStack $requestStack): Response
    {
        $em = $this->getDoctrine()->getManager();
        $uR = $em->getRepository(Phone::class);
        $list = $uR->findAll();
        
        $data = array();
        foreach ($list as $l) {
            $item = [];
            $item['id'] = $l->getId();
            $item['number'] = $l->getNumber();
            $people = $l->getPeople();
            $item['people'] = $people->getName();
            array_push($data, $item);
        }
        return new JsonResponse(['status' => true, 'data' => $data]);
    }


    /**
     * @Route("/phone/{id}", name="phone-show", methods={"GET"})
     */
    public function show(Phone $phone)
    {
        $data = array();
        $data['id'] = $phone->getId();
        $data['number'] = $phone->getNumber();
        $people = $phone->getPeople();
        $data['name'] = $people->getName();
        $data['surname'] = $people->getSurname();
        $data['company'] = $people->getCompany();
        return new JsonResponse(['status' => true, 'data' => $data]);
    }
  

    /**
     * @Route("/phone", name="phone-store", methods={"POST"})
     */
    public function store(Request $request): Response
    {
        $eM = $this->getDoctrine()->getManager();
        $data = json_decode($request->getContent(), true);
        
        $people = $this->getDoctrine()->getRepository(People::class)->find($data['people']);

        $phone = new Phone();
        $phone->setPeople($people);
        $phone->setNumber($data['number']);

        $eM->persist($phone);
    
        $eM->flush();
        return new JsonResponse(['status' => true, 'message' => $phone->getNumber() . ' kayıt edildi.']);
    }

    /**
     * @Route("/phone/{id}", name="phone-update", methods={"PUT"})
     */
    public function update($id, Request $request): Response
    {
        $eM = $this->getDoctrine()->getManager();
        $phone = $eM->getRepository(Phone::class)->find($id);
        $data = json_decode($request->getContent(), true);

        $phone->setNumber($data['number']);

        $eM->persist($phone);

        $eM->flush();

        return new JsonResponse(['status' => true, 'message' => $phone->getNumber() . ' güncellendi.']);
    }

    /**
     * @Route("/phone/{id}", name="phone-delete", methods={"DELETE"})
     */
    public function delete($id)
    {
        $eM = $this->getDoctrine()->getManager();

        $phone = $eM->getRepository(Phone::class)->find($id);

        if(!$phone){
            $this->createNotFoundException('Kayıt Bulunamadı!');
        }

        $eM->remove($phone);
        $eM->flush();
        return new JsonResponse(['status' => true, 'message' => $phone->getNumber() . ' silindi.']);
    }

}
