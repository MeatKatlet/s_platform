<?php

// src/AppBundle/Controller/LuckyController.php
namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Enquiry;
use AppBundle\Form\EnquiryType;


class LuckyController extends Controller
{
    /**
     * @Route("/lucky/number2")
     */
    public function numberAction()
    {
        $product = new Product();
        $product->setName('Keyboard');
        $product->setPrice(19.99);
        $product->setDescription('Ergonomic and stylish!');
        $product->setDescription2('Ergonomic and stylish!');
        $em = $this->getDoctrine()->getManager();

        // tells Doctrine you want to (eventually) save the Product (no queries yet)
        $em->persist($product);

        // actually executes the queries (i.e. the INSERT query)
        $em->flush();


        ////////////////////
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
            'SELECT p
            FROM AppBundle:Product p
            WHERE p.price > :price
            ORDER BY p.price ASC'
        )->setParameter('price', 15);

        $products = $query->getResult();
        //var_dump($products);
        ///////////////////



        $number = mt_rand(0, 100);

        /*return new Response(
            '<html><body>Lucky number: '.$number.' and ID'.$product->getId().'</body></html>'
        );*/

        return $this->render('lucky/number.html.twig', array(
            'number' => $number,
        ));
    }

    /**
     * @Route("/lucky/form")
     */

    public function contactAction(Request $request)
    {
        $enquiry = new Enquiry();

        $form = $this->createForm(EnquiryType::class, $enquiry);

        if ($request->isMethod($request::METHOD_POST)) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $message = \Swift_Message::newInstance()
                    ->setSubject('Тема письма')
                    ->setFrom('123@mail.ru')
                    ->setTo($this->container->getParameter('blogger_blog.emails.contact_email'))
                    ->setBody($this->renderView('Page/contactEmail.txt.twig', array('enquiry' => $enquiry)));


                $this->get('mailer')->send($message);

                $this->get('session')->getFlashBag()->add('blogger-notice', 'Форма отправлена, спасибо!');

                //Перенаправляем чтобы при обновлении страницы данные не отправлялись  снова

                return $this->redirect($this->generateUrl('BloggerBlogBundle_contact'));

            }

        }

        return $this->render('Page/contact.html.twig', array(
            'form' => $form->createView()
        ));
    }
}