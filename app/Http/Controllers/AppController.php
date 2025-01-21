<?php

namespace App\Http\Controllers;

use App\Mail\InactiveAccount;
use App\Models\Customer;
use App\Models\Location;
use App\Models\Question;
use App\Traits\AIReview;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use RealRashid\SweetAlert\Facades\Alert;

class AppController extends Controller {
    use AIReview;

    public function initialize(Request $request)
    {
        try {
            if ($request->has('loc')):
                session()->flush();
                session()->put('locID', $request->query('loc'));
                if (empty($location = $this->getLocation($request->query('loc')))) {
                    throw new Exception('The location with ID '.$request->query('loc').' was not found in the database.');
                }
                if ($location->status === 'inactive') {
                    return redirect('notactive')->with(['company' => $location->company]);
                }
                if ($location->stripe_status === 'active' || $location->stripe_status === 'trialing'):
                    Cache::rememberForever('questions', function () {
                        return Question::all();
                    });
                    session()->put('location', $location);
                    session()->put('desc');
                    if ($desc = $this->getDescription(session('location.PID'))) {
                        session()->put('desc', $desc);
                    }
                    session()->put('registered', false);
                    $threadID = $this->setThread();
                    if (!$threadID) {
                        throw new Exception("Failed to initialize thread ID.");
                    }
                    session()->put('threadID', $threadID);
                    Alert::success('Thank you from '.$location->company, 'Your feedback is invaluable to us.');
                    return redirect('/start');
                else:
                    // Handle inactive or non-trialing stripe status
                    Mail::to($location->email)->send(new InactiveAccount([
                        'name' => $location->name,
                        'company' => $location->company,
                        'status' => $location->stripe_status
                    ]));
                    return redirect('notactive')->with(['company' => $location->company]);
                endif;
            else:             // No location provided in the URL
                return redirect('home');
            endif;
        } catch (Exception $e) {
            Log::error('Initialization Error: '.$e->getMessage());
            return view('pages.error', ['error' => $e->getMessage()]);
        }
    }

    public function getLocation($inLoc)
    {
        try {
            $loc = Location::select('locations.users_id', 'users.name', 'company', 'email', 'loc_phone',
                'support_email', 'locations.status', 'min_rate', 'stripe_status', 'CID', 'PID')
                ->join('users', 'locations.users_id', '=', 'users.id')
                ->join('subscriptions', 'locations.users_id', '=', 'subscriptions.user_id')
                ->where('locations.id', '=', $inLoc)
                ->get();
            if (empty($loc[0])) {
                return null;
            } else {
                return $loc[0];
            }
        } catch (Exception $e) {
            Log::debug('Location ERROR: '.$e);
            return view('pages.error', ['error' => $e->getMessage()]);
        }
    }

    function getDescription($inPID)
    {
        try {
            $response = Http::get("https://maps.googleapis.com/maps/api/place/details/json?place_id=$inPID&key=".config('maps.maps_api'));
            $arr = json_decode($response->body(), true);
            if (array_key_exists('editorial_summary', $arr['result'])) {
                return $arr['result']['editorial_summary']['overview'];
            } else {
                return null;
            }
        } catch (Exception $e) {
            Log::error('Description Retrieval Error: '.$e->getMessage());
            return null;
        }
    }

    public function getCustomerByEmail(Request $request, $inUser = '')
    {
        try {
            $cust = Customer::where('users_id', $inUser)
                ->where('email', $request->query('em'))
                ->first();
            return $cust ?? []; // Return empty array if no customer found
        } catch (Exception $e) {
            Log::error('Customer Retrieval Error: '.$e->getMessage());
            return [];
        }
    }

    public function logout(Request $request)
    {
        try {
            $request->session()->flush();
            $request->session()->regenerateToken();

            Alert::info('Logged Out', 'You are now logged out');
            return redirect('/home');
        } catch (Exception $e) {
            Log::error('Logout Error: '.$e->getMessage());
            return view('pages.error', ['error' => $e->getMessage()]);
            //abort(500, 'An error occurred while logging out.');
        }
    }
}
