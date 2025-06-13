<?php
// https://fajarwz.com/blog/create-login-with-google-oauth-using-laravel-socialite/
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Traits\AIReview;
use App\Traits\ReviewInitializer;
use Exception;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\RedirectResponse;

class GoogleSocialiteController extends Controller {
    use AIReview, ReviewInitializer;

    // public float $rating;

    public function redirectToGoogle(): RedirectResponse|\Illuminate\Http\RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleCallback(): ?object
    {
        try {
            $user = Socialite::driver('google')->user();
            $newUser = Customer::firstOrCreate([
                'users_id' => session('location.users_id'),
                'location_id' => session('locID'),
                'oauth_provider' => 'google',
                'oauth_uid' => $user['id'],
                'first_name' => $user['given_name'],
                'last_name' => $user['family_name'],
                'email' => $user['email'],
            ]);
            session()->put('cust', $newUser);
            $newReview = $this->initReview($newUser);
            session()->put('reviewID', $newReview->id);
            if (session('rating')[0] < session('location.min_rate')) {
                alert()->question('What happened?', 'Please tell us how we can improve your experience');
                return redirect('/care');
            }
            alert()->success($newUser->first_name.', You\'re ready to start', text: "Here's the first question");
            return redirect('/question');
        } catch (Exception $e) {
            Log::error('Google login error: '.$e->getMessage());
            return null;
        }
    }
}
