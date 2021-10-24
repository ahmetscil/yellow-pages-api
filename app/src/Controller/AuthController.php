<?php
	namespace App\Controller;

	use App\Entity\User;
	use App\Repository\UserRepository;
	use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Symfony\Component\HttpFoundation\JsonResponse;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\HttpFoundation\Response;
	use Symfony\Component\Routing\Annotation\Route;
	use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
	use Symfony\Component\Security\Core\User\UserInterface;
	
	class AuthController extends AbstractController
	{

		public function register(Request $request, UserPasswordEncoderInterface $encoder)
		{
			$em = $this->getDoctrine()->getManager();
			$request = $this->transformJsonBody($request);
			$username = $request->get('username');
			$password = $request->get('password');
			$email = $request->get('email');

			if (empty($username) || empty($password) || empty($email)){
				return $this->respondValidationError("Invalid Username or Password or Email");
			}


			$user = new User($username);
			$user->setPassword($encoder->encodePassword($user, $password));
			$user->setEmail($email);
			$user->setUsername($username);
			$em->persist($user);
			$em->flush();
			return $this->respondWithSuccess(sprintf('User %s successfully created', $user->getUsername()));
		}

		/**
		 * @param UserInterface $user
		 * @param JWTTokenManagerInterface $JWTManager
		 * @return JsonResponse
		 */
		public function getTokenUser(UserInterface $user, JWTTokenManagerInterface $JWTManager)
		{
			return $this->json(['data' => $user]);
			return new JsonResponse(['token' => $JWTManager->create($user)]);
		}

		public function login(Request $request, UserRepository $userRepository, UserPasswordEncoderInterface $encoder)
    {
        $eM = $this->getDoctrine()->getManager();
        $data = json_decode($request->getContent(), true);
  

        $user = $userRepository->findOneBy([
            'email'=>$data['email'],
        ]);

        if (!$user || !$encoder->isPasswordValid($user, $data['password'])) {
            return $this->json([
                'message' => 'email or password is wrong.',
            ]);
        }
        $payload = [
           "user" => $user->getUsername(),
           "exp"  => (new \DateTime())->modify("+5 minutes")->getTimestamp(),
        ];

        $jwt = JWT::encode($payload, $this->getParameter('jwt_secret'), 'HS256');
        return $this->json([
            'message' => 'success!',
            'token' => sprintf('Bearer %s', $jwt),
        ]);
    }

	}