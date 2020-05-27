/*
 |--------------------------------------------------------------------------
 | Laravel Spark Bootstrap
 |--------------------------------------------------------------------------
 |
 | First, we will load all of the "core" dependencies for Spark which are
 | libraries such as Vue and jQuery. This also loads the Spark helpers
 | for things such as HTTP calls, forms, and form validation errors.
 |
 | Next, we'll create the root Vue application for Spark. This will start
 | the entire application and attach it to the DOM. Of course, you may
 | customize this script as you desire and load your own components.
 |
 */

require('spark-bootstrap')
require('./components/bootstrap')
require('./components/Tournaments/tournaments')
require('./components/Tournaments/createtournament')
require('./components/Tournaments/tournamentdetail')
require('./components/Settings/PlayerProfile')
require('./square')
require('./components/Settings/PlayerProfiles/update-player-information')

import BootstrapVue from 'bootstrap-vue'
// import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'
import _ from 'lodash'
Vue.prototype._ = _

Vue.component('tournament-bracket', require('./components/Tournaments/TournamentBracket.vue'), {
    name: 'tournament-bracket',
})

Vue.component('match-details', require('./components/Matches/MatchDetails'), {
    name: 'match-details',
})

Vue.component('player-profile', require('./components/Settings/PlayerProfile'), {
    name: 'player-profile',
})

Vue.component('update-gamer-tag', require('./components/Settings/Player-Profile/UpdateGamerTag'), {
    name: 'update-gamer-tag',
})

Vue.component('update-platform-selection', require('./components/Settings/Player-Profile/UpdatePlatformSelection'), {
    name: 'update-platform-selection',
})

Vue.component('player-details', require('./components/Players/PlayerDetails'), {
    name: 'player-details',
})

Vue.use(BootstrapVue)

var app = new Vue({
    mixins: [require('spark')],
})
