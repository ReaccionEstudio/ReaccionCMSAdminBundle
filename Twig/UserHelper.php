<?php

namespace App\ReaccionEstudio\ReaccionCMSAdminBundle\Twig;

use Services\Managers\ManagerPermissions;
use App\ReaccionEstudio\ReaccionCMSAdminBundle\Constants\Languages;

/**
 * UserHelper class (Twig_Extension)
 *
 * @author Alberto Vian <alberto@reaccionestudio.com>
 */
class UserHelper extends \Twig_Extension
{
	public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('getUserNickname', array($this, 'getUserNickname')),
            new \Twig_SimpleFunction('getUserMainRoleTranslationKey', array($this, 'getUserMainRoleTranslationKey'))
        );
    }

    /**
     * Get user nickname if exists
     *
     * @param   User    $user     User Twig object
     * @return  String  [type]    User nickname | Username
     */
    public function getUserNickname($user)
    {
        if($user == null) return '';

        if($user->getNickname()) 
        {
            return $user->getNickname();
        }

        return $user->getUsername();
    }

    /**
     * Get user main role translation key
     *
     * @param   User    $user     User Twig object
     * @return  String  [type]    User main role
     */
    public function getUserMainRoleTranslationKey($user)
    {
        if($user == null) return '';
        
        $roles = $user->getRoles();

        if(empty($roles[0])) return '';
        return "roles." . strtolower($roles[0]);
    }

	public function getName()
    {
        return 'UserHelper';
    }
}