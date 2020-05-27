<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\TournamentDeleted;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\TournamentCreateRequest;
use App\Http\Requests\TournamentUpdateRequest;
use App\TournamentKings\Managers\RoundManager;
use App\Tournamentkings\Entities\Models\Player;
use App\TournamentKings\Managers\BalanceManager;
use App\Tournamentkings\Entities\Models\GameType;
use App\Tournamentkings\Entities\Models\Tournament;
use App\TournamentKings\Managers\TournamentManager;
use App\Tournamentkings\Entities\Models\EntryFeeType;

class TournamentController extends Controller
{
    protected $auth_user_player_id = 0;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['verified', 'auth']);

        $this->middleware(function (Request $request, $next) {
            if (Auth::check()) {
                $this->auth_user_player_id = Auth::user()->player->id;
            }

            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tournaments = Tournament::orderBy('start_datetime', 'ASC')->get()->toJson();

        return view('tournaments')->with(['tournaments' => $tournaments]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Tournament $tournament)
    {
        $tournament->tournament_type = 'public';

        $game_types = GameType::orderBy('name')->where('active', 1)->get();

        return view('tournament-create')->with([
            'tournament'       => $tournament,
            'game_types'       => $game_types,
            'tournament_types' => Tournament::TOURNAMENT_TYPES,
            'entry_fee_types'  => EntryFeeType::getMappedTypes(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TournamentCreateRequest $request)
    {
        $request = $request->toArray();

        $tk_tournament_message_status = __('tournaments.created', ['name' => $request['name']]);
        if ($request['tournament_type'] == 'private') {
            $request['password'] = \Hash::make($request['password']);
        } else {
            unset($request['password']);
        }

        $request['created_by_player_id'] = $this->auth_user_player_id;

        unset($request['busy']);
        unset($request['errors']);
        unset($request['successful']);
        unset($request['password_confirmation']);

        $newTournament = Tournament::create($request);

        $tournamentWithRounds           = TournamentManager::buildRounds($newTournament);
        $tournamentWithRoundsAndMatches = RoundManager::buildMatches($tournamentWithRounds);

        // flash a success message
        session()->flash('tk_message_status', $tk_tournament_message_status);

        return '/tournaments/'.$newTournament->id;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Tournament  $tournament
     * @return \Illuminate\Http\Response
     */
    public function show(Tournament $tournament)
    {
        setlocale(LC_MONETARY, 'en_US');

        $balance          = $tournament->balance ? $tournament->balance->balance : 0;
        $formattedBalance = money_format('%+.2n', $balance);

        return view('tournament-detail')->with([
            'tournament'          => $tournament->load(['rounds.matches.players', 'matches']),
            'auth_user_player_id' => $this->auth_user_player_id,
            'tournament_types'    => Tournament::TOURNAMENT_TYPES,
            'formattedBalance'    => $formattedBalance,
            'balance'             => $balance,
            'placement'           => $tournament->placement(auth()->user()->player),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Tournament  $tournament
     * @return \Illuminate\Http\Response
     */
    public function edit(Tournament $tournament)
    {
        // check permissions
        abort_if((! $tournament->mayEdit()), 403);

        $game_types = GameType::orderBy('name')->get();

        return view('tournament-create')->with([
            'tournament'       => $tournament,
            'game_types'       => $game_types,
            'tournament_types' => Tournament::TOURNAMENT_TYPES,
            'entry_fee_types'  => EntryFeeType::getMappedTypes(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Tournament  $tournament
     * @return \Illuminate\Http\Response
     */
    public function update(TournamentUpdateRequest $request, Tournament $tournament)
    {
        abort_if((! $tournament->mayEdit()), 403);

        $request            = $request->toArray();

        if ($request['tournament_type'] == 'private') {
            // password is being changed
            if (strlen($request['password'])) {
                $request['password'] = \Hash::make($request['password']);
            }
            // admin is switching from a public to a private tournament
            if ($tournament->tournament_type == 'public') {
                $request['password'] = \Hash::make($request['password']);
            }
        } else {
            unset($request['password']);
        }

        $request['created_by_player_id'] = $tournament->created_by_player_id;

        unset($request['busy']);
        unset($request['errors']);
        unset($request['successful']);
        unset($request['password_confirmation']);

        Tournament::where('id', $tournament->id)->update($request);
        $updatedTournament = Tournament::find($tournament->id);

        if ($updatedTournament) {
            $updatedTournamentWithRounds = TournamentManager::updateTournament($tournament, $updatedTournament);
            RoundManager::buildMatches($updatedTournamentWithRounds);

            $tk_tournament_message_status = 'Tournament "'.$request['name'].'" updated.';
        } else {
            $tk_tournament_message_status = 'Tournament "'.$request['name'].'" failed to update.';
        }

        // flash a success message
        session()->flash('tk_message_status', $tk_tournament_message_status);

        return '/tournaments/'.$tournament->id;
    }

    /**
     * Show the delete confirm page.
     */
    public function delete(Request $request, Tournament $tournament)
    {
        // check permissions
        abort_if((! $tournament->mayDelete()), 403);

        return view('tournaments.delete')->with([
            'tournament'       => $tournament,
            'tournament_types' => Tournament::TOURNAMENT_TYPES,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Tournament  $tournament
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tournament $tournament)
    {
        // check permissions
        abort_if((! $tournament->mayDelete()), 403);

        $tournament_name = $tournament->name;

        // Called here for now, but we'll want it in a more general place eventually.
        event(new TournamentDeleted($tournament));
        // delete from tournaments
        //   and cascade delete player_tournament
        //   and cascade delete matches
        //   and cascade delete match_player
        $tournament->destroy($tournament->id);

        // flash a success message
        session()->flash('tk_message_status', "Tournament '$tournament_name' has been deleted.");

        // redirect to tournament landing page
        return redirect('/tournaments');
    }

    /**
     * Register a new player.
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request, Tournament $tournament)
    {
        if (! $tournament->mayRegister()) {
            // flash a warning message
            session()->flash('tk_message_status', "You could not be registered for this tournament. It's either full or you have already registered.");
            // refresh tournament detail page
            return redirect("/tournaments/$tournament->id");
        }

        $errors = new MessageBag;

        if ($tournament->tournament_type == 'private' && ! $tournament->entryFeeType->is_free) {
            // validate password on private tournaments

            $validatedData = $request->validate(
                [
                    'password' => [
                        'required',
                        'min:6',
                        function ($attribute, $value, $fail) use ($tournament) {
                            if (! \Hash::check($value, $tournament->password)) {
                                $fail('The password is incorrect. Please try again.');
                            }
                        },
                    ],
                ],
                [
                    'password.min'    => 'The password is incorrect. Please try again.',
                ]
            );
        }

        if ($tournament->entryFeeType->is_flat_fee) {
            $data = [
                'amount' => $tournament->entry_fee,
            ];
            $errors = BalanceManager::completeTransactionToTournament($tournament, $data);
        }

        if ($tournament->entryFeeType->is_target_pot) {
            $data = [
                'amount' => $tournament->player_deposit,
            ];
            $errors = BalanceManager::completeTransactionToTournament($tournament, $data);
        }

        if ($errors->isNotEmpty()) {
            $redirect = redirect(route('register.confirm', $tournament))->withErrors($errors);
        } else {
            $messages = [];

            if (! $tournament->entryFeeType->is_free) {
                $messages = [
                    'success' => __('balance.deposit.success'),
                ];
            }

            // register player for tournament
            $tournament->players()->attach(
                $this->auth_user_player_id,
                ['created_at' => now()]
            );

            $player = Player::find($this->auth_user_player_id);

            TournamentManager::registerPlayer($player, $tournament);

            // flash a success message
            session()->flash('tk_message_status', __('tournaments.registration-success'));
            $redirect = redirect("/tournaments/$tournament->id")->with($messages);
        }

        return $redirect;
    }

    /**
     * Register a new player.
     *
     * @return \Illuminate\Http\Response
     */
    public function registerConfirm(Request $request, Tournament $tournament)
    {
        $tournament->load('entryFeeType');

        if (! $tournament->mayRegister()) {
            session()->flash('tk_message_status', "You could not be registered for this tournament. It's either full or you have already registered.");

            return redirect("/tournaments/$tournament->id");
        }

        if ($tournament->tournament_type == 'public' && $tournament->entry_fee_type_name == EntryFeeType::FREE) {
            return $this->register($request, $tournament);
        }

        setlocale(LC_MONETARY, 'en_US');

        $balance          = $tournament->balance ? $tournament->balance->balance : 0;
        $formattedBalance = money_format('%+.2n', $balance);

        return view('tournaments.register')->with([
            'tournament'       => $tournament,
            'tournament_types' => Tournament::TOURNAMENT_TYPES,
            'balance'          => $formattedBalance,
        ]);
    }

    public function fetch(Request $request)
    {
        $tournamentQuery = Tournament::with(
            [
                'gameType',
                'createdByPlayer',
            ]
        )->orderBy('start_datetime', 'DESC')
            ->get()
            ->map(function ($tournament) {
                /*
                 * This seemingly redundant assignment will make sure that the attribute
                 * shows up in the request data.
                 */
                $tournament->total_pot = $tournament->total_pot;

                return $tournament;
            });

        if ($request->input('open')) {
            $tournamentQuery = $tournamentQuery->filter(function ($item) {
                $item->available_slots = $item->available_slots;

                return $item->available_slots > 0;
            })->values();
        }

        return $tournamentQuery;
    }
}
