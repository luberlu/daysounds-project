<?php

namespace ProjectUserBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use FOS\UserBundle\Controller\RegistrationController as BaseController;

use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Form\Factory\FactoryInterface;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use ProjectBundle\Entity\Playlist;

use ProjectBundle\Controller\PlaylistController;

class RegistrationController extends BaseController
{

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function registerAction(Request $request)
    {
        /** @var $formFactory FactoryInterface */
        $formFactory = $this->get('fos_user.registration.form.factory');
        /** @var $userManager UserManagerInterface */
        $userManager = $this->get('fos_user.user_manager');
        /** @var $dispatcher EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');

        $user = $userManager->createUser();
        $user->setEnabled(true);

        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::REGISTRATION_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        $form = $formFactory->createForm();
        $form->setData($user);

        $form->handleRequest($request);

        // Form submit => actions here

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $event = new FormEvent($form, $request);
                $dispatcher->dispatch(FOSUserEvents::REGISTRATION_SUCCESS, $event);

                $userManager->updateUser($user);

                $em = $this->getDoctrine()->getManager();

                $Defaultplaylist = new Playlist();
                $Defaultplaylist->setName("All sounds");
                $Defaultplaylist->setPosition(1);
                $Defaultplaylist->setDateAdd(new \DateTime());
                $Defaultplaylist->setIsDefault(true);
                $Defaultplaylist->setIsDayli(false);
                $Defaultplaylist->setUser($user);

                $em->persist($Defaultplaylist);

                $Dayliplaylist = new Playlist();
                $Dayliplaylist->setName("Dayli sounds");
                $Dayliplaylist->setPosition(2);
                $Dayliplaylist->setDateAdd(new \DateTime());
                $Dayliplaylist->setIsDayli(true);
                $Dayliplaylist->setIsDefault(false);
                $Dayliplaylist->setUser($user);

                $em->persist($Dayliplaylist);

                $em->flush();

                if (null === $response = $event->getResponse()) {
                    // before $url = $this->generateUrl('fos_user_registration_confirmed');

                    $this->get('session')->getFlashBag()->set('success',
                        'Welcome to our platform daysounds '. $user->getUsername());

                    $url = $this->generateUrl("stream");
                    $response = new RedirectResponse($url);
                }

                $dispatcher->dispatch(FOSUserEvents::REGISTRATION_COMPLETED, new FilterUserResponseEvent($user, $request, $response));

                return $response;
            }

            $event = new FormEvent($form, $request);
            $dispatcher->dispatch(FOSUserEvents::REGISTRATION_FAILURE, $event);

            if (null !== $response = $event->getResponse()) {
                return $response;
            }
        }

        return $this->render('@FOSUser/Registration/register.html.twig', array(
            'form' => $form->createView(),
        ));
    }

}