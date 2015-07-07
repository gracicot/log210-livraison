<?php
/**
 * Created by PhpStorm.
 * User: tomasz
 * Date: 15-06-23
 * Time: 10:17 AM
 */

namespace Log210\APIBundle\Message\Response;


class TokenResponse {

    /**
     * @var string $access_token
     */
    private $access_token;

    /**
     * @var int $expires_in
     */
    private $expires_in;

    /**
     * @var string $refresh_token
     */
    private $refresh_token;

    /**
     * @var array $links
     */
    private $links;

    /**
     * @return string
     */
    public function getAccess_token()
    {
        return $this->access_token;
    }

    /**
     * @param string $access_token
     */
    public function setAccess_token($access_token)
    {
        $this->access_token = $access_token;
    }

    /**
     * @return int
     */
    public function getExpires_in()
    {
        return $this->expires_in;
    }

    /**
     * @param int $expires_in
     */
    public function setExpires_in($expires_in)
    {
        $this->expires_in = $expires_in;
    }

    /**
     * @return string
     */
    public function getRefresh_token()
    {
        return $this->refresh_token;
    }

    /**
     * @param string $refresh_token
     */
    public function setRefresh_token($refresh_token)
    {
        $this->refresh_token = $refresh_token;
    }

    /**
     * @return array
     */
    public function getLinks()
    {
        return $this->links;
    }

    /**
     * @param array $links
     */
    public function setLinks($links)
    {
        $this->links = $links;
    }
}
