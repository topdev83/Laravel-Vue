<?php

namespace App\Http\Controllers\Auth;

use App\Domain;
use App\Tenant;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    use RegistersUsers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * The user has been registered.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\User $user
     * @return \Illuminate\Http\JsonResponse
     */
    protected function registered(Request $request, User $user)
    {
        if ($user instanceof MustVerifyEmail) {
            return response()->json(['status' => trans('verification.sent')]);
        }

        return response()->json($user);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'company_name' => 'required|max:255|unique:tenants',
            'domain' => 'max:255|unique:domains',
            'email' => 'required|email|max:255|unique:tenants',
            'password' => 'required|min:6',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return \App\User
     * @throws \Stancl\Tenancy\Exceptions\TenantCountNotBeIdentifiedById
     */
    protected function create(array $data)
    {
        if ($data['domain'] == '') {
            $data['domain'] = $data['company_name'];
        }

        $tenant = Tenant::createTenant($data);
        $user = User::create([
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
        $user->base_url = $tenant->domains()->first()->domain
            . "."
            . data_get(config('tenancy.central_domains'), '0');
        return $user;
    }

    public function verifyDomain(Request $request)
    {
        return [
            'available' => ! Domain::where('domain',$request->domain)->count()
        ];
    }
}
