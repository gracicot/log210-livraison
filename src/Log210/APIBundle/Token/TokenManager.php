<?php

namespace Log210\APIBundle\Token;

use FOS\UserBundle\Doctrine\UserManager;
use Doctrine\Common\Persistence\ObjectManager;
use DateTime;
use DateInterval;
use Log210\APIBundle\Entity\Token;
use Log210\APIBundle\Message\Response\TokenResponse;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use FOS\UserBundle\Model\UserInterface;

class TokenManager
{
	private $um;
	private $om;
	private $ef;

	public function __construct(UserManager $um, ObjectManager $om, EncoderFactory $ef)
	{
		$this->um = $um;
		$this->om = $om;
		$this->ef = $ef;
	}

	public function fromCredential($username, $password)
	{
		$token = null;

        if ($this->authentificateUser($username, $password)) {

            $token = new Token();
            $dateTime = new DateTime();
            $dateTime->add(new DateInterval("PT8H"));
            $token->setId(bin2hex(openssl_random_pseudo_bytes(30)));
            $token->setRefreshToken(bin2hex(openssl_random_pseudo_bytes(30)));
            $token->setExpirationDate($dateTime);
            $token->setUser($this->findUserByUsername($username));

            $this->getEntityManager()->persist($token);
            $this->getEntityManager()->flush();
        }

        return $token;
	}
	
	private function getEncoder(UserInterface $user)
    {
        return $this->ef->getEncoder($user);
    }

    private function authentificateUser($username, $password)
    {
        $user = $this->um->findUserByUsername($username);
        if (is_null($user))
             return false;

        $encoder = $this->getEncoder($user);

        return $encoder->isPasswordValid($user->getPassword(), $password, $user->getSalt()) == 1 ? true : false;
    }

    public function toJson(Token $token)
    {
    	$tokenResponse = new TokenResponse();
        $tokenResponse->setAccess_token($token->getId());
        $tokenResponse->setRefresh_token($token->getRefreshToken());
        $dateNow = strtotime("now");
        $dateExpiry = strtotime($token->getExpirationDate()->format('Y-m-d H:i:s'));
        $tokenResponse->setExpires_in($dateExpiry - $dateNow);
        $tokenResponse->setLinks(array(new Link("self", "/api/tokens/" . $token->getId())));

        return $tokenResponse;
    }

}