<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card">
                    <div class="card-header"><h4>Player Details</h4></div>
                    <div class="card-body">
                        <div class="row">
                            <h5>
                                <img alt="Avatar" class="profile-photo-preview" :src="this.user.photo_url" />
                                {{ this.player.gamer_tag }}
                            </h5>
                        </div>

                        <div class="row">
                            <div class="col-8">
                                <p><strong>Tournaments:</strong></p>
                                <ul v-for="tournament in this.player.tournaments">
                                    <li>
                                        {{ tournament.name }}: {{ tournament.game_type.name }} |
                                        {{ tournamentStatus(tournament) }}
                                        <ul v-for="match in tournament.matches">
                                            <li>
                                                Round {{ match.round }} Match {{ match.number }} | Score:
                                                {{ getMatchScore(match) }} | Status: {{ matchStatus(match) }}
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'player-details',
    props: ['user', 'player', 'sessionMessage'],
    data() {
        return {}
    },
    mounted() {},
    computed: {},
    methods: {
        getMatchScore(match) {
            let score = 0

            if (this.isPlayerOne(match)) {
                score = match.player_one.pivot.score
            } else {
                score = match.player_two.pivot.score
            }

            if (score === null) {
                score = 'Results Pending'
            }

            return score
        },
        matchStatus(match) {
            if (match.winner_player_id === null) {
                return 'Results Pending'
            } else if (this.player.id === match.winner_player_id) {
                return 'Win'
            } else {
                return 'Loss'
            }
        },
        tournamentStatus(tournament) {
            if (tournament.winner_player_id === null) {
                return 'Results Pending'
            } else if (this.player.id === tournament.winner_player_id) {
                return 'Win'
            } else {
                return 'Loss'
            }
        },
        isPlayerOne(match) {
            let playerID = this.player.id

            if (match.player_one.id === playerID) {
                return true
            }

            return false
        },
    },
}
</script>
