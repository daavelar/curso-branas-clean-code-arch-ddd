<?php

require 'vendor/autoload.php';

$http = new OpenSwoole\Http\Server('0.0.0.0', 9501);

$http->on("start", function($server) {
    echo "Servidor Swoole está rodando em http://127.0.0.1:9501\n";
});

$http->on("request", function($request, $response) use ($http) {
    $path = $request->server['request_uri'];

    if (str($path)->contains('account')) {
        $id = str($path)->match('/\/account\/([0-9a-fA-F\-]+)/')->value();

        $account = getAccount($id);

        $response->end(json_encode($account));
    }
    else if($path == '/') {
        $response->end('Curso Branas');
    }
    else if ($path == '/signup' && $request->server['request_method'] == 'POST') {
        $input = json_decode($request->rawContent(), true);

        $signupOutput = signup($input);

        $response->end(json_encode($signupOutput));
    }
    else {
        $response->status(404);
        $response->end("Rota não encontrada");
    }
});

$http->start();
