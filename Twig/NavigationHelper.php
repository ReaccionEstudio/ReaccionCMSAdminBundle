<?php

namespace App\ReaccionEstudio\ReaccionCMSAdminBundle\Twig;

use Services\Managers\ManagerPermissions;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * NavigationHelper class (Twig_Extension)
 *
 * @author Alberto Vian <alberto@reaccionestudio.com>
 */
class NavigationHelper extends \Twig_Extension
{
    CONST ROUTE_PREFIX = "reaccion_cms_admin_";

    /**
     * RequestStack
     * @var Symfony\Component\HttpFoundation\RequestStack
     */
    private $request;

    /**
     * Constructor
     */
    public function __construct(RequestStack $request)
    {
        $this->request = $request->getCurrentRequest();
    }

	public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('isActive', array($this, 'isActive'))
        );
    }

    /**
     * Get specified navigation link class
     *
     * @param   String  $route     Route name
     * @return  String  [type]     Navigation link class
     */
    public function isActive($route, $isPregMatch=false)
    {
        if( ! $isPregMatch)
        {
            $route = self::ROUTE_PREFIX . $route;
        }

        $currentRoute = $this->request->attributes->get('_route');

        if( ! $isPregMatch && ($route == $currentRoute))
        {
            return 'active';
        }
        if( $isPregMatch && preg_match("/" . $route . "/", $currentRoute))
        {
            return 'active';
        }

        return '';
    }

	public function getName()
    {
        return 'NavigationHelper';
    }
}