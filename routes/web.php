<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

use Laravel\Passport\RefreshTokenRepository;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Token\Parser;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// The Fake Third Party that is Supposedly the site we're on:
Route::get('/redirect', function (Request $request) {
    $request->session()->put('state', $state = Str::random(40));

    $query = http_build_query([
        'client_id' => 1,//3,
        // Phony third party that's supposedly the site we're on:
        'redirect_uri' => 'https://dev.laravel/callback',
        'response_type' => 'code',
        'scope' => '',
        'state' => $state,
        'prompt' => 'consent', // "", "none", "consent", or "login"
    ]);

    // Redirects on Client-Side to the Authorization Server (Laravel Passport),
    // supposedly an external domain running OAuth 2.0. That means that on its
    // own version of /oauth/authorize, it receives everything in $query (above),
    // and thus has its own Redirect(Location: $redirectUri):
    return redirect('https://dev.laravel/oauth/authorize?'.$query);
});

Route::get('/callback', function (Request $request) {
    $state = $request->session()->pull('state');

    throw_unless(
        strlen($state) > 0 && $state === $request->state,
        InvalidArgumentException::class,
        'Invalid state value.'
    );

    $curlResponse = Http::withOptions([
            // 'debug' => true,
            // Otherwise cURL throws error 60 (self-signed certificate):
            'verify' => false,
        ])->asForm()
        ->post('https://dev.laravel/oauth/token', [
            'grant_type' => 'authorization_code',
            'client_id' => 1,//3,
            'client_secret' => 'HRK3J50RUNqsAfsNASEw2Mn5hr0w0GjkLzzkFzho',//'MqQUu7HDT5aZuJyKoze4MUovrL5AiYSLB2Ar0QWG',
            'redirect_uri' => 'https://dev.laravel/callback',
            'code' => $request->code,
        ]);

    $token = $curlResponse->json();

    $parser = new Parser(new JoseEncoder());
    $jwt = $parser->parse($token['access_token']);
    $claims = $jwt->claims();
    $jti = $claims->get('jti');

    $refreshTokenRepository = app(RefreshTokenRepository::class);
    $refreshTokenRepository->revokeRefreshTokensByAccessTokenId($jti);

    unset($token['refresh_token']);

    return $token;
});

require __DIR__.'/auth.php';


