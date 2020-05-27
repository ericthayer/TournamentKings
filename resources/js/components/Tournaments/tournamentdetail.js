Vue.component('tournamentdetail', {
    name: 'tournamentdetail',
    props: ['user', 'tournament'],
    data() {
        return {
            formUrl: '/tournaments',
            date: '',
            time: '',
            form: new SparkForm({
                name: '',
                game_type_id: '',
                tournament_type: '',
                password: '',
                password_confirmation: '',
                description: '',
                start_datetime: '',
                total_slots: '',
            }),
        }
    },
    mounted() {
        this.time = moment(this.tournament.start_datetime)
            .format('H:mm')
            .toString()
        this.date = moment(this.tournament.start_datetime)
            .format('YYYY-MM-DD')
            .toString()

        if (this.tournament.id) {
            this.formUrl = '/tournaments/' + this.tournament.id
            this.form.name = this.tournament.name
            this.form.game_type_id = this.tournament.game_type_id
            this.form.tournament_type = this.tournament.tournament_type
            this.form.description = this.tournament.description
            this.form.start_datetime = this.datetime
            this.form.total_slots = this.tournament.total_slots
        }
    },
    computed: {
        hasTournament() {
            return this.tournament ? true : false
        },
        showPasswords() {
            return this.form.tournament_type == 'private'
        },
        datetime() {
            let formattedDateTime = moment(this.date + ' ' + this.time)
                .format('YYYY-MM-DD H:mm:ss')
                .toString()
            this.form.start_datetime = formattedDateTime
            return formattedDateTime
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
