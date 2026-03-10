<?php

namespace App\Exceptions;

use Throwable;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ApiExceptionHandler
{
    use ApiResponses;
    public static function ModelNotFoundHandler(NotFoundHttpException $exception, Request $request): JsonResponse {
        $previousException = $exception->getPrevious();
            if ($previousException instanceof ModelNotFoundException) {
                /** @var \Illuminate\Database\Eloquent\ModelNotFoundException $exception */
                return self::error([
                [
                    'status' => 404,
                    'message' => 'The resource cannot be found.',
                    'source' => class_basename($previousException->getModel())
                ]
            ], 404);
        }

        // If we are here, it means the URL itself is wrong (Route not found)
        return self::error([
            [
                'status' => 404,
                'message' => 'API Endpoint not found.',
                'source' => $request->path()
            ]
        ], 404);
    }

    public static function ValidationExceptionHandler(ValidationException $exception, Request $request): JsonResponse {
        foreach ($exception->errors() as $key => $value)
            foreach ($value as $message) {
                $errors[] = [
                    'status' => 422,
                    'message' => $message,
                    'source' => $key
                ];
            }
        return self::error($errors, 422);
    }

    public static function AuthenticationExceptionHandler(AuthenticationException $exception, Request $request): JsonResponse {
        return self::error([
            [
                'status' => 401,
                'message' => 'Unauthenticated.',
                'source' => ''
            ]
        ], 401);
    }

    public static function GeneralExceptionHandler(Throwable $exception, Request $request): JsonResponse
    {
        $code = method_exists($exception, 'getStatusCode') ? $exception->getStatusCode() : 500;

        $isDebug = config('app.debug');

        if ($isDebug) {
            // Development: Show everything
            $type    = class_basename($exception);
            $message = $exception->getMessage();
            $source  = 'Line: ' . $exception->getLine() . ' in ' . $exception->getFile();
        } else {
            // Production: Hide implementation details
            // If it's a 500 (Server Error), hide the message. 
            // If it's a specific HTTP error (like 403 Forbidden), the message is usually safe.
            $type    = ($code === 500) ? 'Server_Error' : 'Client_Error';
            $message = ($code === 500) ? 'Internal Server Error' : $exception->getMessage();
            $source  = 'system';
        }

        return self::error([
            [
                'type'    => $type,
                'status'  => $code,
                'message' => $message,
                'source'  => $source
            ]
        ], $code);
    }
}   
