<?php

namespace App\Http\Controllers;

use App\Mail\InactiveAccount;
use App\Models\Location;
use App\Traits\AIReview;
use App\Traits\GooglePlaces;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use InvalidArgumentException;
use NumberFormatter;
use RuntimeException;

class AppController extends Controller {
    use AIReview, GooglePlaces;

    private const string LOCATION_INACTIVE = 'inactive';
    private const string STRIPE_ACTIVE = 'active';
    private const string STRIPE_TRIALING = 'trialing';

    public function initialize(Request $request)
    {
        try {
            // Early return when location is missing
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
            // Handle active or trialing stripe status
            if ($location->stripe_status === self::STRIPE_ACTIVE || $location->stripe_status === self::STRIPE_TRIALING) {
                return $this->initializeActiveStatus($location);
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
    private function initializeActiveStatus($location): object
    {

        session()->put('location', $location);
        $this->prepQuestions();
        //session()->put('desc', $this->getDescription(session('location.PID')) ?? null);
        session()->put('registered', false);

        $threadID = $this->setThread();
        if (!$threadID) {
            throw new Exception("Failed to initialize thread ID.");
        }
        session()->put('threadID', $threadID);
        alert()->info('Thank you', $location->company.' appreciates your feedback.');
        return redirect('/start');
    }

    /**
     * @throws Exception
     */
    private function prepQuestions(): void
    {
        try {
            $jsonPath = public_path('questions.json');
            if (!file_exists($jsonPath)) {
                throw new RuntimeException('Questions file not found');
            }

            $json = file_get_contents($jsonPath);
            if ($json === false) {
                throw new RuntimeException('Failed to read questions file');
            }

            $questArr = json_decode($json, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new RuntimeException('Invalid JSON format: '.json_last_error_msg());
            }

            $type = session('location.type');
            $freq = session('location.customer_frequency');

            if (!in_array($type, ['retail', 'service'])) {
                throw new InvalidArgumentException('Invalid location type');
            }

            if ($type === 'service' && !$freq) {
                throw new InvalidArgumentException('Customer frequency required for service type');
            }
            /** @var string $type $freq */
            $specific = match ($type) {
                'retail' => $questArr[$type] ?? [],
                default => $questArr[$type][$freq] ?? [],
            };

            if (!isset($questArr['initial'], $questArr['general'])) {
                throw new RuntimeException('Missing required question sections');
            }

            $questions = array_merge($questArr['initial'], $specific, $questArr['general']);
            session()->put('questions', $questions);
            $n = new NumberFormatter("en", NumberFormatter::SPELLOUT);
            session()->put('question_num', count($questions));
            session()->put('question_num_txt', $n->format(count($questions)));

        } catch (Exception $e) {
            Log::error('Error preparing questions: '.$e->getMessage());
            throw $e;
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
