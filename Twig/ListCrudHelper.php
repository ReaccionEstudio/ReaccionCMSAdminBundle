<?php

namespace App\ReaccionEstudio\ReaccionCMSAdminBundle\Twig;

use Services\Managers\ManagerPermissions;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination;

/**
 * ListCrudHelper class (Twig_Extension)
 *
 * @author Alberto Vian <alberto@reaccionestudio.com>
 */
class ListCrudHelper extends \Twig_Extension
{
	public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('getCurrentListingCount', array($this, 'getCurrentListingCount')),
        );
    }

    /**
     * Get the current listing count value.
     *
     * @param   SlidingPagination   $result     SlidingPagination object
     * @return  Integer             $total      Current listing count value
     */
    public function getCurrentListingCount(SlidingPagination $result)
    {
        if($result->getCurrentPageNumber() == 1)
        {
            return count($result->getItems());
        }
        else if($result->getCurrentPageNumber() > 1)
        {
            $total = $result->getItemNumberPerPage() * $result->getCurrentPageNumber();
            $total = $total - ($result->getItemNumberPerPage() - count($result->getItems()) );
            return $total;
        }
    }

	public function getName()
    {
        return 'ListCrudHelper';
    }
}