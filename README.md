PHP OpenID Connect Basic Client
========================
This project is a fork from https://github.com/jumbojett/OpenID-Connect-PHP.
The motivation was the need for Symfony's SessionInterface instead of native session handling.

### Fork overrides ###
```src/OpenIDConnectClient.php::fetchUrl``` => add default User-Agent for WAF
```
// Add default user agent
$headers[] = "User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Safari/537.36";
```

# Original project's Readme

A simple library that allows an application to authenticate a user through the basic OpenID Connect flow.
This library hopes to encourage OpenID Connect use by making it simple enough for a developer with little knowledge of
the OpenID Connect protocol to setup authentication.

A special thanks goes to Justin Richer and Amanda Anganes for their help and support of the protocol.

# Requirements #
 1. PHP 7.4 or greater
 2. CURL extension
 3. JSON extension

## Install ##
 1. Install library using composer
```
composer require dbit/openid-connect-php
```
 2. Include composer autoloader
```php
require __DIR__ . '/vendor/autoload.php';
```

## Example 1: Basic Client ##

```php
use OpenIDConnect\OpenIDConnectClient;

$oidc = new OpenIDConnectClient('https://id.provider.com',
                                'ClientIDHere',
                                'ClientSecretHere');
$oidc->setCertPath('/path/to/my.cert');
$oidc->authenticate();
$name = $oidc->requestUserInfo('given_name');

```

[See openid spec for available user attributes][1]

## Example 2: Dynamic Registration ##

```php
use OpenIDConnect\OpenIDConnectClient;

$oidc = new OpenIDConnectClient("https://id.provider.com");

$oidc->register();
$client_id = $oidc->getClientID();
$client_secret = $oidc->getClientSecret();

// Be sure to add logic to store the client id and client secret
```

## Example 3: Network and Security ##
```php
// Configure a proxy
$oidc->setHttpProxy("http://my.proxy.com:80/");

// Configure a cert
$oidc->setCertPath("/path/to/my.cert");
```

## Example 4: Request Client Credentials Token ##

```php
use OpenIDConnect\OpenIDConnectClient;

$oidc = new OpenIDConnectClient('https://id.provider.com',
                                'ClientIDHere',
                                'ClientSecretHere');
$oidc->providerConfigParam(array('token_endpoint'=>'https://id.provider.com/connect/token'));
$oidc->addScope('my_scope');

// this assumes success (to validate check if the access_token property is there and a valid JWT) :
$clientCredentialsToken = $oidc->requestClientCredentialsToken()->access_token;

```

## Example 5: Request Resource Owners Token (with client auth) ##

```php
use OpenIDConnect\OpenIDConnectClient;

$oidc = new OpenIDConnectClient('https://id.provider.com',
                                'ClientIDHere',
                                'ClientSecretHere');
$oidc->providerConfigParam(array('token_endpoint'=>'https://id.provider.com/connect/token'));
$oidc->addScope('my_scope');

//Add username and password
$oidc->addAuthParam(array('username'=>'<Username>'));
$oidc->addAuthParam(array('password'=>'<Password>'));

//Perform the auth and return the token (to validate check if the access_token property is there and a valid JWT) :
$token = $oidc->requestResourceOwnerToken(TRUE)->access_token;

```

## Example 6: Basic client for implicit flow e.g. with Azure AD B2C (see http://openid.net/specs/openid-connect-core-1_0.html#ImplicitFlowAuth) ##

```php
use OpenIDConnect\OpenIDConnectClient;

$oidc = new OpenIDConnectClient('https://id.provider.com',
                                'ClientIDHere',
                                'ClientSecretHere');
$oidc->setResponseTypes(array('id_token'));
$oidc->addScope(array('openid'));
$oidc->setAllowImplicitFlow(true);
$oidc->addAuthParam(array('response_mode' => 'form_post'));
$oidc->setCertPath('/path/to/my.cert');
$oidc->authenticate();
$sub = $oidc->getVerifiedClaims('sub');

```

## Example 7: Introspection of an access token (see https://tools.ietf.org/html/rfc7662) ##

```php
use OpenIDConnect\OpenIDConnectClient;

$oidc = new OpenIDConnectClient('https://id.provider.com',
                                'ClientIDHere',
                                'ClientSecretHere');
$data = $oidc->introspectToken('an.access-token.as.given');
if (!$data->active) {
    // the token is no longer usable
}

```

## Example 8: PKCE Client ##

```php
use OpenIDConnect\OpenIDConnectClient;

$oidc = new OpenIDConnectClient('https://id.provider.com',
                                'ClientIDHere',
                                null);
$oidc->setCodeChallengeMethod('S256');
$oidc->authenticate();
$name = $oidc->requestUserInfo('given_name');

```


## Development Environments ##
In some cases you may need to disable SSL security on on your development systems.
Note: This is not recommended on production systems.

```php
$oidc->setVerifyHost(false);
$oidc->setVerifyPeer(false);
```

Also, your local system might not support HTTPS, so you might disable uprading to it:

```php
$oidc->httpUpgradeInsecureRequests(false);
```
