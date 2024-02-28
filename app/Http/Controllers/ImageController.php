<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
 
class ImageController extends Controller
{
    /**
     * The API URL
     * 
     * @var string
     */
    const API_URL = 'https://api.pexels.com/v1/';

    /**
     * API Client
     * 
     * @var PendingRequest
     */
    protected $client;

    /**
     * Constructor
     *
     * @throws \Exception
     */
    public function __construct()
    {
        $apiKey = config('services.pexels.key');

        if ( empty( $apiKey ) ) {
           throw new \Exception( 'API Key is not set in the .env file. Please contact the administrator.' );
        }

        $this->client = Http::withHeaders(['Authorization' => $apiKey])->baseUrl( self::API_URL );
    }

    /**
     * Index images from the API
     */
    public function search( Request $request ): Response
    {
        $query = $request->input('query', 'Nature');

        $response = $this->client->get(
            'search',
            array_merge(
                [
                    'query' => $query,
                    'per_page' => 30,
                    'orientation' => 'landscape'
                ]
            )
        );

        if ( $response->unauthorized() ) {
            return response(
                [
                    'success' => false,
                    'message' => 'API key is invalid. Please contact the administrator.'
                ],
                $response->status()
            );
        }

        if ( $response->tooManyRequests() ) {
            return response(
                [
                    'success' => false,
                    'message' => 'API key has exceeded the rate limit. Please contact the administrator.'
                ],
                $response->status()
            );
        }

        return response( $response->json(), $response->status() );
    }
}
