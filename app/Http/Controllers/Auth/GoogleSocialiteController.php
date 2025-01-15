<?php
// https://fajarwz.com/blog/create-login-with-google-oauth-using-laravel-socialite/
namespace App\Http\Controllers\Auth;

use Exception;
use App\Models\Customer;
use App\Traits\Assistant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use RealRashid\SweetAlert\Facades\Alert;

class GoogleSocialiteController extends Controller{
    use Assistant;
    public function redirectToGoogle()    {
        return Socialite::driver('google')->redirect();
    }
    public function handleCallback(Request $request){        
        try {
        // get user data from Google
            $user = Socialite::driver('google')->user();                                  
            $newUser = Customer::create([
                'users_id' => session('location.users_id'),
                'location_id' => session('locID'),
                'oauth_provider' => 'google',
                'oauth_uid' => $user['id'],
                'first_name' => $user['given_name'],
                'last_name' => $user['family_name'],
                'email' => $user['email'],
            ]);  ;
            session()->put('cust', $newUser);  
            $newReview = $this->initReview($newUser);
            session()->put('reviewID', $newReview->id);   
            ray(session()->all());              
            if(session('rating')[0] < session('location.min_rate')){            
                Alert::question('What happened?', 'Please tell us how we can improve your experience');
                return  redirect('/care');
              }
              Alert::success('Success', 'You can begin your feedback');
              return redirect('/question');    
        }catch (Exception $e){
            dd($e->getMessage());
        }
    }
}