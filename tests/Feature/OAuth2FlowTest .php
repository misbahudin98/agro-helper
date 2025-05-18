<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class OAuth2FlowTest extends TestCase
{
    /**
     * Uji bahwa LocalRedirect menyimpan nilai state dan code_verifier ke session
     * dan mengembalikan URL redirect dengan parameter yang sesuai dengan RFC.
     *
     * @return void
     */
    public function testLocalRedirectSetsSessionAndRedirectsWithCorrectParameters()
    {
        // Lakukan request ke endpoint LocalRedirect
        $response = $this->get('/redirect');

        // Pastikan session memiliki kunci 'state' dan 'code_verifier'
        $this->assertTrue(session()->has('state'));
        $this->assertTrue(session()->has('code_verifier'));

        // Pastikan respons adalah redirect
        $response->assertRedirect();

        // Ambil URL redirect dan parsing query string-nya
        $redirectUrl = $response->headers->get('Location');
        $parsedUrl = parse_url($redirectUrl);
        parse_str($parsedUrl['query'] ?? '', $queryParams);

        // Pastikan parameter wajib ada
        $this->assertArrayHasKey('client_id', $queryParams);
        $this->assertArrayHasKey('redirect_uri', $queryParams);
        $this->assertArrayHasKey('response_type', $queryParams);
        $this->assertArrayHasKey('state', $queryParams);
        $this->assertArrayHasKey('code_challenge', $queryParams);
        $this->assertArrayHasKey('code_challenge_method', $queryParams);
        $this->assertArrayHasKey('prompt', $queryParams);

        // Validasi nilai parameter sesuai standar OAuth 2 (RFC)
        $this->assertEquals('1', $queryParams['client_id']);
        $this->assertEquals('http://fe.org/callback.php', $queryParams['redirect_uri']);
        $this->assertEquals('code', $queryParams['response_type']);
        $this->assertEquals('S256', $queryParams['code_challenge_method']);
        $this->assertEquals('consent', $queryParams['prompt']);
    }

    /**
     * Uji bahwa LocalCallback gagal jika nilai state tidak valid.
     *
     * @return void
     */
    public function testLocalCallbackFailsWithInvalidState()
    {
        // Siapkan session dengan state dan code_verifier yang diketahui
        $this->withSession([
            'state'         => 'known_state',
            'code_verifier' => 'dummy_code_verifier'
        ]);

        // Harapkan exception karena state pada request tidak sesuai
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid state value.');

        // Lakukan request dengan state yang tidak sesuai
        $this->post('/callback', [
            'state' => 'invalid_state',
            'code'    => 'dummy_code'
        ]);
    }

    /**
     * Uji bahwa LocalCallback berhasil menukarkan authorization code
     * dengan token apabila state valid dan endpoint token mengembalikan respons sukses.
     *
     * @return void
     */
    public function testLocalCallbackExchangesCodeForTokenSuccessfully()
    {
        // Setup session dengan state dan code_verifier
        $state = 'test_state';
        $codeVerifier = 'test_code_verifier';
        $this->withSession([
            'state'         => $state,
            'code_verifier' => $codeVerifier,
        ]);

        // Persiapkan respons dummy dari endpoint /oauth/token
        $dummyResponse = [
            'access_token'  => rawurlencode('dummy_access_token'),
            'refresh_token' => rawurlencode('dummy_refresh_token'),
        ];

        // Mock Http facade untuk mensimulasikan respons dari /oauth/token
        Http::fake([
            url('/oauth/token') => Http::response($dummyResponse, 200),
        ]);

        // Lakukan request ke endpoint LocalCallback dengan parameter yang valid
        $response = $this->post('/local-callback', [
            'state' => $state,
            'code'  => 'dummy_code'
        ]);

        // Hitung nilai expires_in sesuai konfigurasi env, misalnya PASSPORT_REFRESH berisi menit
        $expectedExpiresIn = env('PASSPORT_REFRESH', 60) * 60; // default 60 menit jika tidak diset

        // Pastikan respons mengarah ke URL callback FE dengan token yang tepat
        $response->assertRedirect(function ($url) use ($dummyResponse, $expectedExpiresIn) {
            $parsedUrl = parse_url($url);
            parse_str($parsedUrl['query'] ?? '', $queryParams);

            return isset($queryParams['access_token'])
                && isset($queryParams['refresh_token'])
                && isset($queryParams['expires_in'])
                && $queryParams['access_token'] === 'dummy_access_token'
                && $queryParams['refresh_token'] === 'dummy_refresh_token'
                && (int)$queryParams['expires_in'] === $expectedExpiresIn;
        });
    }
}
