<?php
/*
 * This file is part of NeutronFormBundle
 *
 * (c) Nikolay Georgiev <azazen09@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace Neutron\FormBundle\Security\Handler;

use Symfony\Component\HttpFoundation\Session\Session;

use Symfony\Component\Security\Core\SecurityContext;

/**
 * This class handles security of tinymce ajax manager
 *
 * @author Nikolay Georgiev <azazen09@gmail.com>
 * @since 1.0
 */
class TinyMceSecurityHandler
{

    /**
     * @var \Symfony\Component\Security\Core\SecurityContext
     */
    protected $securityContext;

    /**
     * @var Symfony\Component\HttpFoundation\Session\Session
     */
    protected $session;

    /**
     * Construct
     *
     * @param SecurityContext $securityContext
     * @param Symfony\Component\HttpFoundation\Session\Session $session
     */
    public function __construct(SecurityContext $securityContext, Session $session)
    {
        $this->securityContext = $securityContext;
        $this->session = $session;
    }

    /**
     * Grants access to ajaxfilemanager
     *
     * @param array $authorizedRoles
     * @return void
     */
    public function authorize(array $authorizedRoles)
    { 
        $authorized = false;
        
        if ($token = $this->securityContext->getToken()){
            $user = $token->getUser();

            if ($user != 'anon.' && count(array_intersect($user->getRoles(), $authorizedRoles)) > 0) {
                $authorized = true;
            }
        }
    	
    	
    	$this->session->set('authorized', $authorized);
    }

}