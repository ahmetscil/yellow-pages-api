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

    public function register(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $eM = $this->getDoctrine()->getManager();
        $data = json_decode($request->getContent(), true);

			$username = $data['username'];
			$password = $data['password'];
			$email = $data['email'];

			if (empty($username) || empty($password) || empty($email)){
				return $this->respondValidationError("Invalid Username or Password or Email");
			}


			$user = new User($email);
			$user->setPassword($encoder->encodePassword($user, $password));
			$user->setEmail($email);
			$user->setUsername($username);
			$eM->persist($user);
			$eM->flush();
            $ud = [];
            $ud['name'] = $username;
            $ud['email'] = $email;
            $ud['id'] = $user->getId();
            return new JsonResponse(['status' => true, 'data' => $ud]);
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

		protected function transformJsonBody(\Symfony\Component\HttpFoundation\Request $request)
		{
				$data = json_decode($request->getContent(), true);
		
				if (json_last_error() !== JSON_ERROR_NONE) {
						return null;
				}
		
				if ($data === null) {
						return $request;
				}
		
				$request->request->replace($data);
		
				return $request;
		}	
	}