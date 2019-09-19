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
use AppBundle\Entity\Club;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
/**
 * Club controller.
 * @Route("/club")
 *
 */
class ClubController extends Controller
{

    /**
     * @Route("/", name="club")
     *
     */
    public function showAction()
    {

        return $this->render('Club/index.html.twig');
    }

    /**
     * @Route("club/club.json", name="club.json")
     *
     */
    public function jsonAction(Request $request) #mostrar
    {
        $em = $this->getDoctrine()->getManager();
        $clubs = $em->getRepository('AppBundle:Club')->findAll();
        $array = array();
        foreach ($clubs as $club) {

            $array [] = array(
                0 => sprintf('<input type="checkbox" name="id[]" value="%s">', $club->getIdclub()),
                1 => sprintf('<a href="%s">%s</a>', $this->generateUrl('club_edit', array('id' => $club->getIdclub())), $club->getNombre()),
                2 => sprintf('<a href="%s">%s</a>', $this->generateUrl('club_edit', array('id' => $club->getIdclub())), $club->getEscudo()),
                3 => sprintf('<a href="%s">%s</a>', $this->generateUrl('club_edit', array('id' => $club->getIdclub())), $club->getPresupuesto())
            );
        }
        return new JsonResponse(array(
            'data' => $array
        ));
    }

    /**
     * Creates a new club entity.
     *
     * @Route("create", name="club_new")
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Club();
        $form = $this->createForm('AppBundle\Form\ClubType', $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $file=$form['escudo']->getData();
            $ext=$file->guessExtension();
            $file_name=$entity->getNombre().".".$ext;
            $file->move("uploads", $file_name);
            $entity->setEscudo($file_name);

            $em->persist($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('info', 'Datos agregados correctamente');
            return $this->redirectToRoute('club');
        }
        return $this->render('Club/crear.html.twig', array(
            'form' => $form->createView(),
            'back'=>'club'
        ));
    }

    /**
     * Edit a club entity.
     *
     * @Route("/{id}/edit", name="club_edit")
     *
     */
    public function editAction(Request $request, Club $club)
    {

        $form = $this->createForm('AppBundle\Form\ClubeditType', $club);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($club);
            $em->flush();
            $this->get('session')->getFlashBag()->add('info', 'Datos agregados correctamente');
            return $this->redirectToRoute('club');
        }

        return $this->render('Club/editar.html.twig', array(
            'form' => $form->createView(),
            'club'=> $club,
            'back'=>'club'
        ));
    }

    /**
     * Deletes a club entity.
     *
     * @Route("/delete", name="club_delete")
     *
     */
    public function deleteAction(Request $request)
    {
        $id = $request->get('id');
        $em = $this->getDoctrine()->getManager();
        for ($i = 0; $i < count($id); $i++) {
            $entity = $em->getRepository('AppBundle:Club')->find($id[$i]);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find club entity.');
            }
            $em->remove($entity);
            $em->flush();
        }
        return new JsonResponse(array('club'));
    }
}