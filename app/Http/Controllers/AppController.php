<?php

namespace App\Http\Controllers;

use App\Mail\InactiveAccount;
use App\Models\Location;
use App\Traits\AIReview;
use App\Traits\ReviewQuestionSet;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AppController extends Controller {
    use ReviewQuestionSet, AIReview;

    private const string LOCATION_INACTIVE = 'inactive';
    private const string STRIPE_ACTIVE = 'active';
    private const string STRIPE_TRIALING = 'trialing';

    public function initialize(Request $request)
    {
        try {
            /* "loc" is the business location in the query string. Go to the home page if the location is missing */
            if (!$request->has('loc')) {
                return redirect('home');
            }
            $this->clearAndSetSessionLocation($request);
            $location = $this->getLocation($request->query('loc'));
            if (!$location) {
                throw new Exception('The location with ID '.$request->query('loc').' was not found in the database.');
            }
            // Handle inactive locations
            if ($location->status === self::LOCATION_INACTIVE) {
                return $this->handleInactiveLocation($location);
            }
            // Initialize the questions and start the review process
            if ($location->stripe_status === self::STRIPE_ACTIVE || $location->stripe_status === self::STRIPE_TRIALING) {
                if (!$this->prepQuestions($location->type, $location->customer_frequency)) {
                    throw new Exception('Question initialization failed for location: '.$request->query('loc'));
                }
                if ($this->initializeActiveStatus($location)) {
                    alert()->info('Thank you', $location->company.' appreciates your feedback.');
                    return redirect('/start');
                } else {
                    return view('pages.error', ['error' => 'Unable to initialize active status.']);
                }
            }
            // Handle all other cases (e.g., inactive stripe)
            return $this->notifyInactiveStripe($location);
        } catch (Exception $e) {
            Log::error('Initialization Error: '.$e->getMessage());
            return view('pages.error', ['error' => $e->getMessage()]);
        }
    }

    private function clearAndSetSessionLocation(Request $request): void
    {
        session()->flush();
        session()->put('locID', $request->query('loc'));
    }

    /** @noinspection PhpMethodParametersCountMismatchInspection */
    public function getLocation($inLoc)
    {
        try {
            $location = Location::select('locations.users_id', /** @lang text */ 'users.name', 'company', 'email',
                'users.type', 'engagement_frequency', 'loc_qty', 'loc_phone', 'support_email', 'locations.addr',
                'locations.status', 'min_rate',
                'stripe_status', 'CID', 'PID')
                ->join('users', 'locations.users_id', '=', 'users.id')
                ->join('subscriptions', 'locations.users_id', '=', 'subscriptions.user_id')
                ->where('locations.id', $inLoc)
                ->first();
            return $location ?: null;
        } catch (Exception $e) {
            Log::debug('Location ERROR: '.$e);
            return view('pages.error', ['error' => $e->getMessage()]);
        }
    }

    private function handleInactiveLocation($location): object
    {
        return redirect('notactive')->with(['company' => $location->company]);
    }

    /**
     * @throws Exception
     */
    private function initializeActiveStatus($location): bool
    {
        try {
            session()->put('location', $location);
            session()->put('registered', false);

            $threadID = $this->setThread();
            if (!$threadID) {
                throw new Exception("Failed to initialize thread ID.");
            }
            session()->put('threadID', $threadID);
            return true;

        } catch (Exception $e) {
            Log::error('Active Status Initialization Error: '.$e->getMessage());
            return false;
        }
    }


    private function notifyInactiveStripe($location): object
    {
        Mail::to($location->email)->send(new InactiveAccount([
            'name' => $location->name,
            'company' => $location->company,
            'status' => $location->stripe_status
        ]));
        return redirect('notactive')->with(['company' => $location->company]);
    }

    public function logout(Request $request): object
    {
        try {
            $request->session()->flush();
            $request->session()->regenerateToken();
            alert()->info('Logged Out', 'You are now logged out');
            return redirect('/home');
        } catch (Exception $e) {
            Log::error('Logout Error: '.$e->getMessage());
            return view('pages.error', ['error' => $e->getMessage()]);
        }
    }

//    public function getCustomerByEmail(Request $request, $inUser = '')
//    {
//        try {
//            $cust = Customer::where('users_id', $inUser)
//                ->where('email', $request->query('em'))
//                ->first();
//            return $cust ?? []; // Return empty array if no customer found
//        } catch (Exception $e) {
//            Log::error('Customer Retrieval Error: '.$e->getMessage());
//            return [];
//        }
//    }

}
