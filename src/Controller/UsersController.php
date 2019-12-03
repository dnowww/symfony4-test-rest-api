<?php

namespace App\Controller;

use App\Security\TokenStorage;
use App\Entity\EntityMerger;
use App\Entity\User;
use App\Exception\ValidationException;
use FOS\RestBundle\Controller\Annotations\Version;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use FOS\RestBundle\Controller\Annotations as Rest;

/**
 * @Security("is_anonymous() or is_authenticated()")
 * @Version("v1")
 */
class UsersController extends AbstractController
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;
    /**
     * @var JWTEncoderInterface
     */
    private $jwtEncoder;
    /**
     * @var EntityMerger
     */
    private $entityMerger;

    /**
     * @var TokenStorage
     */
    private $tokenStorage;

    /**
     * UserController constructor.
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param JWTEncoderInterface $jwtEncoder
     * @param TokenStorage $tokenStorage
     * @param EntityMerger $entityMerger
     */
    public function __construct(
        UserPasswordEncoderInterface $passwordEncoder,
        JWTEncoderInterface $jwtEncoder,
        TokenStorage $tokenStorage,
        EntityMerger $entityMerger
    )
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->jwtEncoder = $jwtEncoder;
        $this->tokenStorage = $tokenStorage;
        $this->entityMerger = $entityMerger;
    }

    /**
     * @Rest\View()
     * @Rest\Get("/users/{theUser}", name="get_user")
     * @Security("is_granted('show', theUser)", message="Access denied")
     *
     * @param User|null $user
     * @return User|null
     */
    public function getUsers(?User $user)
    {
        if (null === $user) {
            throw new NotFoundHttpException();
        }

        return $user;
    }

    /**
     * @Rest\View(statusCode=201)
     * @Rest\Post("/users", name="post_user")
     * @Security("is_granted('edit', theUser)", message="Access denied")
     * @ParamConverter(
     *     "user",
     *     converter="fos_rest.request_body"
     * )
     *
     * @param User $user
     * @param ConstraintViolationListInterface $validationErrors
     * @return User
     */
    public function postUser(User $user, ConstraintViolationListInterface $validationErrors)
    {
        if (count($validationErrors) > 0) {
            throw new ValidationException($validationErrors);
        }

        $this->encodePassword($user);
        $user->setRoles([User::ROLE_USER]);
        $this->persistUser($user);

        return $user;
    }

    /**
     * @Rest\View()
     * @Rest\Put("/users/{theUser}", name="put_user")
     * @ParamConverter(
     *     "modifiedUser",
     *     converter="fos_rest.request_body"
     * )
     * @Security("is_granted('edit')", message="Access denied")
     *
     * @param User|null $user
     * @param User $newUser
     * @param ConstraintViolationListInterface $validationErrors
     * @return User|null
     * @throws \ReflectionException
     */
    public function putUser(?User $user, User $newUser, ConstraintViolationListInterface $validationErrors)
    {
        if (null === $user) {
            throw new NotFoundHttpException();
        }

        if (count($validationErrors) > 0) {
            throw new ValidationException($validationErrors);
        }

        if (empty($newUser->getPassword())) {
            $newUser->setPassword(null);
        }
        $this->entityMerger->merge($user, $newUser);

        $this->encodePassword($user);
        $this->persistUser($user);

        if ($newUser->getPassword()) {
            $this->tokenStorage->invalidateToken($user->getUsername());
        }

        return $user;
    }

    /**
     * @param User $user
     */
    protected function encodePassword(User $user): void
    {
        $user->setPassword(
            $this->passwordEncoder->encodePassword(
                $user,
                $user->getPassword()
            )
        );
    }

    /**
     * @param User $user
     */
    protected function persistUser(User $user): void
    {
        $manager = $this->getDoctrine()->getManager();
        $manager->persist($user);
        $manager->flush();
    }
}