<?php

namespace OakLabs\Psd2\Authorization;

class Authorization implements AuthorizationInterface
{
    /**
     * @var string
     */
    protected $code;

    /**
     * @var string
     */
    protected $state;

    /**
     * @var string
     */
    private $redirectUri;

    /**
     * @var string
     */
    private $clientId;

    /**
     * @var string
     */
    private $clientSecret;

    /**
     * [
     *  ['code' => (string)],
     *  ['state' => (string),]
     *  ['redirect_uri' => (string),]
     *  'client_id' => (string),
     *  'client_secret' => (string)
     * ]
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->code = $data['code'] ?? null;

        $this->state = $data['state'] ?? null;

        $this->redirectUri = $data['redirect_uri'] ?? null;

        $this->clientId = $data['client_id'];

        $this->clientSecret = $data['client_secret'];
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @return string|null
     */
    public function getState():? string
    {
        return $this->state;
    }

    /**
     * @return string
     */
    public function getRedirectUri(): string
    {
        return $this->redirectUri;
    }

    /**
     * @return string
     */
    public function getClientId(): string
    {
        return $this->clientId;
    }

    /**
     * @return string
     */
    public function getClientSecret(): string
    {
        return $this->clientSecret;
    }
}
