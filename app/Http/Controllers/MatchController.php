<?php

namespace App\Http\Controllers;

use Auth;
use Carbon\Carbon;
use App\Events\WonMatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\MatchResultsConfirmation;
use Illuminate\Support\Facades\Storage;
use App\Tournamentkings\Entities\Models\Match;
use App\TournamentKings\Managers\MatchManager;
use App\Tournamentkings\Entities\Models\Player;
use App\Tournamentkings\Entities\Models\Placement;
use App\Tournamentkings\Entities\Models\Tournament;
use App\Tournamentkings\Entities\Models\MatchPlayer;
use App\Tournamentkings\Entities\Models\PlacementType;

class MatchController extends Controller
{
    protected $auth_user_player_id = 0;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
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

    public function show(Match $match)
    {
        $match->load('tournament', 'players', 'round');
        $match->canPostResults      = $match->mayPostResult();
        $match->canEditResults      = $match->mayEditResult();
        $match->confirmedResults    = $match->isConfirmed();
        $match->areResultsPosted    = $match->areResultsPosted();
        $match->canConfirmResults   = $match->mayConfirmResult();
        $match->canDeleteResults    = $match->mayDeleteResult();
        $match->toJson();

        return view('matches.details')->with('match', $match);
    }

    public function create(Match $match)
    {
        abort_if((! $match->mayPostResult()), 403);
        $match->load('players', 'round', 'tournament');

        return view('matches.edit')->with(['match' => $match]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Match  $match
     * @return \Illuminate\Http\Response
     */
    public function edit(Match $match)
    {
        // check permissions
        abort_if((! $match->mayEditResult()), 403);
        $match->load('players', 'round', 'tournament');

        return view('matches.edit')->with(['match' => $match]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Match  $match
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Match $match)
    {
        // check permissions
//        abort_if((! $match->mayEditResult()), 403);

        // on posting a result, a result screen is required
        if (empty($match->winner_player_id)) {
            // validation
            $validatedData = $request->validate(['result_screen' => 'required']);
        }

        // validation
        $validatedData = $request->validate(
            [
            'player_1_gamer_tag' => 'required|exists:players,id|integer|different:player_2_gamer_tag',
            'player_2_gamer_tag' => 'required|exists:players,id|integer',
            'player_1_score'     => 'required|numeric|different:player_2_score',
            'player_2_score'     => 'required|numeric',
            'result_screen'      => 'image|file|mimes:png,jpg,jpeg,gif|max:20480', // 20mb size limit
            ],
            ['player_1_score.different' => 'The scores are a tie. Please play the game again.']
        );

        // and the winner is ...
        if ($request->player_1_score > $request->player_2_score) {
            $winner_player_id = $request->player_1_gamer_tag;
        } else {
            $winner_player_id = $request->player_2_gamer_tag;
        }

        // if a result screen is being uploaded
        if ($request->has('result_screen')) {
            // set a unique file name
            $result_screen        = 'match-'.$match->id.'.'.$request->file('result_screen')->extension();
            $result_screen_folder = config('tk.match_image_path');

            // store the file in s3
            Storage::cloud()->putFileAs($result_screen_folder, $request->file('result_screen'), $result_screen, 'public');
        }

        // update players records with scores
        $match->players()->syncWithoutDetaching(
            [
                $request->player_1_gamer_tag => ['score' => $request->player_1_score],
                $request->player_2_gamer_tag => ['score' => $request->player_2_score],
            ]
        );

        // update the match record with the winner
        $match->winner_player_id = $winner_player_id;
        // if a result screen is being uploaded
        if ($request->has('result_screen')) {
            $match->result_screen = $result_screen;
        }
        $match->result_posted_at = Carbon::now()->toDateTimeString();
        $match->result_posted_by = $this->auth_user_player_id;
        // the other player needs to confirm these results
        $match->result_confirmed_at = null;
        $match->result_confirmed_by = null;
        $match->save();

        // remove tournament winner
        $match->tournament->winner_player_id = null;
        $match->tournament->save();

        // find the other player
        if ($this->auth_user_player_id == $request->player_1_gamer_tag) {
            $other_player_id = $request->player_2_gamer_tag;
        } else {
            $other_player_id = $request->player_1_gamer_tag;
        }
        $other_player = Player::find($other_player_id);

        // re-query the resource based on id
        $match = Match::find($match->id);

        // send email to other player for confirmation
        Mail::to($other_player->user->email)->send(new MatchResultsConfirmation($match));
        // to test email display
        //return new MatchResultsConfirmation($match);

        // flash a success message
        $roundNumber = $match->round;
        $matchNumber = $match->number;
        $otherTag    = htmlspecialchars($other_player->gamer_tag);
        session()->flash(
            'tk_message_status',
            __('matches.update-success',
                compact('roundNumber', 'matchNumber', 'otherTag')
            )
        );

        return redirect('/tournaments/'.$match->tournament_id);
    }

    /**
     * Show the delete confirm page.
     */
    public function delete(Request $request, $match_id)
    {
        // find the resource based on id
        $match = Match::find($match_id);

        // check permissions
        abort_if((! $match->mayDeleteResult()), 403);

        return view('matches.delete')->with(['match' => $match]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Match  $match
     * @return \Illuminate\Http\Response
     */
    public function destroy(Match $match)
    {
        // check permissions
        abort_if((! $match->mayDeleteResult()), 403);

        // delete the file in s3
        Storage::cloud()->delete(config('tk.match_image_path').'/'.$match->result_screen);

        // update players records with null scores
        MatchPlayer::where('match_id', $match->id)->update(['score' => null]);

        // remove match results
        $match->winner_player_id = null;
        $match->result_screen    = null;
        $match->result_posted_at = null;
        $match->save();

        // remove tournament winner
        $match->tournament->winner_player_id = null;
        $match->tournament->save();

        // flash a success message
        session()->flash('tk_message_status', 'Match Results for Round '.$match->round.', Match '.$match->number.' have been deleted.');

        // redirect to tournament detail page
        return redirect('/tournaments/'.$match->tournament_id);
    }

    /**
     * Show the match result's confirm page.
     */
    public function confirmResults(Request $request, Match $match)
    {
        // check permissions
        if (! $match->mayConfirmResult()) {
            // flash an error message
            session()->flash('tk_message_status', "Confirming match results is not possible. Either you don't have the correct permissions or the results no longer need confirming.");
            // redirect to tournament detail page
            return redirect('/tournaments/'.$match->tournament_id);
        }

        return view('matches.confirmresults')->with(['match' => $match]);
    }

    /**
     * Confirm the match result's.
     */
    public function confirm(Request $request, Match $match)
    {
        // check permissions
        if (! $match->mayConfirmResult()) {
            // flash an error message
            session()->flash('tk_message_status', "Confirming match results is not possible. Either you don't have the correct permissions or the results no longer need confirming.");
            // redirect to tournament detail page
            return redirect('/tournaments/'.$match->tournament_id);
        }

        // update the match record with the winner
        $match->result_confirmed_by = $this->auth_user_player_id;
        $match->result_confirmed_at = Carbon::now()->toDateTimeString();
        $match->save();

        // find the tournament based on id
        $tournament = Tournament::find($match->tournament_id);
        $nextMatch  = MatchManager::findNextOpenMatch($tournament, $match);

        $winningPlayer = Player::find($match->winner_player_id);
        $losingPlayer  = $match->players->filter(function ($player) use ($match, $winningPlayer) {
            if ($player->id != $winningPlayer->id) {
                return $player;
            }
        })->first();

        if ($nextMatch) {
            if ($nextMatch->bronze_match) {
                MatchManager::registerPlayer($losingPlayer, $nextMatch);
                MatchManager::registerPlayer($winningPlayer, MatchManager::findFinalMatch($tournament));
            } else {
                MatchManager::registerPlayer($winningPlayer, $nextMatch);
            }
        }

        if ($match->final_match) {
            $tournament->winner_player_id = $winningPlayer->id;
            $tournament->save();

            $winningPlayerTournamentId = $tournament
                ->players
                ->where('id', $winningPlayer->id)
                ->first()
                ->pivot
                ->id;

            $placement = Placement::create([
                'player_tournament_id' => $winningPlayerTournamentId,
                'placement_type_name'  => PlacementType::GOLD,
            ]);

            event(new WonMatch($placement));

            if ($tournament->total_slots > 2) {
                $losingPlayerTournamentId = $tournament
                    ->players
                    ->where('id', $losingPlayer->id)
                    ->first()
                    ->pivot
                    ->id;

                $placement = Placement::create([
                    'player_tournament_id' => $losingPlayerTournamentId,
                    'placement_type_name'  => PlacementType::SILVER,
                ]);

                event(new WonMatch($placement));
            }
        }

        if ($match->bronze_match && $tournament->total_slots > 2) {
            $winningPlayerTournamentId = $tournament
                ->players
                ->where('id', $winningPlayer->id)
                ->first()
                ->pivot
                ->id;

            $placement = Placement::create([
                'player_tournament_id' => $winningPlayerTournamentId,
                'placement_type_name'  => PlacementType::BRONZE,
            ]);

            event(new WonMatch($placement));
        }

        $roundNumber = $match->round;
        $matchNumber = $match->number;
        session()->flash(
            'tk_message_status',
            __('matches.confirmation-success',
                compact('roundNumber', 'matchNumber'))
        );

        return redirect('/tournaments/'.$match->tournament_id);
    }

    public function upcoming(Request $request)
    {
        $user = Auth::user();

        return $user->player->matches->load('tournament', 'players')->map(function ($match) {
            $match->canPostResults      = $match->mayPostResult();
            $match->canEditResults      = $match->mayEditResult();
            $match->confirmedResults    = $match->isConfirmed();
            $match->areResultsPosted    = $match->areResultsPosted();
            $match->canConfirmResults   = $match->mayConfirmResult();
            $match->canDeleteResults    = $match->mayDeleteResult();

            return $match;
        })->toJson();
    }
}
