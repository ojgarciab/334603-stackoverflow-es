<?php
/* Si aún no hemos configurado el proyecto, cargamos la pantalla de configuración */
if (file_exists('../client_secret.json') === false) {
    header('Location: configuracion.html');
    die();
}

require_once __DIR__.'/../vendor/autoload.php';
/* Obtenemos el esquema de la web */
if (empty($_SERVER['HTTPS']) === false
  || (
    empty($_SERVER['HTTP_X_FORWARDED_PROTO']) === false
    && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https'
  )
) {
    $esquema = 'https';
} else {
    $esquema = 'http';
}

$scriptUri = $esquema .'://'. $_SERVER["HTTP_HOST"] . $_SERVER['PHP_SELF']);

$client = new Google_Client();
$client->setApplicationName('B'); //name of the application
$client->setAuthConfig('client_secret.json');
$client->setRedirectUri($scriptUri); //redirects to same url
$client->setApprovalPrompt("force");
$client->setAccessType('offline'); // default: offline
$client->setApprovalPrompt("consent");
$client->addScope(Google_Service_Blogger::BLOGGER);
$client->setIncludeGrantedScopes(true); // incremental auth
$guzzleClient = new \GuzzleHttp\Client(array('curl' => array(CURLOPT_SSL_VERIFYPEER => false)));
$client->setHttpClient($guzzleClient);

if (isset($_GET['code'])) {
    $client->authenticate($_GET['code']);
    //get the access token
    $tokens = $client->getAccessToken();
    file_put_contents(__DIR__."/Data/token.json", $tokens); 
}

$post = new Google_Service_Blogger_Post();
$post->setTitle('Entrada de prueba '. date('H:i:s'));
$post->setImages([
    'url' => 'https://image.freepik.com/vector-gratis/casa-dos-pisos_1308-16176.jpg',
]);
$post->setLabels([
    'Etiqueta 1',
    'Etiqueta 2',
]);
$post->setContent(file_get_contents("plantilla.html"));
$service->posts->insert('7496096651400930024', $post);
