<template>
    <div class="tournament-container">
        <div class="tournament-bracket tournament-bracket--rounded">
            <div
                class="tournament-bracket__round"
                v-bind:class="{ 'tournament-bracket__round--semifinals': notFirstRound(round) }"
                v-for="round in this.matches"
            >
                <h3 class="tournament-bracket__round-title">Round {{ round.number }}</h3>
                <ul class="tournament-bracket__list">
                    <li class="tournament-bracket__item" v-for="match in round.games">
                        <div class="tournament-bracket__match" tabindex="0" @click="openMatchDetails(match)">
                            <table class="tournament-bracket__table">
                                <caption class="tournament-bracket__caption" v-show="match.bye">
                                    <span>Bye Match</span>
                                </caption>
                                <thead class="sr-only">
                                    <tr>
                                        <th>Player</th>
                                        <th>Score</th>
                                    </tr>
                                </thead>
                                <tbody class="tournament-bracket__content">
                                    <tr
                                        class="tournament-bracket__team "
                                        v-bind:class="{ 'tournament-bracket__team--winner': isWinner(player, match) }"
                                        v-for="player in match.players"
                                    >
                                        <td class="tournament-bracket__country">
                                            <abbr class="tournament-bracket__code" :title="player.name"
                                                ><a v-if="player.id > 0" :href="'/player/' + player.id + '/details'">{{
                                                    player.name
                                                }}</a>
                                                <span v-else>{{ player.name }}</span>
                                            </abbr>
                                            <span class="tournament-bracket__flag" aria-label="Avatar"></span>
                                        </td>
                                        <td class="tournament-bracket__score">
                                            <span class="tournament-bracket__number">{{ player.score }}</span>
                                            <span
                                                v-if="player.id > 0 && isWinner(player, match) && isLastRound(round)"
                                                class="tournament-bracket__medal tournament-bracket__medal--gold fa fa-trophy"
                                                aria-label="Gold medal"
                                            ></span>
                                            <span
                                                v-if="player.id > 0 && !isWinner(player, match) && isLastRound(round)"
                                                class="tournament-bracket__medal tournament-bracket__medal--silver fa fa-trophy"
                                                aria-label="Silver medal"
                                            >
                                            </span>
                                            <span
                                                v-if="
                                                    player.id > 0 && isWinner(player, match) && isBronzeMedalGame(round)
                                                "
                                                class="tournament-bracket__medal tournament-bracket__medal--bronze fa fa-trophy"
                                                aria-label="Bronze medal"
                                            >
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'tournament-bracket',
    props: ['rounds'],

    data() {
        return {
            matches: [],
        }
    },
    mounted() {
        let formattedMatches = []

        this.rounds.forEach(round => {
            formattedMatches.push(this.formatRounds(round))
        })

        this.matches = formattedMatches
    },
    methods: {
        formatRounds(round) {
            let games = []

            round.matches.forEach(game => {
                games.push(this.formatGames(game))
            })

            return {
                games: games,
                number: round.number,
            }
        },
        formatGames(game) {
            let player1 = {
                id: 0,
                name: 'Empty',
                score: 0,
            }
            let player2 = {
                id: 0,
                name: 'Empty',
                score: 0,
            }

            if (game.players.length === 2) {
                player1.id = game.players[0].id
                player1.name = game.players[0].gamer_tag
                player1.score = game.players[0].pivot.score ? game.players[0].pivot.score : 0

                if (game.players[0].id === game.winner_player_id) {
                    player1.winner = true
                }

                player2.id = game.players[1].id
                player2.name = game.players[1].gamer_tag
                player2.score = game.players[1].pivot.score ? game.players[1].pivot.score : 0

                if (game.players[1].id === game.winner_player_id) {
                    player2.winner = true
                }
            } else if (game.players.length === 1) {
                player1.id = game.players[0].id
                player1.name = game.players[0].gamer_tag
                player1.score = game.players[0].pivot.score ? game.players[0].pivot.score : 0

                if (game.players[0].id === game.winner_player_id) {
                    player1.winner = true
                }
            }

            let players = []

            players.push(player1)
            players.push(player2)

            return {
                players: players,
                match_id: game.id,
                bye: game.bye,
                winner_player_id: game.winner_player_id,
            }
        },
        openMatchDetails(match) {
            if (match.players.length === 2 && match.players[0].id !== 0 && match.players[1].id !== 0) {
                return (window.location = '/match/' + match.match_id + '/details')
            }

            return false
        },
        isWinner(player, match) {
            let winner_player_id = match.winner_player_id

            if (winner_player_id && winner_player_id === player.id) {
                return true
            }

            return false
        },
        notFirstRound(round) {
            if (round.number > 1) {
                return true
            }

            return false
        },
        isLastRound(round) {
            if (round.number < this.rounds.length) {
                return false
            }

            return true
        },
        isBronzeMedalGame(round) {
            if (round.number === this.rounds.length - 1 && round.games.length === 1) {
                return true
            }

            return false
        },
        isByeMatch(match) {
            return match.bye
        },
    },
}
</script>
