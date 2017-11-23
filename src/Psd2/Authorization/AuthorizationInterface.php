<?php

namespace OakLabs\Psd2\Authorization;

interface AuthorizationInterface
{
    /**
     * @return string
     */
    public function getCode(): string;

    /**
     * @return string|null
     */
    public function getState():? string;

    /**
     * @return string
     */
    public function getRedirectUri(): string;

    /**
     * @return string
     */
    public function getClientId(): string;

    /**
     * @return string|null
     */
    public function getClientSecret():? string;
}
