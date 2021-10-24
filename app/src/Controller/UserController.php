<?php
/**
 * ÜYE yönetimi
 * tüm CRUD işlemleri gerçekleştirilir.
 * 
 * POST http://localhost:8080/user         Yeni Kayıt
 * GET http://localhost:8080/user          Tüm Liste
 * GET http://localhost:8080/user/{id}     Seçili Kayıt
 * PUT http://localhost:8080/user/{id}     Update
 * DELETE http://localhost:8080/user/{id}  Delete
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

class UserController extends AbstractController
{
    /**
     * @Route("/user", name="user-index", methods={"GET"})
     */
    public function index(RequestStack $requestStack): Response
    {
        $em = $this->getDoctrine()->getManager();
        $uR = $em->getRepository(User::class);
        $list = $uR->findAll();
        
        $data = array();
        foreach ($list as $l) {
            $item = [];
            $item['id'] = $l->getId();
            $item['user'] = $l->getEmail();

            $people = $l->getPeople();
            $ppl = [];
            foreach ($people as $p) {
                $l = [];
                $l['id'] = $p->getId();
                $l['name'] = $p->getName();
                $l['surname'] = $p->getSurname();
                $l['company'] = $p->getCompany();

                $phones = $p->getPhones();
                $phone = [];
                foreach ($phones as $p) {
                    $i = [];
                    $i['id'] = $p->getId();
                    $i['number'] = $p->getNumber();
                    array_push($phone, $i);
                }
                $l['phones'] = $phone;
                array_push($ppl, $l);
            }
            $item['people'] = $ppl;
            array_push($data, $item);
        }
        return new JsonResponse(['status' => true, 'data' => $data]);
    }


    /**
     * @Route("/user/{id}", name="user-show", methods={"GET"})
     */
    public function show(User $user)
    {
        $data = array();
        $item = [];
        $item['id'] = $user->getId();
        $item['user'] = $user->getEmail();
        $phones = $user->getPhones();
        $phone = [];
        foreach ($phones as $p) {
            $l = [];
            $l['name'] = $p->getName();
            $l['surname'] = $p->getSurname();
            $l['company'] = $p->getCompany();
            $l['number'] = $p->getNumber();
            array_push($phone, $l);
        }
        $item['phones'] = $phone;
        array_push($data, $item);
        return new JsonResponse(['status' => true, 'data' => $data]);
    }
  

    /**
     * @Route("/user", name="user-store", methods={"POST"})
     */
    public function store(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $eM = $this->getDoctrine()->getManager();
        $data = json_decode($request->getContent(), true);
        $user = new User();
        $user->setEmail($data['email']);
        $user->setUsername($data['username']);
        $user->setRoles(['ROLE_USER']);

        $plainPassword = $data['password'];
        $encoded = $encoder->encodePassword($user, $plainPassword);
        $user->setPassword($encoded);

        $eM->persist($user);

        $eM->flush();
        return new JsonResponse(['status' => true, 'message' => $user->getEmail() . ' kayıt edildi.']);
    }
}
