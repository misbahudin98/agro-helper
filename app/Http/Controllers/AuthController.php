<?php

namespace App\Http\Controllers;

use App\Models\PendingRegistration;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Passport\TokenRepository;
use Laravel\Passport\RefreshTokenRepository;

class AuthController extends Controller
{
    public function callbackUrl()
    {
        $encryptedValue = Cookie::get('callback');

        if ($encryptedValue) {
            try {
                // Decrypt cookie if it was encrypted by Laravel
                return explode("|", Cookie::get("callback"));
            } catch (\Exception $e) {
                return redirect('login')->withErrors([
                    'url' => 'Invalid source URL.',
                ]);
            }
        } else {
            return redirect('login')->withErrors([
                'url' => 'Invalid source URL.',
            ]);
        }
    }

    public function LoginPage()
    {
        Auth::guard('web')->logout();
        return view('auth.login');
    }

    public function RedirectToPlatform(Request $request)
    {
        $platform = $request->validate([
            'platform' => 'sometimes|in:' . env("LOGIN_PLATFORM", "google")
        ]);

        if (!empty($platform)) {
            if ($request->platform == "google") {
                return Socialite::driver($platform['platform'])
                    ->with(['prompt' => 'select_account'])
                    ->redirect();
            } elseif ($request->platform == "facebook" || $request->platform == "github") {
                return Socialite::driver($platform['platform'])
                    ->redirect();
            }
        } else {
            return $this->LoginPage();
        }
    }

    public function HandleGoogleCallback(Request $request)
    {
        $email = Socialite::driver('google')->user()->email;
        // Check if email exists in database
        $user = User::where('email', $email)->first();

        if ($user) {
            // If user is found, log in directly
            Auth::login($user);
            return redirect()->intended('/auth/redirect');
        } else {
            return redirect("login")->withErrors(['email' => 'Check your email or contact your administrator.']);
        }
    }

    public function HandleFacebookCallback(Request $request)
    {
        $email = Socialite::driver('facebook')->user()->getEmail();
        // Check if email exists in database
        $user = User::where('email', $email)->first();

        if ($user) {
            // If user is found, log in directly
            Auth::login($user);
            return redirect()->intended('/auth/redirect');
        } else {
            return redirect("login")->withErrors(['email' => 'Check your email or contact your administrator.']);
        }
    }

    public function HandleGithubCallback(Request $request)
    {
        $email = Socialite::driver('Github')->user()->getEmail();
        // Check if email exists in database
        $user = User::where('email', $email)->first();

        if ($user) {
            // If user is found, log in directly
            Auth::login($user);
            return redirect()->intended('/auth/redirect');
        } else {
            return redirect("login")->withErrors(['email' => 'Check your email or contact your administrator.']);
        }
    }

    public function LocalLoginSubmit(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            return redirect()->intended('/auth/redirect');
        }

        return back()->withErrors([
            'email' => 'Please double-check your email and password.',
        ]);
    }

    public function LocalRegisterSubmit(Request $request)
    {
        $credentials = $request->validate([
            'name'     => 'required',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'address'  => 'required',
            'contact'  => "required|regex:/^[+]?\d+$/",
        ]);

        $address = strip_tags($credentials['address']);
        $address = htmlspecialchars($credentials['address'], ENT_QUOTES, 'UTF-8');
        $int = Str::random(10);
        $app = env("APP_NAME");
        $url = env("APP_URL");
        $expiredAt = Carbon::now('UTC')->addMinutes(5);
        $wib = Carbon::now('Asia/Jakarta')->addMinutes(5);

        $fromEmail = env('MAIL_FROM_ADDRESS');
        $html = "<!DOCTYPE html>
<html lang='en'>
<head>
  <meta charset='UTF-8'>
  <title>Verify Your Email</title>
  <style>
    body {
      font-family: 'Arial', sans-serif;
      background-color: #f7f7f7;
      margin: 0;
      padding: 0;
    }
    .container {
      max-width: 600px;
      margin: 50px auto;
      background-color: #fff;
      border-radius: 8px;
      box-shadow: 0 4px 6px rgba(0,0,0,0.1);
      padding: 20px;
    }
    h1 {
      color: #333;
      font-size: 24px;
      text-align: center;
      margin-bottom: 20px;
    }
    p {
      color: #555;
      font-size: 16px;
      line-height: 1.5;
      margin-bottom: 20px;
    }
    .button {
      display: inline-block;
      background-color: #3490dc;
      color: #fff;
      padding: 12px 20px;
      border-radius: 4px;
      text-decoration: none;
      font-size: 16px;
      transition: background-color 0.3s ease;
      text-align: center;
    }
    .button:hover {
      background-color: #2779bd;
    }
    .footer {
      text-align: center;
      color: #999;
      font-size: 14px;
      margin-top: 30px;
    }
  </style>
</head>
<body>
  <div class='container'>
    <h1>Verify Your Email</h1>
    <p>Thank you for registering with <strong>{$app}</strong>. To complete your registration, please verify your email address by clicking the button below:</p>
    <div style='text-align:center;'>
      <a href='{$url}/register/code?code={$int}' class='button'>Verify Email</a>
    </div>
    <p>If the button above does not work, copy and paste the following URL into your browser:</p>
    <p style='word-break: break-all;'><a href='{$url}/register/code?code={$int}'>{$url}/register/code?code={$int}</a></p>
    <p style='color:red'>This link is valid until {$wib} WIB.</p>
    <p style='color:red'>Make sure this link is sent from {$fromEmail}.</p>
    <div class='footer'>
      <p>&copy; 2025 {$app}. All rights reserved.</p>
    </div>
  </div>
</body>
</html>
";

        $emailData = [
            'subject' => 'Registration ' . env("APP_NAME"),
            'message' => $html,
        ];

        try {
            Mail::send([], [], function ($message) use ($emailData, $credentials) {
                $message->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
                    ->to($credentials['email'])
                    ->subject($emailData['subject'])
                    ->html($emailData['message']);
            });

            PendingRegistration::insert([
                'name'               => $credentials["name"],
                'email'              => $credentials["email"],
                'password'           => $credentials["password"],
                'verification_token' => $int,
                'address'            => $address,
                'contact'            => $credentials["contact"],
                'expired_at'         => $expiredAt
            ]);

            return response("Please check your email to confirm your registration.");
        } catch (\Exception $e) {
            return back()->withErrors([
                'email' => 'There was an error during registration, please contact the administrator.',
            ]);
        }
    }

    public function LocalRegisterCode(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|size:10|exists:pending_registrations,verification_token',
        ]);

        // Check if verification token is still valid (expired_at >= current UTC time)
        $pending = PendingRegistration::where('verification_token', $validated['code'])
            ->where('expired_at', '>=', Carbon::now('UTC'))
            ->first();

        // If token is not found or has expired, return error
        if (!$pending) {
            return redirect('login')->withErrors([
                'code' => 'The verification code is invalid or has expired.',
            ]);
        }

        // Check if the pending email is already registered in the users table
        if (User::where('email', $pending->email)->exists()) {
            return redirect('login')->withErrors([
                'code' => 'Email is already registered. Please log in.',
            ]);
        }

        $user = User::create([
            'name'     => $pending->name,
            'email'    => $pending->email,
            'password' => bcrypt($pending->password),
            'address'  => $pending->address,
            'contact'  => $pending->contact,
        ]);

        // If token is valid and email is not registered, show success message
        return view('auth.login', [
            'data' => ['message' => 'Your account has been successfully created, please try logging in.']
        ]);
    }

    public function LocalRedirect(Request $request)
    {
        $state = Str::random(10);
        $codeVerifier = Str::random(128);
        $request->session()->put('state', $state);
        $request->session()->put('code_verifier', $codeVerifier);

        $codeChallenge = strtr(
            rtrim(base64_encode(hash('sha256', $codeVerifier, true)), '='),
            '+/',
            '-_'
        );

        $callbackUrl = $this->callbackUrl();
        $query = http_build_query([
            'client_id'             => $callbackUrl[1],
            'redirect_uri'          => $callbackUrl[0],
            'response_type'         => 'code',
            'scope'                 => Auth::user()->role,
            'state'                 => $state,
            'code_challenge'        => $codeChallenge,
            'code_challenge_method' => 'S256',
            'prompt'                => 'consent',
        ]);

        return redirect(url('/oauth/authorize?' . $query));
    }

    public function LocalCallback(Request $request)
    {
        $storedState = $request->session()->pull("state");
        $codeVerifier = $request->session()->pull("code_verifier");

        throw_unless(
            strlen($storedState) > 0 && $storedState === $request->state,
            \InvalidArgumentException::class,
            'Invalid state value.'
        );
        $callbackUrl = self::callbackUrl();

        $response = Http::asForm()->post(url('/oauth/token'), [
            'grant_type'    => 'authorization_code',
            'client_id'     => $callbackUrl[1],
            'scope'         => Auth::user()->role,
            'redirect_uri'  => $callbackUrl[0],
            'code_verifier' => $codeVerifier,
            'code'          => $request->code,
        ]);

        if (!$response->successful()) {
            return response()->json(['error' => 'Token exchange failed'], $response->status());
        }

        $response = $response->json();

        $accessToken  = rawurldecode($response['access_token']);
        $refreshToken = rawurldecode($response['refresh_token']);
        $expiresIn    = env("PASSPORT_REFRESH") * 60;


        Auth::guard('web')->logout();

        return redirect($callbackUrl[0] . "?access_token={$accessToken}&refresh_token={$refreshToken}&expires_in={$expiresIn}");
    }

    public function localRefreshToken(Request $request)
    {
        $request->validate([
            'refresh_token' => 'required|string'
        ]);
        // return app("index");
        $clientId = app("index");

        // Gunakan $referer atau $origin sesuai kebutuhan, misalnya logging atau validasi
        $response = Http::asForm()->timeout(60)->post(url('/oauth/token'), [
            'grant_type'    => 'refresh_token',
            'client_id'     => $clientId,
            'refresh_token' => $request->refresh_token,
        ]);
        if ($response->successful()) {
            $data = $response->json();
            $data['expires_in'] = env("PASSPORT_REFRESH") * 60;

            if (isset($data['error'])) {
                return response()->json(['message' => $data['error_description']], 401);
            }

            return response()->json($data);
        }

        switch ($response->status()) {
            case 400:
                return response()->json(['message' => 'Invalid request. Please check your username or password.'], 400);
            case 401:
                return response()->json(['message' => 'Invalid access rights.'], 401);
            case 403:
                return response()->json(['message' => 'You do not have permission to access this.'], 403);
            case 500:
                return response()->json(['message' => 'Server error occurred.'], 500);
            default:
                return response()->json(['message' => 'An unknown error occurred.'], 500);
        }
    }

    public function LocalLogout(Request $request)
    {
        $token = $request->user()->token();
        if ($token) {
            $tokenRepository = app(TokenRepository::class);
            $refreshTokenRepository = app(RefreshTokenRepository::class);

            // Revoke an access token...
            $tokenRepository->revokeAccessToken($token->id);

            // Revoke all of the token's refresh tokens...
            $refreshTokenRepository->revokeRefreshTokensByAccessTokenId($token->id);

            return response()->json(['message' => 'Token revoked successfully']);
        }
    }
}
