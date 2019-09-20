<?php
/**
 * Created by PhpStorm.
 * User: Miguel
 * Date: 3/3/2018
 * Time: 09:51
 */

namespace AppBundle\Controller;

use Monolog\Handler\MailHandlerTest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Entity\Persona;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Validator\Constraints\Date;

/**
 * Persona controller.
 * @Route("/persona")
 *
 */
class PersonaController extends Controller
{

    /**
     * @Route("/", name="persona")
     *
     */
    public function showAction()
    {

        return $this->render('Persona/index.html.twig');
    }

    /**
     * @Route("persona/persona.json", name="persona.json")
     *
     */
    public function jsonAction(Request $request) #mostrar
    {
        $em = $this->getDoctrine()->getManager();
        $personas = $em->getRepository('AppBundle:Persona')->findAll();
        $array = array();
        foreach ($personas as $persona) {
            $fecha = $persona->getFechanacimiento() ? date_format($persona->getFechanacimiento(),'d-m-Y'): '';
            $array [] = array(
                0 => sprintf('<input type="checkbox" name="id[]" value="%s">', $persona->getIdpersona()),
                1 => sprintf('<a href="%s">%s</a>', $this->generateUrl('persona_edit', array('id' => $persona->getIdpersona())), $persona->getNombre()),
                2 => sprintf('<a href="%s">%s</a>', $this->generateUrl('persona_edit', array('id' => $persona->getIdpersona())), $persona->getDni()),
                3 => sprintf('<a href="%s">%s</a>', $this->generateUrl('persona_edit', array('id' => $persona->getIdpersona())), $fecha),
                4 => sprintf('<a href="%s">%s</a>', $this->generateUrl('persona_edit', array('id' => $persona->getIdpersona())), $persona->getEmail()),
                5 => sprintf('<a href="%s">%s</a>', $this->generateUrl('persona_edit', array('id' => $persona->getIdpersona())), $persona->getSalario()),
                6 => sprintf('<a href="%s">%s</a>', $this->generateUrl('persona_edit', array('id' => $persona->getIdpersona())), $persona->getPerfil()),
                7 => sprintf('<a href="%s">%s</a>', $this->generateUrl('persona_edit', array('id' => $persona->getIdpersona())), $persona->getClub()),
                8 => sprintf('<a href="%s">%s</a>', $this->generateUrl('persona_edit', array('id' => $persona->getIdpersona())), $persona->getPosicion()),
            );
        }
        return new JsonResponse(array(
            'data' => $array
        ));
    }

    /**
     * Creates a new persona entity.
     *
     * @Route("create", name="persona_new")
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Persona();
        $form = $this->createForm('AppBundle\Form\PersonaType', $entity);
        $form->handleRequest($request);
        $contjuagores=0;
        $contentrenadores=0;
        $contsalario=0;
        $fechaactual = new  \DateTime();
        $tiempo=array();
        if ($form->isSubmitted() && $form->isValid()) {

            $transport = \Swift_SmtpTransport::newInstance('smtp.mandrillapp.com', 587);
            $swift = \Swift_Mailer::newInstance($transport);
            $body = 'Registro exitoso....Bienvenido';

            $em = $this->getDoctrine()->getManager();
            $club=$form->get('club')->getData();
            $perfil=$form->get('perfil')->getData();

            $personas = $em->getRepository('AppBundle:Persona')->findAll();
           /* $clubs = $em->getRepository('AppBundle:Club')->findBy(array('nombre'=>$club->getNombre()));*/

            foreach ($personas as $persona){
                if($persona->getClub()==$club && $persona->getPerfil()->getNombre()=="Jugador") {
                    $contsalario += $persona->getSalario();
                }
                if ($persona->getPerfil()->getNombre()=="Jugador" && $persona->getClub()==$club   ){
                        $contjuagores++;
                }
                if ($persona->getPerfil()->getNombre()=="Entrenador" && $persona->getClub()==$club )
                        $contentrenadores++;
                }
            $contsalario=$contsalario+ $entity->getSalario();

                     $fechaformulario=$entity->getFechanacimiento();
                     $intervalo= date_diff($fechaformulario,$fechaactual);

            foreach ($intervalo as $valor){
               $tiempo[]=$valor;
                }

               if ($entity->getPerfil()->getNombre()=="Entrenador" && $contentrenadores==0){
                   $em->persist($entity);
                   $em->flush();
                   $this->get('session')->getFlashBag()->add('info', 'Datos agregados correctamente');
                   return $this->redirectToRoute('persona');
               }elseif ($entity->getPerfil()->getNombre()=="Jugador" && $contjuagores<=4 && $contsalario<=$entity->getClub()->getPresupuesto() ){
                   $em->persist($entity);
                   $message = (new \Swift_Message('Email Through Swift Mailer'))
                       ->setFrom(['suport@gmail.com'])
                       ->setTo([$entity->getEmail()])
                       ->setBody($body)
                       ->setContentType('text/html')
                   ;
                   $swift->send($message);
                   $em->flush();
                   $this->get('session')->getFlashBag()->add('info', 'Datos agregados correctamente');
                   return $this->redirectToRoute('persona');
               } elseif ($entity->getPerfil()->getNombre()=="Canterano" && $tiempo[0]<=23 ){
                   $em->persist($entity);
                   $em->flush();

                   $this->get('session')->getFlashBag()->add('info', 'Datos agregados correctamente');
                   return $this->redirectToRoute('persona');
               } else
                   $this->get('session')->getFlashBag()->add('info', 'Se esta violando algunas de las reglas del sistema');



        }
        return $this->render('Persona/crear.html.twig', array(
            'form' => $form->createView(),
            'back'=>'persona'
        ));
    }

    /**
     * Edit a persona entity.
     *
     * @Route("/{id}/edit", name="persona_edit")
     *
     */
    public function editAction(Request $request, Persona $entity)
    {

        $form = $this->createForm('AppBundle\Form\PersonaType', $entity);
        $form->handleRequest($request);
        $contjuagores=0;
        $contentrenadores=0;
        $contsalario=0;
        $fechaactual = new  \DateTime();
        $tiempo=array();
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $club=$form->get('club')->getData();
            $perfil=$form->get('perfil')->getData();

            $personas = $em->getRepository('AppBundle:Persona')->findAll();
            /* $clubs = $em->getRepository('AppBundle:Club')->findBy(array('nombre'=>$club->getNombre()));*/

            foreach ($personas as $persona){
                if($persona->getClub()==$club && $persona->getPerfil()->getNombre()=="Jugador") {
                    $contsalario += $persona->getSalario();
                }
                if ($persona->getPerfil()->getNombre()=="Jugador" && $persona->getClub()==$club   ){
                    $contjuagores++;
                }
                if ($persona->getPerfil()->getNombre()=="Entrenador" && $persona->getClub()==$club )
                    $contentrenadores++;
            }
            $contsalario=$contsalario+ $entity->getSalario();

            $fechaformulario=$entity->getFechanacimiento();
            $intervalo= date_diff($fechaformulario,$fechaactual);

            foreach ($intervalo as $valor){
                $tiempo[]=$valor;
            }

            if ($entity->getPerfil()->getNombre()=="Entrenador" && $contentrenadores==1){
                $em->persist($entity);
                $em->flush();

                $this->get('session')->getFlashBag()->add('info', 'Datos agregados correctamente');
                return $this->redirectToRoute('persona');
            }elseif ($entity->getPerfil()->getNombre()=="Jugador" && $contjuagores<=4 && $contsalario<=$entity->getClub()->getPresupuesto() ){
                $em->persist($entity);
                $em->flush();

                $this->get('session')->getFlashBag()->add('info', 'Datos agregados correctamente');
                return $this->redirectToRoute('persona');
            } elseif ($entity->getPerfil()->getNombre()=="Canterano" && $tiempo[0]<=23 ){
                $em->persist($entity);
                $em->flush();

                $this->get('session')->getFlashBag()->add('info', 'Datos agregados correctamente');
                return $this->redirectToRoute('persona');
            } else
                $this->get('session')->getFlashBag()->add('info', 'Se esta violando algunas de las reglas del sistema');



        }

        return $this->render('Persona/editar.html.twig', array(
            'form' => $form->createView(),
            'persona'=> $entity,
            'back'=>'persona'
        ));
    }

    /**
     * Deletes a persona entity.
     *
     * @Route("/delete", name="persona_delete")
     *
     */
    public function deleteAction(Request $request)
    {
        $id = $request->get('id');
        $em = $this->getDoctrine()->getManager();
        for ($i = 0; $i < count($id); $i++) {
            $entity = $em->getRepository('AppBundle:Persona')->find($id[$i]);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find persona entity.');
            }
            $em->remove($entity);
            $em->flush();
        }
        return new JsonResponse(array('persona'));
    }

/// Enviar el correo
    public function mymail($email, \Swift_Mailer $mailer)
    {
        $message = (new \Swift_Message('Bienvenido'))
            ->setFrom('send@example.com')
            ->setTo($email)
            ->setBody(
                $this->renderView(
                // templates/emails/registration.html.twig
                    'emails/registration.html.twig',
                    ['name' => $email]
                ),
                'text/html'
            );

    }

}