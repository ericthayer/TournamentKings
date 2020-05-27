import UpcomingMatches from './Matches/UpcomingMatches'
import UpcomingTournaments from './Tournaments/UpcomingTournaments'

Vue.component('home', {
    name: 'home',
    components: {
        UpcomingMatches,
        UpcomingTournaments,
    },
    props: ['user', 'open'],
    data() {
        return {
            matches: [],
            tournaments: [],
        }
    },
    computed: {
        hasMatches() {
            return this.matches.length ? true : false
        },
        hasTournaments() {
            return this.tournaments.length ? true : false
        },
    },
    mounted() {
        let url = 'api/matches'
        axios.get(url).then(response => {
            this.matches = response.data
        })

        this.fetchTournaments()
    },

    methods: {
        async fetchTournaments() {
            console.log(this.open)
            let url = 'api/tournaments'

            if (this.open) {
                url = 'api/tournaments?open=true'
            }
            let tournaments = await axios
                .get(url)
                .then(response => response.data)
                .catch(error => console.error(error.message))
            this.tournaments = tournaments
        },
        clickOnTournament() {
            console.log('click on tournament')
        },
        clickOnMatch(match) {
            console.log('click on match')
        },
    },
})
