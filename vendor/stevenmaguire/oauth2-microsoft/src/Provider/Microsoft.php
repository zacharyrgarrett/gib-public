<?php namespace Stevenmaguire\OAuth2\Client\Provider;

use GuzzleHttp\Psr7\Uri;
use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Token\AccessToken;
use Psr\Http\Message\ResponseInterface;

class Microsoft extends AbstractProvider
{
    /**
     * Default scopes
     *
     * @var array
     */
    public $defaultScopes = ['wl.basic', 'wl.emails'];

    /**
     * Base url for authorization.
     *
     * @var string
     */
    //protected $urlAuthorize = 'https://login.live.com/oauth20_authorize.srf';
    protected $urlAuthorize = 'https://login.microsoftonline.com/common/oauth2/authorize';

    /**
     * Base url for access token.
     *
     * @var string
     */
    //protected $urlAccessToken = 'https://login.live.com/oauth20_token.srf';
    protected $urlAccessToken = 'https://login.microsoftonline.com/common/oauth2/token';

    /**
     * Base url for resource owner.
     *
     * @var string
     */
    protected $urlResourceOwnerDetails = 'https://apis.live.net/v5.0/me';

    /**
     * Get authorization url to begin OAuth flow
     *
     * @return string
     */
    public function getBaseAuthorizationUrl()
    {
        return $this->urlAuthorize;
    }

    /**
     * Get access token url to retrieve token
     *
     * @return string
     */
    public function getBaseAccessTokenUrl(array $params)
    {
        return $this->urlAccessToken;
    }

    /**
     * Get default scopes
     *
     * @return array
     */
    protected function getDefaultScopes()
    {
        return $this->defaultScopes;
    }

    /**
     * Check a provider response for errors.
     *
     * @throws IdentityProviderException
     * @param  ResponseInterface $response
     * @return void
     */
    protected function checkResponse(ResponseInterface $response, $data)
    {
        print_r($data['error']); ////BREAKING HERE
        if (isset($data['error'])) { echo($response->getReasonPhrase());
            throw new IdentityProviderException(
                (isset($data['error']['message']) ? $data['error']['message'] : $response->getReasonPhrase()),
                $response->getStatusCode(),
                $response
            );
        }
    }

    /**
     * Generate a user object from a successful user details request.
     *
     * @param array $response
     * @param AccessToken $token
     * @return MicrosoftResourceOwner
     */
    protected function createResourceOwner(array $response, AccessToken $token)
    {
        return new MicrosoftResourceOwner($response);
    }

    /**
     * Get provider url to fetch user details
     *
     * @param  AccessToken $token
     *
     * @return string
     */
    public function getResourceOwnerDetailsUrl(AccessToken $token)
    {
        $uri = new Uri($this->urlResourceOwnerDetails);

        return (string) Uri::withQueryValue($uri, 'access_token', (string) $token);
    }

    /**
     * Requests an access token using a specified grant and option set.
     *
     * @param  mixed $grant
     * @param  array $options
     * @throws IdentityProviderException
     */
    //public function getAccessToken($grant, array $options = [])
    //{
    //    $grant = $this->verifyGrant($grant);
//
    //    $params = [
    //        'client_id'     => $this->clientId,
    //        'client_secret' => $this->clientSecret,
    //        'redirect_uri'  => $this->redirectUri,
    //    ];
//
    //    $params   = $grant->prepareRequestParameters($params, $options);
    //    $request  = $this->getAccessTokenRequest($params);
    //    $response = $this->getParsedResponse($request);
    //    //if (false === is_array($response)) {
    //    //    throw new UnexpectedValueException(
    //    //        'Invalid response received from Authorization Server. Expected JSON.'
    //    //    );
    //    //}
    //    $prepared = $this->prepareAccessTokenResponse($response);
    //    $token    = $this->createAccessToken($prepared, $grant);
//
    //    return $token;
    //}
}
