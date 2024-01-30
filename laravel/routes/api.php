<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/shorten', function (Request $request) {
    $url = $request->input('url');
    $response = Http::withOptions(['verify' => false])->post('https://safebrowsing.googleapis.com/v4/threatMatches:find?key='.env('SAFE_BROWSING_API_KEY'), [
        "client" => [
          "clientId" => "Client",
          "clientVersion" => "1.0"
        ],
        "threatInfo" => [
          "threatTypes" => ["MALWARE", "SOCIAL_ENGINEERING"],
          "platformTypes" => ["WINDOWS"],
          "threatEntryTypes" => ["URL"],
          "threatEntries" => [
            ["url" => $url],
          ]
        ]
      ]);
    if (isset($response->json()['matches'])) {
        return response()->json([
            'unsafe' => true,
        ]);
    } else {
        $hash = substr(md5($url), 0, 6);
        $existingUrl = DB::table('urls')->where('hash', $hash)->first();
        if ($existingUrl) {
            return response()->json([
                'url' => url(env('FOLDER') ? '/'.env('FOLDER').'/' . $existingUrl->hash : '/' . $existingUrl->hash),
            ]);
        }
        DB::table('urls')->insert([
            'url' => $url,
            'hash' => $hash,
        ]);
        return response()->json([
            'url' => url(env('FOLDER') ? '/'.env('FOLDER').'/' . $hash : '/' . $hash),
        ]);
    }

});