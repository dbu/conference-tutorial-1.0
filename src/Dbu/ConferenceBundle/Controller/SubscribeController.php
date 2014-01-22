<?php

namespace Dbu\ConferenceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SubscribeController extends Controller
{
    public function subscribeAction(Request $request)
    {
        $defaultData = array();

        $form = $this->createFormBuilder($defaultData)
            ->add('name', 'text')
            ->add('email', 'email')
            ->add('subscribe', 'submit', array('translation_domain' => 'messages', 'label' => 'subscribe'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();
            $request->getSession()->getFlashBag()->add(
                'notice',
                sprintf('Thanks a lot for your subscription %s <%s>', $data['name'], $data['email'])
            );

            // the cmf router can redirect to a route stored in the db.
            return $this->redirect($this->generateUrl('/cms/simple'));
        }

        return $this->render('DbuConferenceBundle:Subscribe:form.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
