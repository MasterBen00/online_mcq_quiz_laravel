<?php


namespace App\Exceptions\Traits;


use App\Exceptions\ApiException;

trait ExceptionTrait
{

    public function apiException($request, $exception)
    {
        if ($exception instanceof ApiException) {
            return response()->json([
                'error_message' => json_decode($exception->getMessage()),
                'error_code' => $exception->getCode()
            ], $exception->getCode());
        }

        return parent::render($request, $exception);
    }

//    public function getDecodedMessage($assoc = false)
//    {
//        return json_decode($this->getMessage(), $assoc);
//    }

}
