<?php

namespace App\Notifications;

use App\Broadcasting\MedianaPatternChannel;
use App\Classes\sms\MedianaMessage;
use App\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\SerializesModels;

class MobileVerified extends Notification implements ShouldQueue
{
    use Queueable, SerializesModels;

    const MEDIANA_PATTERN_CODE_USER_MOBILE_VERIFIED = '';

    public $timeout = 120;

    /**
     * @var User
     */
    protected $user;

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     *
     * @return array
     */
    public function via($notifiable)
    {
        $this->user = $notifiable;

        return [
            MedianaPatternChannel::class,
//            'mail',
        ];
    }

//    /**
//     * Get the mail representation of the notification.
//     *
//     * @param  mixed  $notifiable
//     *
//     * @return \Illuminate\Notifications\Messages\MailMessage
//     */
//    public function toMail($notifiable)
//    {
//        return (new MailMessage)->line($this->msg())
//            ->action('برو به سایت آلاء', url('/'));
//    }

    private function msg(): string
    {
        return "آلایی عزیز، شماره موبایل شما در آلاء تایید شد.";
    }

    /**
     * @param $notifiable
     *
     * @return MedianaMessage
     */
    public function toMediana($notifiable)
    {
        return (new MedianaMessage())->content($this->msg())
            ->setInputData($this->getInputData())
            ->setPatternCode(self::MEDIANA_PATTERN_CODE_USER_MOBILE_VERIFIED)
            ->sendAt(Carbon::now());
    }

    private function getInputData(): array
    {
        return [
            'name' => $this->getUserFullName(),
            'request' => 'شماره موبایل',
            'supportLink' => '',
            'site' => __('Site Name'),
        ];
    }

    /**
     * @return mixed
     */
    private function getUserFullName():string
    {
        $userFullName = optional($this->user)->full_name;
        return (isset($userFullName))?$userFullName:'کاربر' ;
    }
}
