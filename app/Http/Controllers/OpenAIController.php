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
     * Send a message to the thread.
     */
    public function send( Request $request ): Response
    {
        $message = $request->input( 'message' );
        $step = $request->input( 'step' );

        if ( empty( $step ) ) {
            return response(
                [
                    'success' => false,
                    'message' => 'Thread ID or message is empty.'
                ],
                400
            );
        }

        $prompts = [
            'color_palette' => 'You are an assistant that helps users create a website template using their prompt. You will get a description of the website. You create a color palette array for a website. Using the description, you will interpret and align the color choices with the website\'s theme and style. It must reference the \'color-example.json\' file, using the \'description\' field of each color as a guide to generate a creative and thematic \'name\' and \'value\' for each color. The \'slug\' should match exactly as given in the JSON file. You should then generate a new color hex value that corresponds to the thematic interpretation based on the website\'s description. The output will be an array of objects with \'name\', \'slug\', and \'color\' (newly generated hex value) for each color, reflecting a palette that complements the website\'s aesthetic as described. Response format must be a JSON-array of the colors and nothing else. I repeat, your response should only be the JSON-array without any other text. Here is the content of the color-example.json file: [{"slug":"base","name":"Background","color":"#ffffff","description":"The main body color of the website. Usually white or almost white."},{"slug":"base-2","name":"Light Background","color":"#f7f7f3","description":"A subtle variation of the body background, that is used to visually separate sections and areas of the layout."},{"slug":"base-3","name":"Dark Background","color":"#1A1919","description":"Areas of the website that have a dark background. Any text over dark background areas should have the Text on Dark color (contrast-4) to ensure proper contrast."},{"slug":"contrast","name":"Text","color":"#0C0C0C","description":"The main color of the body text. It is applied to text that is over the body background (base) or any of the light (base-2) backgrounds."},{"slug":"contrast-2","name":"Secondary text","color":"rgba(0,0,0, 0.5)","description":"This color is applied on secondary text. The color should have sufficient contrast when placed over the body or light backgrounds (base and base-2)."},{"slug":"contrast-3","name":"Border","color":"rgba(0,0,0, 0.5)","description":"This color is applied on borders. The color should have sufficient contrast when placed over the body or light backgrounds (base and base-2) and is lighter than contrast-3."},{"slug":"contrast-4","name":"Text on dark","color":"#F8F8F8","description":"This color is applied to text that is over a dark background (base-3). For example, if the background color is black, the text color is white, or a color that ensures proper contrast."},{"slug":"accent","name":"Accent","color":"#325CE8","description":"Primary accent color. Can be used for buttons, links, and sometimes as a background. Any text over the accent background should apply the text-on-dark (contrast-4) color."},{"slug":"accent-2","name":"Accent 2","color":"#466DEC","description":"Secondary accent color. Can be used for buttons, links, and sometimes as a background. Any text over the accent background should apply the text-on-dark (contrast-4) color."}] From this point on, this is the description of the website:',
            'images' => 'You will get a description of the website. Based on the description of the website, you will return a comma-separated list of keywords that will be used to search for images on Pexels. The keyword should not exceed 3 words and must be relevant to the website\'s description. You should not return more than three keywords. The response format must be the search word and nothing else. Your response should only be the search keyword in English without any other text. From this point on, this is the description of the website:',
        ];

        $params = [
            'assistant_id' => 'asst_etZ5RLbylXLmrbRn8ow8HF9y'
        ];

        if ( isset( $prompts[ $step ] ) ) {
            $params['instructions'] = $prompts[ $step ];

            if ( 'color_palette' === $step ) {
                $params['model'] = 'gpt-4-1106-preview';
            }
        }

        if ( ! empty( $message ) ) {
            $params['additional_instructions'] = $message;


            $moderate = $this->client->post(
                'moderations',
                [
                    'input' => $message,
                ]                
            );

            if ( ! $moderate->successful() ) {
                return response(
                    [
                        'success' => false,
                        'message' => 'Unable to moderate.'
                    ],
                    $response->status()
                );
            }

            $results = $moderate->json();

            if ( $results['results'][0]['flagged'] ) {
                return response(
                    [
                        'success' => false,
                        'message' => 'Your message was flagged as inappropriate.'
                    ],
                    400
                );
            }
        }

        $response = $this->client->post( 'threads' );

        if ( ! $response->successful() ) {
            return response(
                [
                    'success' => false,
                    'message' => 'Unable to create a new thread.'
                ],
                $response->status()
            );
        }

        $thread_id = $response->json()['id'];

        $response = $this->client->post(
            'threads/' . $thread_id . '/runs',
            $params
        );

        if ( ! $response->successful() ) {
            return response(
                [
                    'success' => false,
                    'message' => 'Unable to create a new thread.'
                ],
                $response->status()
            );
        }

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

        if ( ! $response->successful() ) {
            return response(
                [
                    'success' => false,
                    'message' => 'Unable to create a new thread.'
                ],
                $response->status()
            );
        }

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

        if ( ! $response->successful() ) {
            return response(
                [
                    'success' => false,
                    'message' => 'Unable to create a new thread.'
                ],
                $response->status()
            );
        }

        return response( $response->json(), $response->status() );
    }
}
