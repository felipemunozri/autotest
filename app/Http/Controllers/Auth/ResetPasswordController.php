<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    protected function rules()
    {
        return [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:6',
        ];
    }

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /*public function reset(Request $request)
    {
        \Log::debug(json_encode('asdas'));
        \Log::debug(json_encode($request->input('email')));
        $request->validate($this->rules(), $this->validationErrorMessages());

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $response = $this->broker()->reset(
            $this->credentials($request), function ($user, $password) {
            $this->resetPassword($user, $password);
        }
        );

        return 'hola';

         return $response == Password::RESET_LINK_SENT
                    ? $this->sendResetLinkWithEmailResponse($request, $response)
                    : $this->sendResetLinkFailedResponse($request, $response); 

    } */

    /* public function sendResetLinkWithEmailResponse(Request $request, $response)
    {

        return $request->wantsJson()
                    ? new JsonResponse(['message' => trans($response), 'email' => $request->input('email')], 200)
                    : back()->with('status', trans($response));
    } */
}
