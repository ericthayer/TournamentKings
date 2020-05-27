import TournamentForm from './TournamentForm'

Vue.component('createtournament', {
    name: 'createtournament',
    components: {
        TournamentForm,
    },
    props: ['user', 'tournament', 'errors'],
    data() {
        return {
            formUrl: '/tournaments',
            date: '',
            time: '',
            form: new SparkForm({
                name: '',
                game_type_id: '',
                tournament_type: '',
                entry_fee_type_name: '',
                entry_fee: '',
                target_pot: '',
                password: '',
                password_confirmation: '',
                description: '',
                start_datetime: '',
                total_slots: 0,
            }),
        }
    },
    mounted() {
        this.date = moment(this.startDateTime)
            .format('YYYY-MM-DD')
            .toString()

        let time = moment(this.startDateTime).add(1, 'hours')

        if (this.tournament.id) {
            this.formUrl = '/tournaments/' + this.tournament.id
            this.form.name = this.tournament.name
            this.form.game_type_id = this.tournament.game_type_id
            this.form.tournament_type = this.tournament.tournament_type
            this.form.description = this.tournament.description
            this.form.start_datetime = this.datetime
            this.form.total_slots = this.tournament.total_slots
            this.form.entry_fee_type_name = this.tournament.entry_fee_type_name
            this.form.entry_fee = this.tournament.entry_fee
            this.form.target_pot = this.tournament.target_pot

            time = moment(this.startDateTime)
        }

        this.time = moment(time)
            .format('HH:mm')
            .toString()
    },
    computed: {
        hasTournament() {
            return !!this.tournament
        },
        showPasswords() {
            return this.form.tournament_type === 'private'
        },
        showEntryFee() {
            return this.form.entry_fee_type_name === 'flat-fee'
        },
        showTargetPot() {
            return this.form.entry_fee_type_name === 'target-pot'
        },
        datetime() {
            let formattedDateTime = moment(this.date + ' ' + this.time)
                .format('YYYY-MM-DD H:mm:ss')
                .toString()
            this.form.start_datetime = formattedDateTime
            return formattedDateTime
        },
        slotsNotPowerOfTwo() {
            return !Number.isInteger(Math.log2(this.form.total_slots))
        },
        startDateTime() {
            if (this.tournament.start_datetime) {
                return this.tournament.start_datetime
            } else {
                return moment()
            }
        },
    },
    methods: {
        register() {
            if (this.tournament.id) {
                Spark.put(this.formUrl, this.form).then(response => {
                    window.location.href = response
                })
            } else {
                Spark.post(this.formUrl, this.form).then(response => {
                    window.location.href = response
                })
            }
        },
    },
})
