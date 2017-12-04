<?php

namespace Drupal\hybrid_user\Gateway\rest;

use Drupal\hybrid_user\Components\HybridUser;
use Symfony\Component\HttpFoundation\Request;

class RequestHandler {

    public function handleRequest(Request $request) {
        $res = new HybridUser();

        return $res->create(json_decode($request->getContent()));
    }
}