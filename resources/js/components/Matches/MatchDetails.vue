<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card">
                    <div class="card-header"><h4>Match Details</h4></div>

                    <div class="card-body">
                        <div v-if="this.sessionMessage" class="alert alert-success" role="alert">
                            {{ this.sessionMessage }}
                        </div>

                        <h5>Details:</h5>

                        <h6>
                            Match:
                            <span class="text-muted"
                                >{{ match.tournament.name }} | Round {{ match.round.number }} | Match
                                {{ match.number }}</span
                            >
                        </h6>
                        <h6>
                            Tournament Type:
                            <span class="text-muted">{{
                                _.startCase(_.toLower(match.tournament.tournament_type))
                            }}</span>
                        </h6>
                        <p>
                            Players:
                            <span class="text-muted"
                                >{{ match.player_one.gamer_tag }} vs. {{ match.player_two.gamer_tag }}</span
                            >
                        </p>
                        <h6>
                            Status: <span class="text-muted">{{ getMatchStatus() }}</span>
                        </h6>
                        <h6 v-if="matchWon()">
                            Winner: <span class="text-muted">{{ getWinner() }}</span>
                        </h6>
                        <h6 v-if="matchWon()">
                            {{ match.player_one.gamer_tag }} Score:
                            <span class="text-muted"> : {{ match.player_one.pivot.score }} </span>
                        </h6>
                        <h6 v-if="matchWon()">
                            {{ match.player_two.gamer_tag }} Score:
                            <span class="text-muted"> : {{ match.player_two.pivot.score }} </span>
                        </h6>
                        <div v-if="">
                            <h6>Result Screen:</h6>
                            <img class="tk-thumb" :src="match.result_screen_url" />
                        </div>

                        <button class="btn-tk" @click="returnToTournament()">Return to Tournament</button>

                        <button v-if="canPostResults" class="btn-tk" @click="postResults()">Post Results</button>
                        <button v-if="canEditResults" class="btn-tk" @click="editResults()">Edit Results</button>
                        <button v-if="canDeleteResults" class="btn-tk" @click="deleteResults()">Delete Results</button>
                        <button v-if="canConfirmResults" class="btn-tk" @click="confirmResults()">
                            Confirm Results
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    name: 'match-details',
    props: ['user', 'match', 'sessionMessage'],
    data() {
        return {
            tournamentCreator: '',
        }
    },
    mounted() {
        this.getTournamentCreator(this.match.tournament.created_by_player_id)
    },
    computed: {
        canPostResults() {
            return this.match.canPostResults
        },
        canEditResults() {
            return this.match.canEditResults
        },
        canDeleteResults() {
            return this.match.canDeleteResults
        },
        canConfirmResults() {
            return this.match.canConfirmResults
        },
    },
    methods: {
        matchWon() {
            if (this.match.winner_player_id) {
                return true
            }

            return false
        },
        getWinner() {
            if (this.match.player_one.id === this.match.winner_player_id) {
                return this.match.player_one.gamer_tag
            } else if (this.match.player_two.id === this.match.winner_player_id) {
                return this.match.player_two.gamer_tag
            }

            return null
        },
        getTournamentCreator(player_id) {
            axios.get('/player/' + player_id + '/details').then(response => (this.tournamentCreator = response.data))
        },
        returnToTournament() {
            window.location = '/tournaments/' + this.match.tournament.id
        },
        getMatchStatus() {
            let status = 'Waiting for match to start.'

            if (this.match.players.length > 1) {
                status = 'Pending Results...'
            }

            if (this.match.result_posted_by) {
                status = 'Pending Result Confirmation...'
            }

            if (this.match.result_confirmed_by) {
                if (this.match.player_one.id === this.match.result_confirmed_by) {
                    status =
                        'Match Results Posted by: ' +
                        this.match.player_two.gamer_tag +
                        ' | Match confirmed by: ' +
                        this.match.player_one.gamer_tag
                } else {
                    status =
                        'Match Results Posted by: ' +
                        this.match.player_one.gamer_tag +
                        ' | Match confirmed by: ' +
                        this.match.player_two.gamer_tag
                }
            }

            return status
        },
        postResults() {
            window.location = '/matches/' + this.match.id + '/create'
        },
        editResults() {
            window.location = '/matches/' + this.match.id + '/edit'
        },
        confirmResults() {
            window.location = '/matches/' + this.match.id + '/confirmresults'
        },
        deleteResults() {
            window.location = '/matches/' + this.match.id + '/delete'
        },
    },
}
</script>
