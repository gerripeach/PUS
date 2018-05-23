<?php

use Slim\Http\Request;
use Slim\Http\Response;
use PUS\Util\Database\DatabaseHelper;
use PUS\Util\Random\RandomNamer;

// Routes

$app->get('/', function (Request $request, Response $response, array $args) {
    return $this->renderer->render($response, 'index.phtml', array());
});


$app->get('/{name}', function (Request $request, Response $response, array $args) {
    $db = $this->get('database');
    $name = htmlspecialchars($args['name']);
    $result = $db->getRedirect($name);

    if ($result != "") {
        $ip = htmlspecialchars($request->getAttribute('ip_address'));
        $db->saveHit($name, $ip);
        return $response->withRedirect($result);
    }

    return $this->renderer->render($response, 'index.phtml', array());
});

$app->post('/', function (Request $request, Response $response, array $args) {
    $url = htmlspecialchars($request->getParam('url'));
    $short = htmlspecialchars($request->getParam('short'));
    $renderArgs = array();

    if ($url == null) {
        $renderArgs = array(
            'message' => "You have to provide an URL. Please try again."
        );
        return $this->renderer->render($response, 'index.phtml', $renderArgs);
    }

    $db = $this->get('database');

    if ($short == null) {
        $random = new RandomNamer($db->getNumOfRedirects());
        $short = $random->create();
    }

    if ($db->saveRedirect($short, $url)) {
        $renderArgs = array(
            'url' => $request->getUri() . $short
        );
        return $this->renderer->render($response, 'short.phtml', $renderArgs);
    }
    else {
        $renderArgs = array(
            'message' => "Short is already in use. Please try again."
        );
        return $this->renderer->render($response, 'index.phtml', $renderArgs);
    }
});
