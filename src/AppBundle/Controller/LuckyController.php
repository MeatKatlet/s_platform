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


    }
}