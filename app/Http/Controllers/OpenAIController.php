<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
 
class OpenAIController extends Controller
{
    /**
     * The API URL
     * 
     * @var string
     */
    const API_URL = 'https://api.openai.com/v1/';

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
        $apiKey = config('services.openai.key');

        if ( empty( $apiKey ) ) {
           throw new \Exception( 'API Key is not set in the .env file. Please contact the administrator.' );
        }

        $this->client = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type' => 'application/json',
            'OpenAI-Beta' => 'assistants=v1'
        ])->baseUrl( self::API_URL );
    }

    /**
     * Create a new thread instance.
     */
    public function start(): Response
    {
        $response = $this->client->post( 'threads' );

        if ( $response->unauthorized() ) {
            throw new \Exception( 'API key is invalid. Please contact the administrator.' );
        }

        return response( $response->json(), $response->status() );
    }

    /**
     * Send a message to the thread.
     */
    public function send( Request $request ): Response
    {
        $thread_id = $request->input( 'thread_id' );
        $message = $request->input( 'message' );
        $step = $request->input( 'step' );

        if ( empty( $thread_id ) || empty( $step ) ) {
            return response( [ 'error' => 'Thread ID or message is empty.' ], 400 );
        }

        $prompts = [
            'color_palette' => 'You are an assistant that helps users create a website template using their prompt. You will get a description of the website. You create a color palette array for a website. Using the description, you will interpret and align the color choices with the website\'s theme and style. It must reference the \'color-example.json\' file, using the \'description\' field of each color as a guide to generate a creative and thematic \'name\' and \'value\' for each color. The \'slug\' should match exactly as given in the JSON file. You should then generate a new color hex value that corresponds to the thematic interpretation based on the website\'s description. The output will be an array of objects with \'name\', \'slug\', and \'color\' (newly generated hex value) for each color, reflecting a palette that complements the website\'s aesthetic as described. Response format must be a JSON-array of the colors and nothing else. I repeat, your response should only be the JSON-array without any other text. From this point on, this is the description of the website:'
        ];

        $params = [
            'assistant_id' => 'asst_etZ5RLbylXLmrbRn8ow8HF9y',
            'instructions' => $prompts[ $step ]
        ];

        if ( ! empty( $message ) ) {
            $params['additional_instructions'] = $message;
        }

        $response = $this->client->post(
            'threads/' . $thread_id . '/runs',
            $params
        );

        return response( $response->json(), $response->status() );
    }

    /**
     * Send a message to the thread.
     */
    public function get( Request $request ): Response
    {
        $thread_id = $request->input( 'thread_id' );

        $response = $this->client->get(
            'threads/' . $thread_id . '/messages'
        );

        return response( $response->json(), $response->status() );
    }

    /**
     * Check message status.
     */
    public function status( Request $request ): Response
    {
        $thread_id = $request->input( 'thread_id' );
        $run_id = $request->input( 'run_id' );

        $response = $this->client->get(
            'threads/' . $thread_id . '/runs/' . $run_id
        );

        return response( $response->json(), $response->status() );
    }
}
