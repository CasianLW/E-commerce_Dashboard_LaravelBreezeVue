<?php

namespace App\Http\Controllers\Auth;

use App\Services\AuthService;
use Inertia\Inertia;


use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;
use Inertia\Response;


class AuthSessionController extends Controller
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }
    public function LoginIndex(){
        return Inertia::render('Auth/Login');
    }

    public function RegisterIndex(){
        return Inertia::render('Auth/Login');
    }

    public function LoginStore(LoginRequest $request): RedirectResponse
    {
        try {
            // Log::info($request->all());
            $response = $this->authService->login($request->all());
            Log::info($response);
            if (!isset($response['token'])){
                return redirect()->back()->with('flash', ['error' => 'Invalid credentials.']);
            }
            
            $token = $response['token'];
            if (!$token){
                return redirect()->back()->with('error', 'Invalid credentials.');
            }
            $request->session()->put('token', $token);
            Log::info('Redirecting to HOME...');
            $redirect = redirect()->intended(RouteServiceProvider::HOME);
            // $redirect = print_r("test");
            return $redirect;
        } catch (ClientException $e) {
            $response = json_decode($e->getResponse()->getBody()->getContents());
            $errors = $response->error;
            throw ValidationException::withMessages([
                'email' => $errors->email,
                'password' => $errors->password,
            ]);
        }
    }

    /**
     * @throws ValidationException
     */
    public function RegisterStore(Request $request): RedirectResponse
    {
        try {
            $response = $this->authService->register($request->all());
            $token = $response['token'];
            $request->session()->put('token', $token);
            Log::info('Redirecting to HOME...');
            $redirect = redirect()->intended(RouteServiceProvider::HOME);
            Log::info('Redirected to HOME.');
            return $redirect;
        } catch (ClientException $e) {
            $response = json_decode($e->getResponse()->getBody()->getContents());
            $errors = $response->errors;
            throw ValidationException::withMessages([
                'firstname' => $errors->firstname,
                'lastname' => $errors->lastname,
                'location' => $errors->location,
                'phone' => $errors->phone,
                'zip' => $errors->zip,
                'email' => $errors->email,
                'password' => $errors->password,
            ]);
        }
    }
    public function destroy(Request $request): RedirectResponse
    {
        // Log::info('working');
        // destroy the session and remove the token
        $request->session()->forget('token');
        $request->session()->flush();
        return redirect('/');
    }

}