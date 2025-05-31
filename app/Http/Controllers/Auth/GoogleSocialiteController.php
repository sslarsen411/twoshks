<?php
// https://fajarwz.com/blog/create-login-with-google-oauth-using-laravel-socialite/
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Traits\AIReview;
use Exception;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\RedirectResponse;

class GoogleSocialiteController extends Controller {
    use AIReview;

    // public float $rating;

    public function redirectToGoogle(): RedirectResponse|\Illuminate\Http\RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleCallback(Request $request): object
    {
        try {
            // get user data from Google
            // ray(session()->all());
//            ray(session('rating')[0]);
//            $rate = session('rating')[0];

            $user = Socialite::driver('google')->user();
            //    ray($user);
//            $newUser = Customer::create([
//                'users_id' => session('location.users_id'),
//                'location_id' => session('locID'),
//                'oauth_provider' => 'google',
//                'oauth_uid' => $user['id'],
//                'first_name' => $user['given_name'],
//                'last_name' => $user['family_name'],
//                'email' => $user['email'],
//            ]);
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
            //         ray(session()->all());

            $newReview = $this->initReview($newUser);
            //        ray($newReview);

            session()->put('reviewID', $newReview->id);

            ray(session()->all());

            if (session('rating')[0] < session('location.min_rate')) {
                alert()->question('What happened?', 'Please tell us how we can improve your experience');
                return redirect('/care');
            }
            alert()->success('Success', 'You can begin your feedback');
            return redirect('/question');
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }
}
