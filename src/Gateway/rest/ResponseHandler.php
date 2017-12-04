<?php

namespace Drupal\hybrid_user\Gateway\rest;

use Symfony\Component\HttpFoundation\JsonResponse;

class ResponseHandler {

    /**
     * @var string
     */
    public $message;

    /**
     * @return JsonResponse
     */
    public function onSuccess() {
        return new JsonResponse(json_encode(["message"=>"success"]), 200, array(), true);
    }

    /**
     * @param $e string
     * @return JsonResponse
     */
    public function onError($e) {
        return new JsonResponse(json_encode(["message"=>$e]), 200, array(), true);
    }
}