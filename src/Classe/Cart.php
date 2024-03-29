<?php

namespace App\Classe;

use Symfony\Component\HttpFoundation\RequestStack;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;

class Cart
{
    private $requestStack;
    private $entityManager;


    public function __construct(EntityManagerInterface $entityManager , RequestStack $requestStack)
    {
        $this->entityManager = $entityManager;
        $this->requestStack = $requestStack;
    }

    public function add($id)
    {
        $session = $this->requestStack->getSession(); 

        $cart= $session->get('cart', []);

        if(!empty($cart[$id])) {
            $cart[$id]++;
        }else{
            $cart[$id] = 1;
        }

        $session->set('cart', $cart);
    }

    public function get()
        {
            $session = $this->requestStack->getSession(); 
            return $session->get('cart');
        }

    public function remove()
        {
            $session = $this->requestStack->getSession(); 
            return $session->remove('cart');
        }    

        public function delete($id)
        {
            $session = $this->requestStack->getSession(); 
            $cart = $session->get('cart', []);

            unset ($cart[$id]);

            return $session->set('cart', $cart);
        }

        public function decrease($id)
        {
            $session = $this->requestStack->getSession();
            $cart = $session->get('cart');

            if ($cart[$id] > 1 ) {
                $cart[$id]--;
            }else {
                unset ($cart[$id]);
            }

            return $session->set('cart', $cart);
        }

        public function getFull()
        {
            $cartComplete=[];
        
            if ($this->get()) {
                foreach ($this->get() as $id => $quantity) 
                {
                    $productobject = $this->entityManager->getRepository(Product::class)->findOneById($id);
                    
                    if (!$productobject) {
                        $this->delete($id);
                        continue;
                    }

                    $cartComplete[] = 
                    [
                        'product' => $productobject,
                        'quantity' => $quantity
                    ];
                }
            }

            return $cartComplete;
            
        }
}

    
    