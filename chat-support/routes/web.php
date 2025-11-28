<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/chat', [ChatController::class, 'index']);
Route::post('/chat/send', [ChatController::class, 'send']);


// Route::get('/test-openai', function() {
//     $client = OpenAI::client(config('services.openai.key'));
//     $response = $client->chat()->create([
//         'model' => 'gpt-4o-mini',
//         'messages' => [
//             ['role' => 'user', 'content' => 'Say hello!']
//         ]
//     ]);
//     return $response['choices'][0]['message']['content'];
// });
