<?php


namespace App\Helpers;


class SendJsonResponse
{
    /**
     * Sending JSON 404 response
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response|object
     */
    static public function sendNotFound()
    {
        $data = [
            'innerCode' => '404',
            'message' => 'not found',
        ];
        return response()->json($data)->setStatusCode(404);
    }

    /**
     * Sending JSON response with message
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    static public function sendWithMessage($message)
    {
        $response = [
            'message' => $message,
        ];

        return response()->json($response);
    }
}
