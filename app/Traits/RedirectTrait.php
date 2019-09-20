<?php


namespace App\Traits;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

Trait RedirectTrait
{
    protected function redirectTo(Request $request = null)
    {
        $baseUrl    = url("/");
        $targetUrl  = redirect()
            ->intended()
            ->getTargetUrl();
        $redirectTo = $baseUrl;
        if (strcmp($targetUrl, $baseUrl) == 0) {
            // Indicates a strange situation when target url is the home page despite
            // the fact that there is a probability that user must be redirected to another page except home page

            if (strcmp(URL::previous(), route('login')) != 0) // User first had opened a page and then went to login
            {
                $redirectTo = URL::previous();
            }
        }
        else {
            $redirectTo = $targetUrl;
        }

        return $redirectTo;
    }
}
