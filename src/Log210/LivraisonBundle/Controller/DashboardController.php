<?php

namespace Log210\LivraisonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Log210\LivraisonBundle\Entity\Restaurant;
use Log210\LivraisonBundle\Entity\Restaurateur;

/**
 *
 *
 * @Security("has_role('ROLE_USER')")
 */
class DashboardController extends Controller
{
    /**
     * @Route("/", name="index")
     * @Template("Log210LivraisonBundle:Dashboard:index.html.twig")
     */
    public function indexAction(Request $request)
    {
      //$request = $this->getRequest();

      //$locale=$request->getLocale();

      //$request->setLocale('en_US')
      //$this->get('session')->set('_locale','en');

        return [];
    }

    public function selectLangAction($langue = null)
{
    if($langue != null)
    {
        $this->container->get('request')->setLocale($langue);
    }

    $url = $this->container->get('router')->generate('dashboard', array('_locale' => $langue));

    return new RedirectResponse($url);
}

}
