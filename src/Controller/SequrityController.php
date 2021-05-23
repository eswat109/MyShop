<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\Guest;
use App\Entity\Product;
use App\Entity\Order;
use App\Entity\OrderContent;
use App\Entity\Whishlist;
use App\Form\GuestType;
use App\Repository\CartRepository;
use App\Repository\GuestRepository;
//use Doctrine\DBAL\Types\TextType;
//use http\Env\Response;
use App\Repository\ProductRepository;
use App\Repository\WhishlistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class SequrityController extends AbstractController
{
    private $COOKIE_TIME = 60*15;

    private function getGuest(): ?Guest {
        $guest = NULL;
        $guestRep = $this->getDoctrine()->getRepository(Guest::class);
        if (isset($_COOKIE['login']))
            $guest = $guestRep->findOneBy(['login' => $_COOKIE['login']]);
        return $guest;
    }

    private function getPath(): string {
        $guest = $this->getGuest();
        return $guest ? 'profile' : 'login';;
    }

    private function isLoginCorrect(): bool{
        $guest = $this->getGuest();
        return (bool)$guest;
    }

    private function toLoginIfNot(){
        return $this->redirectToRoute('login');
    }

    /**
     * @Route("/add_to_cart/{id}", name="add_to_cart", methods={"GET"})
     */
    public function addToCart(Product $product, CartRepository $cartRep)
    {
        $guest = $this->getGuest();
        if (!$this->isLoginCorrect())
            return $this->toLoginIfNot();

        $entityManager = $this->getDoctrine()->getManager();
        $amount = 1;
        $cart = $cartRep->findOneBy(array('id_guest' => $guest, 'id_product' => $product));
        if ($cart){
            $cart->setAmount($cart->getAmount()+1);
        }
        else{
            $cart = new Cart();
            $cart
                ->setIdGuest($guest)
                ->setAmount(1)
                ->setIdProduct($product)
            ;
            $entityManager->persist($cart);
        }
        $entityManager->flush();

        return $this->redirectToRoute('index', ['message' => 'Product '.$product.' added to cart']);
    }

    /**
     * @Route("/add_to_whishlist/{id}", name="add_to_whishlist", methods={"GET"})
     */
    public function addToWhishlist(Product $product, WhishlistRepository $whishlistRep)
    {
        $guest = $this->getGuest();
        if (!$this->isLoginCorrect())
            return $this->toLoginIfNot();

        $entityManager = $this->getDoctrine()->getManager();
        $action = '';
        $whishlist = $whishlistRep->findOneBy(array('id_guest' => $guest, 'id_product' => $product));
        if ($whishlist){
            $entityManager->remove($whishlist);
            $action = 'removed from';
        }
        else{
            $whishlist = new Whishlist();
            $whishlist
                ->setIdGuest($guest)
                ->setIdProduct($product)
            ;
            $entityManager->persist($whishlist);
            $action = 'added to';
        }
        $entityManager->flush();

        return $this->redirectToRoute('index', ['message' => 'Product '.$product.' '.$action.' whishlist']);
    }

    /**
     * @Route("/my_whishlist/remove/{id}", name="my_whishlist_remove", methods={"GET"})
     */
    public function whishlistRemove(CartRepository $cartRep, Whishlist $whishlist, Request $request)
    {
        $guest = $this->getGuest();
        if (!$this->isLoginCorrect())
            return $this->toLoginIfNot();

        $entityManager = $this->getDoctrine()->getManager();
        $product = $whishlist->getIdProduct();
        $cart = $cartRep->findOneBy(array('id_guest' => $guest, 'id_product' => $product));
        if ($cart){
            $cart->setAmount($cart->getAmount()+1);
        }
        else{
            $cart = new Cart();
            $cart
                ->setIdGuest($guest)
                ->setAmount(1)
                ->setIdProduct($product)
            ;
            $entityManager->persist($cart);
            $guest->addPCart($cart);
        }
        $guest->removePWhishlist($whishlist);
        $entityManager->flush();

        return $this->redirectToRoute('my_whishlist');
    }

    /**
     * @Route("/my_whishlist/delete/{id}", name="my_whishlist_delete", methods={"GET"})
     */
    public function whishlistDelete(Whishlist $whishlist, Request $request)
    {
        $guest = $this->getGuest();
        if (!$this->isLoginCorrect())
            return $this->toLoginIfNot();

        $entityManager = $this->getDoctrine()->getManager();
        $guest->removePWhishlist($whishlist);
        $entityManager->flush();

        return $this->redirectToRoute('my_whishlist');
    }

    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(ProductRepository $productRep, Request $request)
    {
        $guest = $this->getGuest();
        $message = $request->query->get('message');
        $products = $productRep->findAll();

        return $this->render('bootstrap/main.html.twig', [
            '_path' => $this->getPath(),
            'products' => $products,
            'message' => $message
        ]);
    }

    /**
     * @Route("/login", name="login", methods={"GET","POST"})
     */
    public function login(Request $request): Response
    {
        $form = $this->createFormBuilder()
            ->add('login', TextType::class)
            ->add('password', TextType::class)
            //->add('submit', SubmitType::class, array('label' => 'Login!'))
            ->getForm()
        ;
        
        $form->handleRequest($request);
        $guestRep = $this->getDoctrine()->getRepository(Guest::class);
        $login = $form->get('login')->getData();
        $password = $form->get('password')->getData();

        if ($form->isSubmitted()){
            $temp = $guestRep->findOneBy(['login' => $login]);

            if (!$temp){
                $form->addError(new FormError('Such account doesn\'t exist'));
            }
            else if (!password_verify($password, $temp->getPassword())){
                $form->addError(new FormError('Password incorrect'));
            }
            else {
                setcookie('login', $login, time() + $this->COOKIE_TIME);
                return $this->redirectToRoute('profile');
            }
        }

        return $this->render('bootstrap/login.html.twig', [
            'form' => $form->createView()
            ,'_path' => $this->getPath()
            ,'btn_name' => 'Login!'
        ]);
    }


    /**
     * @Route("/register", name="register", methods={"GET","POST"})
     */
    public function register(Request $request): Response
    {
        $guest = new Guest();
        $form = $this->createForm(GuestType::class, $guest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $guestRep = $this->getDoctrine()->getRepository(Guest::class);
            $temp = $guestRep->findOneBy(['login' => $guest->getLogin()]);

            if (!$temp){
                $entityManager->persist($guest);
                $entityManager->flush();

                return $this->redirectToRoute('login');
            }
            else{
                $form->addError(new FormError('Those login is exist'));
            }
        }

        return $this->render('bootstrap/register.html.twig', [
            'guest' => $guest,
            'btn_name' => 'Register!',
            '_path' => $this->getPath(),
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/profile", name="profile", methods={"GET"})
     */
    public function profile(GuestRepository $guestRep)
    {
        $guest = $this->getGuest();
        if (!$this->isLoginCorrect())
            return $this->toLoginIfNot();
        return $this->render('bootstrap/profile.html.twig', [
            'guest' => $guest,
            '_path' => $this->getPath(),
        ]);
    }

    /**
     * @Route("/edit_profile", name="edit_profile", methods={"GET", "POST"})
     */
    public function editProfile(Request $request): Response
    {
        $guest = $this->getGuest();
        if (!$this->isLoginCorrect())
            return $this->toLoginIfNot();

        $entityManager = $this->getDoctrine()->getManager();
        $passwordForm = $this->createFormBuilder()
            ->add('password', TextType::class)
            ->add('repeat_password', TextType::class)
            ->add('submit', SubmitType::class, array('label' => 'Change password!'))
            ->setAction($this->generateUrl('edit_password'))
            ->getForm()
        ;

        $editForm = $this->createFormBuilder($guest)
            ->add('login', TextType::class)
            ->add('firstName', TextType::class)
            ->add('secondName', TextType::class)
            ->add('city', TextType::class)
            ->add('submit', SubmitType::class, array('label' => 'Change data!'))
            ->getForm()
        ;

        if ($request->isMethod('POST')){
            $reqEditForm = $request->get($editForm->getName());
            if ($reqEditForm)
                $editForm->submit($reqEditForm);
        }

        if ($editForm->isSubmitted() ){
            $guest
                ->setLogin($editForm->get('login')->getData())
                ->setFirstName($editForm->get('firstName')->getData())
                ->setSecondName($editForm->get('secondName')->getData())
                ->setCity($editForm->get('city')->getData())
            ;
            $entityManager->flush();
        }

        return $this->render('bootstrap/edit_profile.html.twig', [
            'guest' => $guest
            ,'_path' => $this->getPath()
            ,'editForm' => $editForm->createView()
            ,'passwordForm' => $passwordForm->createView()
        ]);
    }

    /**
     * @Route("/edit_profile/password", name="edit_password", methods={"POST"})
     */
    public function editPassword(Request $request): Response
    {
        $guest = $this->getGuest();
        if (!$this->isLoginCorrect())
            return $this->toLoginIfNot();

        $entityManager = $this->getDoctrine()->getManager();
        $passwordForm = $this->createFormBuilder()
            ->add('password', TextType::class)
            ->add('repeat_password', TextType::class)
            ->add('submit', SubmitType::class, array('label' => 'Change password!'))
            ->getForm()
        ;

        $passwordForm->handleRequest($request);

        if ($passwordForm->isSubmitted()){
            $newPassw = $passwordForm->get('password')->getData();
            $repNewPassw = $passwordForm->get('repeat_password')->getData();

            if ($newPassw === $repNewPassw){
                $guest->setPassword($newPassw);
                $entityManager->flush();
            }
        }

        return $this->redirectToRoute('edit_profile');
    }

    /**
     * @Route("/my_cart", name="my_cart", methods={"GET"})
     */
    public function cart(Request $request)
    {
        $guest = $this->getGuest();
        if (!$this->isLoginCorrect())
            return $this->toLoginIfNot();

        $guestCart = $guest->getPCart();

        return $this->render('bootstrap/cart.html.twig', [
            'guest' => $guest,
            '_path' => $this->getPath(),
            'carts' => $guestCart
        ]);
    }

    /**
     * @Route("/my_whishlist", name="my_whishlist", methods={"GET"})
     */
    public function whishlist(Request $request)
    {
        $guest = $this->getGuest();
        if (!$this->isLoginCorrect())
            return $this->toLoginIfNot();

        $guestWhishlist = $guest->getPWhishlist();

        return $this->render('bootstrap/whishlist.html.twig', [
            'guest' => $guest,
            '_path' => $this->getPath(),
            'whishlists' => $guestWhishlist
        ]);
    }

    /**
     * @Route("/my_cart/add/{id}", name="my_cart_add", methods={"GET"})
     */
    public function cartAdd(Cart $cart, Request $request)
    {
        $guest = $this->getGuest();
        if (!$this->isLoginCorrect())
            return $this->toLoginIfNot();

        $entityManager = $this->getDoctrine()->getManager();

        $cart->setAmount($cart->getAmount()+1);
        $entityManager->flush();

        return $this->redirectToRoute('my_cart');
    }

    /**
     * @Route("/my_cart/remove/{id}", name="my_cart_remove", methods={"GET"})
     */
    public function cartRemove(Cart $cart, Request $request)
    {
        $guest = $this->getGuest();
        if (!$this->isLoginCorrect())
            return $this->toLoginIfNot();

        $entityManager = $this->getDoctrine()->getManager();
        if ($cart->getAmount() > 1) {
            $cart->setAmount($cart->getAmount() - 1);
            $entityManager->flush();
            return $this->redirectToRoute('my_cart');
        }

        return $this->redirectToRoute('my_cart_delete', array('id' => $cart->getId()));
    }

    /**
     * @Route("/my_cart/delete/{id}", name="my_cart_delete", methods={"GET"})
     */
    public function cartDelete(Cart $cart, Request $request)
    {
        $guest = $this->getGuest();
        if (!$this->isLoginCorrect())
            return $this->toLoginIfNot();

        $entityManager = $this->getDoctrine()->getManager();
        $guest->removePCart($cart);
        $entityManager->flush();

        return $this->redirectToRoute('my_cart');
    }

    /**
     * @Route("/my_cart/make_order", name="my_cart_make_order", methods={"GET"})
     */
    public function makeOrder(Request $request)
    {
        $guest = $this->getGuest();
        if (!$this->isLoginCorrect())
            return $this->toLoginIfNot();

        $entityManager = $this->getDoctrine()->getManager();
        $guestCart = $guest->getPCart();
        $order = new Order();
        $order
            ->setIdGuest($guest)
            ->setCity($guest->getCity())
            ->setOrderAt(new \DateTime('now'))
            ->setStatus('processing')
        ;
        $entityManager->persist($order);
        foreach ($guestCart as $cart){
            $orderContent = new OrderContent();
            $orderContent
                ->setAmount($cart->getAmount())
                ->setIdProduct($cart->getIdProduct())
                ->setIdOrder($order)
            ;
            $entityManager->remove($cart);
            $entityManager->persist($orderContent);
        }
        $entityManager->flush();

        return $this->redirectToRoute('my_orders');
    }

    /**
     * @Route("/my_orders", name="my_orders", methods={"GET"})
     */
    public function orders(Request $request)
    {
        $guest = $this->getGuest();
        if (!$this->isLoginCorrect())
            return $this->toLoginIfNot();

        $orders = $guest->getPOrder();

        return $this->render('bootstrap/orders.html.twig',
            array(
                'guest' => $guest,
                'orders' => $orders,
                '_path' => $this->getPath(),
            ));
    }

    /**
     * @Route("/logout", name="logout", methods={"GET"})
     */
    public function logout()
    {
        unset($_COOKIE['login']);
        setcookie('login', null, -1);
        return $this->redirectToRoute('login');
    }

}
