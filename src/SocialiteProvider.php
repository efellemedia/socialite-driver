<?php

namespace Efelle\SocialiteDriver;

use SocialiteProviders\Manager\OAuth2\User;
use Laravel\Socialite\Two\ProviderInterface;
use SocialiteProviders\Manager\OAuth2\AbstractProvider;

class SocialiteProvider extends AbstractProvider implements ProviderInterface
{
    /**
     * Base URL to the efelle accounts domain.
     */
    const BASE_URL = 'https://accounts.efelle.co';
    
    /**
     * Unique Provider Identifier.
     */
    const IDENTIFIER = 'EFELLE';

    /**
     * {@inheritdoc}
     */
    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase(BASE_URL.'/oauth/authorize', $state);
    }

    /**
     * {@inheritdoc}
     */
    protected function getTokenUrl()
    {
        return BASE_URL.'/oauth/token';
    }

    /**
     * {@inheritdoc}
     */
    protected function getUserByToken($token)
    {
        $response = $this->getHttpClient()->get(BASE_URL.'/api/user', [
            'headers' => [
                'Authorization' => 'Bearer '.$token,
            ],
        ]);
        
        return json_decode($response->getBody(), true);
    }

    /**
     * {@inheritdoc}
     */
    protected function mapUserToObject(array $user)
    {
        return (new User())->setRaw($user)->map([
            'id'         => $user['id'],
            'first_name' => $user['first_name'],
            'last_name'  => $user['last_name'],
            'fullname'   => $user['full_name'],
            'email'      => $user['email'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    protected function getTokenFields($code)
    {
        return array_merge(parent::getTokenFields($code), [
            'grant_type' => 'authorization_code'
        ]);
    }
}
