<?php
/**
 * Created by PhpStorm.
 * User: Miguel
 * Date: 3/3/2018
 * Time: 09:51
 */

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Entity\Posicionjugador;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
/**
 * Posicionjugador controller.
 * @Route("/posicionjugador")
 *
 */
class PosicionjugadorController extends Controller
{

    /**
     * @Route("/", name="posicionjugador")
     *
     */
    public function showAction()
    {

        return $this->render('Posicionjugador/index.html.twig');
    }

    /**
     * @Route("posicionjugador/posicionjugador.json", name="posicionjugador.json")
     *
     */
    public function jsonAction(Request $request) #mostrar
    {
        $em = $this->getDoctrine()->getManager();
        $posicionjugadors = $em->getRepository('AppBundle:Posicionjugador')->findAll();
        $array = array();
        foreach ($posicionjugadors as $posicionjugador) {
            $array [] = array(
                0 => sprintf('<input type="checkbox" name="id[]" value="%s">', $posicionjugador->getIdposicion()),
                1 => sprintf('<a href="%s">%s</a>', $this->generateUrl('posicionjugador_edit', array('id' => $posicionjugador->getIdposicion())), $posicionjugador->getNombre()),
            );
        }
        return new JsonResponse(array(
            'data' => $array
        ));
    }

    /**
     * Creates a new posicionjugador entity.
     *
     * @Route("create", name="posicionjugador_new")
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Posicionjugador();
        $form = $this->createForm('AppBundle\Form\PosicionjugadorType', $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('info', 'Datos agregados correctamente');
            return $this->redirectToRoute('posicionjugador');
        }
        return $this->render('Posicionjugador/crear.html.twig', array(
            'form' => $form->createView(),
            'back'=>'posicionjugador'
        ));
    }

    /**
     * Edit a posicionjugador entity.
     *
     * @Route("/{id}/edit", name="posicionjugador_edit")
     *
     */
    public function editAction(Request $request, Posicionjugador $posicionjugador)
    {

        $form = $this->createForm('AppBundle\Form\PosicionjugadorType', $posicionjugador);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($posicionjugador);
            $em->flush();
            $this->get('session')->getFlashBag()->add('info', 'Datos agregados correctamente');
            return $this->redirectToRoute('posicionjugador');
        }

        return $this->render('Posicionjugador/editar.html.twig', array(
            'form' => $form->createView(),
            'posicionjugador'=> $posicionjugador,
            'back'=>'posicionjugador'
        ));
    }

    /**
     * Deletes a posicionjugador entity.
     *
     * @Route("/delete", name="posicionjugador_delete")
     *
     */
    public function deleteAction(Request $request)
    {
        $id = $request->get('id');
        $em = $this->getDoctrine()->getManager();
        for ($i = 0; $i < count($id); $i++) {
            $entity = $em->getRepository('AppBundle:Posicionjugador')->find($id[$i]);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find posicionjugador entity.');
            }
            $em->remove($entity);
            $em->flush();
        }
        return new JsonResponse(array('posicionjugador'));
    }
}