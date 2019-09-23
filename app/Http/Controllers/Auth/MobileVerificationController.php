<?php

namespace App\Http\Controllers\Auth;

use App\Events\MobileVerified;
use App\Http\Controllers\Controller;
use App\Http\Requests\SubmitVerificationCode;
use App\User;
use Illuminate\Http\{Request, Response};
use Illuminate\Support\Facades\Redirect;

class MobileVerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Mobile Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling mobile verification for any
    | user that recently registered with the application. Mobiles may also
    | be re-sent if the user didn't receive the original email message.
    |
    */


    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('throttle:1,1')
            ->only('resend');
        $this->middleware('throttle:10,1')
            ->only('verify');
    }

    /**
     * Show the mobile verification notice.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        return $request->user()
            ->hasVerifiedMobile() ? back() : view('auth.verify');
    }

    /**
     * Mark the authenticated user's mobile number as verified.
     *
     * @param  SubmitVerificationCode  $request
     * @param                          $code
     *
     * @return \Illuminate\Http\Response
     */
    public function verify(SubmitVerificationCode $request)
    {
        $user     = $request->user();
        $verified = false;
        if ($request->code == $user->getMobileVerificationCode() && $user->markMobileAsVerified()) {
            event(new MobileVerified($user));
            $verified = true;
        }

        if ($verified) {
            return response()->json([
                'user'    => $user,
                'message' => __('messages.mobile_is_verified'),
            ]);
        }
        else {
            $request->expectsJson() ? abort(Response::HTTP_FORBIDDEN,
                __('messages.code_is_wrong')) : Redirect::route('verification.mobile.notice');
        }
    }

    /**
     * Resend the mobile verification notification.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function resend(Request $request)
    {
        /** @var User $user */
        $user = $request->user();
        if ($user->hasVerifiedMobile()) {
            return $request->expectsJson() ? abort(Response::HTTP_FORBIDDEN,
                __('messages.mobile_is_verified')) : route('web.home');
        }

        $user->sendMobileVerificationNotification();

        return response()->json([
            'message' => __('messages.code_is_sent'),
        ]);
    }
}
