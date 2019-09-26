<?php
/**
 * Created by PhpStorm.
 * User: sohrab
 * Date: 2018-10-22
 * Time: 17:21
 */

namespace App\Traits;

use App\Events\MobileVerificationCodeGenerated;
use App\Notifications\MobileVerified;
use App\Notifications\VerifyMobile;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

trait MustVerifyMobileNumberTrait
{
    /**
     * Determine if the user has verified their mobile number.
     *
     * @return bool
     */
    public function hasVerifiedMobile()
    {
        return ! is_null($this->mobile_verified_at);
    }

    /**
     * Mark the given user's mobile as verified.
     *
     * @return bool
     */
    public function markMobileAsVerified()
    {
        return $this->forceFill([
            'mobile_verified_at' => $this->freshTimestamp(),
        ])->save();
    }

    /**
     * Send the mobile verification notification.
     *
     * @return void
     */
    public function sendMobileVerificationNotification()
    {
        if ($this->setMobileVerificationCode()) {
//            $this->notify(new VerifyMobile());
        }
    }

    /**
     * generate a verification code for given user
     *
     * @return bool
     * @throws \Exception
     */
    public function setMobileVerificationCode(): bool
    {
        Log::info('before event');
        event(new MobileVerificationCodeGenerated($this , Carbon::now('Asia/Tehran')));
        return true;


        $verificationCode = $this->getMobileVerificationCode();
        if(isset($verificationCode)) {
            return true;
        }


        $verificationCode = random_int(10000, 99999);
        $result = $this->forceFill([
            'mobile_verified_code' => $verificationCode,
        ])->save();

        if($result){
            event(new MobileVerificationCodeGenerated($this , Carbon::now('Asia/Tehran')));

            return true;
        }
        return false;
    }

    /**
     * Send the mobile verified notification.
     *
     * @return void
     */
    public function sendMobileVerifiedNotification()
    {
        $this->notify(new MobileVerified());
    }

    /**
     * get user's verification code
     *
     * @return string
     */
    public function getMobileVerificationCode()
    {
        return $this->mobile_verified_code;
    }
}
