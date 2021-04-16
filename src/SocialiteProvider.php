<?php

namespace Efelle\SocialiteDriver;

use SocialiteProviders\Manager\OAuth2\User;
use Laravel\Socialite\Two\ProviderInterface;
use SocialiteProviders\Manager\OAuth2\AbstractProvider;

class SocialiteProvider extends AbstractProvider implements ProviderInterface
{
    /**
     * Base URL to the efelle accounts domain.
     *
     * @var string
     */
    protected $baseURL = 'http://accounts.test';
    
    /**
     * Unique Provider Identifier.
     */
    const IDENTIFIER = 'EFELLE';

    /**
     * {@inheritdoc}
     */
    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase($this->baseURL.'/oauth/authorize', $state);
    }

    /**
     * {@inheritdoc}
     */
    protected function getTokenUrl()
    {
        return $this->baseURL.'/oauth/token';
    }

    /**
     * {@inheritdoc}
     */
    protected function getUserByToken($token)
    {
        $response = $this->getHttpClient()->get($this->baseURL.'/api/user', [
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
            'name'       => $user['first_name'].' '.$user['last_name'],
            'nickname'   => $user['nickname'],
            'email'      => $user['email'],
            'roles'    => $user['roles'],
            'all_roles'      => $user['all_roles']
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
