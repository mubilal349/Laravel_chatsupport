<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use OpenAI;
use OpenAI\Exceptions\RateLimitException;

class ChatController extends Controller
{
    public function index()
    {
        return view('chat');
    }

    public function send(Request $request)
    {
        $request->validate([
            'message' => 'required|string'
        ]);

        $client = OpenAI::client(config('services.openai.key'));

        try {
            $response = $client->chat()->create([
                'model' => 'gpt-4o-mini',
                'messages' => [
                    ['role' => 'system', 'content' => 'You are a helpful assistant.'],
                    ['role' => 'user', 'content' => $request->message]
                ]
            ]);

            $reply = $response['choices'][0]['message']['content'] ?? "Sorry, I couldn't understand.";

        } catch (RateLimitException $e) {
            $reply = "Too many requests. Please wait a few seconds.";
        } catch (\Exception $e) {
            $reply = "Something went wrong: " . $e->getMessage();
        }

        return response()->json(['reply' => $reply]);
    }
}
