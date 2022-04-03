<?php

namespace App\Http\Controllers\Auth;

use App\AuthState;
use App\Domain;
use App\Exceptions\EmailTakenException;
use App\Http\Controllers\Controller;
use App\OAuthProvider;
use App\Tenant;
use App\User;
use Illuminate\Auth\Authenticatable;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\JsonResponse;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Contracts\User as SociliteUser;

class OAuthController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        config([
            'services.github.redirect' => route('oauth.callback', 'github'),
            'services.google.redirect' => route('oauth.callback', 'google'),
        ]);
    }

    public function getConnected()
    {
        return auth()
            ->user()
            ->oauthProviders()
            ->select('provider')
            ->get()
            ->pluck('provider');
    }

    /**
     * Redirect the user to the provider authentication page.
     *
     * @param string $provider
     * @return JsonResponse
     */
    public function redirectToProvider($provider)
    {
        $token = request('token');
        $domain = request('domain');

        return response()->json([
            'url' => Socialite::driver($provider)
                ->stateless()
                ->with(['state' => "$domain:$token"])
                ->redirect()
                ->getTargetUrl(),
        ]);
    }

    /**
     * Obtain the user information from the provider.
     *
     * @param $provider
     * @throws EmailTakenException
     */
    public function handleProviderCallback($provider)
    {
        $stateData = explode(':', request('state'));
        $domainName = $stateData[0];
        $token = $stateData[1];

        $sUser = Socialite::driver($provider)->stateless()->user();


        //find tenant by provided domain
        $domain = Domain::where('domain', $domainName)->first();
        if ($domain) {
            //existing
            $tenant = $domain->tenant;
            tenancy()->initialize($tenant);

            if ($token) {
                $authState = AuthState::where('token', $token)->first();
                if ($authState) {
                    $user = User::find($authState->user_id);
                    $this->guard()->setToken(
                        $token = $this->guard()->login($user)
                    );
                }
            }

            $user = $this->findUser($provider, $sUser);
        } else {
            //new tenant
            $emailData = explode('@', $sUser->getName());
            $tenant = Tenant::createTenant([
                'email' => $sUser->getEmail(),
                'company_name' => $emailData[0],
                'domain' => $domainName
            ]);
            tenancy()->initialize($tenant);
            //create seed user
            $user = $this->createUser($provider, $sUser);
        }

        if (auth()->id()) {
            $domain = tenant()->domains()->first()->domain;
            $centralDomain = data_get(config('tenancy.central_domains'),'0');
            return redirect("//$domain.$centralDomain/oauth/$provider/response");
        }

        $this->guard()->setToken(
            $token = $this->guard()->login($user)
        );

        $baseUrl = tenant()->domains()->first()->domain
            . "."
            . data_get(config('tenancy.central_domains'), '0');

        return view('oauth/callback', [
            'baseUrl' => $baseUrl,
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $this->guard()->getPayload()->get('exp') - time(),
        ]);
    }

    public function connectResponse($provider)
    {
        return view('oauth/connect', [
            'connected' => true,
            'provider' => $provider,
        ]);
    }

    /**
     * @param string $provider
     * @param SociliteUser $sUser
     * @return false|\Illuminate\Contracts\Auth\Authenticatable
     */
    protected function findUser($provider, SociliteUser $sUser)
    {
        $oauthProvider = OAuthProvider::where('provider', $provider)
            ->where('provider_user_id', $sUser->getId())
            ->first();

        if ($oauthProvider) {
            $oauthProvider->update([
                'access_token' => $sUser->token,
                'refresh_token' => $sUser->refreshToken,
            ]);

            return $oauthProvider->user;

        } elseif (auth()->id()) {

            //if user already logged in connect it to the oauth provider account
            auth()->user()->oauthProviders()->create([
                'provider' => $provider,
                'provider_user_id' => $sUser->getId(),
                'access_token' => $sUser->token,
                'refresh_token' => $sUser->refreshToken,
            ]);

            return auth()->user();

        }

        return false;
    }

    /**
     * @param string $provider
     * @param \Laravel\Socialite\Contracts\User $sUser
     * @return User
     */
    protected function createUser($provider, $sUser)
    {
        $user = User::create([
            'name' => $sUser->getName(),
            'email' => $sUser->getEmail(),
            'email_verified_at' => now(),
        ]);

        $user->oauthProviders()->create([
            'provider' => $provider,
            'provider_user_id' => $sUser->getId(),
            'access_token' => $sUser->token,
            'refresh_token' => $sUser->refreshToken,
        ]);

        return $user;
    }
}
