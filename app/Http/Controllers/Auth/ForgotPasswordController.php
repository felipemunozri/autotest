<?php

namespace App\Http\Controllers\Auth;

use App\Domain\Helpers\MailHelper;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    public function send(Request $request)
    {
        $params = $this->validate($request, [
            'email' => 'required|email',
            'url' => 'required',
        ]);

        $user = User::query()->where($request->only('email'))->first();

        if($user) {
            $token = $this->getTokenUser($user);
            $params['token'] = $token;
            $params['username'] = $user->name.' '.$user->lastname;

            $this->sendMail($params['email'], $params['token'], $params['url'], $params['username']);
            return response()->json(['message' => 'correo enviado a '.$params['email']]);
        }

        return response()->json(['message' => 'No existe usuario asociado a este correo ('.$params['email'].')']);
    }

    private function sendMail($email, $token, $url, $name = null)
    {
        (new MailHelper())->forgotPasswordEmail($email, $token, $url, $name);
    }

    private function getTokenUser(User $user)
    {
        $broker = $this->broker();

        return $broker->createToken($user);
    }

    
    public function sendResetLinkEmail(Request $request)
    {
        $this->validateEmail($request);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $response = $this->broker()->sendResetLink(
            $this->credentials($request)
        );

        return $response == Password::RESET_LINK_SENT
                    ? $this->sendResetLinkWithEmailResponse($request, $response)
                    : $this->sendResetLinkFailedWithEmailResponse($request, $response);
    }

    protected function sendResetLinkWithEmailResponse(Request $request, $response)
    {
        return $request->wantsJson()
                    ? new JsonResponse(['message' => trans($response)], 200)
                    : back()->with([
                        'status' => trans($response),
                        'email' => $request->input('email')
                    ]);
    }

    protected function sendResetLinkFailedWithEmailResponse(Request $request, $response)
    {
        if ($request->wantsJson()) {
            throw ValidationException::withMessages([
                'email' => [trans($response)],
            ]);
        }

        return back()
                ->with([
                    'email' => $request->input('email'),
                    'retry' => $request->input('retry')
                ])
                ->withInput($request->only('email'))
                ->withErrors(['email' => trans($response)]);
    }
}
