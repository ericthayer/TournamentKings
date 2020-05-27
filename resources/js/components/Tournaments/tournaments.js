import UpcomingTournaments from './UpcomingTournaments'

Vue.component('tournaments', {
    name: 'tournaments',
    components: {
        UpcomingTournaments,
    },
    props: ['user'],
    data() {
        return {
            tournaments: [],
        }
    },
    computed: {
        hasTournaments() {
            return this.tournaments.length ? true : false
        },
    },
    mounted() {
        this.fetchTournaments()
    },
    methods: {
        async fetchTournaments() {
            let tournaments = await axios
                .get('api/tournaments')
                .then(response => response.data)
                .catch(error => console.error(error.message))
            this.tournaments = tournaments
        },
        clickOnTournament() {},
    },
})
