<?php
/**
 * The oAuth2-Server functionality of the theme.
 *
 * @link       https://wwdh.de
 * @since      2.0.0
 *
 * @package    Hupa_Starterter_v2
 * @subpackage Hupa_Starterter_v2/includes/oAuthServer
 */

use Hupa\Starter\Config;
use OAuth2\GrantType\ClientCredentials;
use OAuth2\GrantType\JwtBearer;
use OAuth2\GrantType\UserCredentials;
use OAuth2\GrantType\AuthorizationCode;
use OAuth2\GrantType\RefreshToken;
use OAuth2\Response;
use OAuth2\Scope;
use OAuth2\Server;
use OAuth2\Storage\Memory;
use OAuth2\Storage\Pdo;

class OauthServer
{
    var $server;

    public function __construct()
    {
        $this->init();
    }

    public function init()
    {

        $dsn = sprintf("mysql:host=%s;dbname=%s;charset=%s", DB_HOST, DB_NAME, DB_CHARSET);
        $storage = new Pdo(array('dsn' => $dsn, 'username' => DB_USER, 'password' => DB_PASSWORD));

        $this->server = new Server($storage, array(
            'always_issue_new_refresh_token' => Config::get('ALWAYS_ISSUE_NEW_REFRESH_TOKEN'),
            'auth_code_lifetime' => Config::get('AUTH_CODE_LIFETIME'),
            'access_lifetime' => Config::get('ACCESS_LIFETIME'),
            'refresh_token_lifetime' => Config::get('REFRESH_TOKEN_LIFETIME'),
            'enforce_state' => Config::get('ENFORCE_STATE'),
            'allow_implicit' => Config::get('ALLOW_IMPLICIT')
        ));

        $this->server->addGrantType(new UserCredentials($storage));
        $this->server->addGrantType(new ClientCredentials($storage));
        $this->server->addGrantType(new AuthorizationCode($storage));

        $grantType = new RefreshToken($storage, array(
            'unset_refresh_token_after_use' => Config::get('UNSET_REFRESH_TOKEN_AFTER_USE')
        ));
        $this->server->addGrantType($grantType);
    }
}


class JWToAuthServer
{

    var $server;

    public function __construct()
    {
        $this->init();
    }

    public function init()
    {
        $dsn = sprintf("mysql:host=%s;dbname=%s;charset=%s", DB_HOST, DB_NAME, DB_CHARSET);
        $storage = new Pdo(array('dsn' => $dsn, 'username' => DB_USER, 'password' => DB_PASSWORD));
        $this->server = new Server($storage, array(
            'access_lifetime' => Config::get('ACCESS_JWT_LIFETIME'),
            'use_jwt_access_tokens' => Config::get('USE_JWT_ACCESS_TOKEN'),
            'allow_implicit' => Config::get('ALLOW_JWT_IMPLICIT')
        ));

        $audience = site_url() . '/oauth/v2/';
        $this->server->addGrantType(new JwtBearer($storage, $audience));
    }
}

class OauthMemoryServer
{

    var $server;

    public function __construct()
    {
        $this->init();
    }

    public function init()
    {

        $response = new Response();
        /*$srv = app_settings_by_name('oauth_server');

        $args = sprintf('WHERE ops.client_id="%s"', $srv->public_client_id);
        $jwtRsd = get_oauth_public_keys($args, false);

        if (!$jwtRsd->status) {
            $err = apply_filters('oauth_set_error_message','invalid_key');
            $response->setError($err[0], $err[1], $err[2]);
            $response->send();
            exit();
        }*/

        $supportedScopes = array(
            'basic',
            'media_data',
            'post_data',
            'account_data',
            'hupa_data',
            'public'
        );

        $memory = new Memory(array(
            'default_scope' => 'basic',
            'supported_scopes' => $supportedScopes
        ));

        $storage = new Memory(array(
            'keys' => array(
               // 'public_key' => $jwtRsd->record->public_key,
              //  'private_key' => $jwtRsd->record->private_key,
            ),
            'default_scope' => 'publicSupport',
            'supported_scopes' => $supportedScopes,

            'client_credentials' => array(
               'hupa_theme' => array('client_secret' => Config::get('OAUTH_PUBLIC_CLIENT_SECRET') )
            ),
        ));
        $this->server = new Server($storage, array(
            'use_jwt_access_tokens' => Config::get('USE_JWT_MEMORY_ACCESS_TOKEN'),
        ));

        $scopeUtil = new Scope($memory);
        $this->server->setScopeUtil($scopeUtil);
        $this->server->addGrantType(new ClientCredentials($storage));

    }
}