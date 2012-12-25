<?php
namespace Neutron\FormBundle\Tests\Security\Handler;

use Neutron\ComponentBundle\Test\Tool\BaseTestCase;

use Neutron\FormBundle\Security\Handler\TinyMceSecurityHandler;

class TinyMceSecurityHandlerTest extends BaseTestCase
{
    public function testAnonymousUser()
    {
        $token = $this->getMock('Symfony\Component\Security\Core\Authentication\Token\TokenInterface');
        $token
            ->expects($this->once())
            ->method('getUser')
            ->will($this->returnValue('anon.'));

        $securityContextMock =
            $this->getMockBuilder('Symfony\Component\Security\Core\SecurityContext')
                ->disableOriginalConstructor()
                ->getMock()
            ;

        $securityContextMock
            ->expects($this->once())
            ->method('getToken')
            ->will($this->returnValue($token))
        ;

        $sessionMock =
            $this->getMockBuilder('Symfony\Component\HttpFoundation\Session\Session')
                ->disableOriginalConstructor()
                ->getMock()
        ;
        
        $sessionMock
            ->expects($this->once())
            ->method('set')
            ->with('authorized', false)
        ;

        $handler = new TinyMceSecurityHandler($securityContextMock, $sessionMock);

        $this->assertNull($handler->authorize(array('ROLE_USER')));
    }

    public function testAthenticatedUser()
    {

        $userMock = $this->getMock('Symfony\Component\Security\Core\User\UserInterface');
        $userMock
            ->expects($this->once())
            ->method('getRoles')
            ->will($this->returnValue(array('ROLE_ADMIN')));

        $token = $this->getMock('Symfony\Component\Security\Core\Authentication\Token\TokenInterface');
        $token
            ->expects($this->once())
            ->method('getUser')
            ->will($this->returnValue($userMock))
        ;

        $securityContextMock =
            $this->getMockBuilder('Symfony\Component\Security\Core\SecurityContext')
                ->disableOriginalConstructor()
                ->getMock()
        ;

        $securityContextMock
            ->expects($this->once())
            ->method('getToken')
            ->will($this->returnValue($token))
        ;

        $sessionMock =
            $this->getMockBuilder('Symfony\Component\HttpFoundation\Session\Session')
                ->disableOriginalConstructor()
                ->getMock();
        
        $sessionMock
            ->expects($this->once())
            ->method('set')
            ->with('authorized', true)
        ;

        $handler = new TinyMceSecurityHandler($securityContextMock, $sessionMock);

        $this->assertNull($handler->authorize(array('ROLE_ADMIN')));
    }

}