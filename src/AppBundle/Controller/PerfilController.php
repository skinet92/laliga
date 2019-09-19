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
use AppBundle\Entity\Perfil;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
/**
 * Perfil controller.
 * @Route("/perfil")
 *
 */
class PerfilController extends Controller
{

    /**
     * @Route("/", name="perfil")
     *
     */
    public function showAction()
    {

        return $this->render('Perfil/index.html.twig');
    }

    /**
     * @Route("perfil/perfil.json", name="perfil.json")
     *
     */
    public function jsonAction(Request $request) #mostrar
    {
        $em = $this->getDoctrine()->getManager();
        $perfils = $em->getRepository('AppBundle:Perfil')->findAll();
        $array = array();
        foreach ($perfils as $perfil) {
            $array [] = array(
                0 => sprintf('<input type="checkbox" name="id[]" value="%s">', $perfil->getIdperfil()),
                1 => sprintf('<a href="%s">%s</a>', $this->generateUrl('perfil_edit', array('id' => $perfil->getIdperfil())), $perfil->getNombre()),
            );
        }
        return new JsonResponse(array(
            'data' => $array
        ));
    }

    /**
     * Creates a new perfil entity.
     *
     * @Route("create", name="perfil_new")
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Perfil();
        $form = $this->createForm('AppBundle\Form\PerfilType', $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('info', 'Datos agregados correctamente');
            return $this->redirectToRoute('perfil');
        }
        return $this->render('Perfil/crear.html.twig', array(
            'form' => $form->createView(),
            'back'=>'perfil'
        ));
    }

    /**
     * Edit a perfil entity.
     *
     * @Route("/{id}/edit", name="perfil_edit")
     *
     */
    public function editAction(Request $request, Perfil $perfil)
    {

        $form = $this->createForm('AppBundle\Form\PerfilType', $perfil);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($perfil);
            $em->flush();
            $this->get('session')->getFlashBag()->add('info', 'Datos agregados correctamente');
            return $this->redirectToRoute('perfil');
        }

        return $this->render('Perfil/editar.html.twig', array(
            'form' => $form->createView(),
            'perfil'=> $perfil,
            'back'=>'perfil'
        ));
    }

    /**
     * Deletes a perfil entity.
     *
     * @Route("/delete", name="perfil_delete")
     *
     */
    public function deleteAction(Request $request)
    {
        $id = $request->get('id');
        $em = $this->getDoctrine()->getManager();
        for ($i = 0; $i < count($id); $i++) {
            $entity = $em->getRepository('AppBundle:Perfil')->find($id[$i]);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find perfil entity.');
            }
            $em->remove($entity);
            $em->flush();
        }
        return new JsonResponse(array('perfil'));
    }
}