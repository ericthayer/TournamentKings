<template>
    <div></div>
</template>

<script>
import moment from 'moment'

export default {
    name: 'TournamentForm',
    props: ['user', 'tournament', 'errors'],

    data: () => ({
        menu1: false,
        menu2: false,
        modal2: false,
    }),
    mounted() {},
    computed: {
        datetime() {
            if (this.tournament.start_datetime) {
                this.$emit('datetime', this.tournament.start_datetime)
                return moment(this.tournament.start_datetime)
                    .format('YYYY-MM-DD H:mm:ss')
                    .toString()
            } else {
                this.$emit('datetime', this.date + ' ' + this.time)
                return moment(this.date + ' ' + this.time)
                    .format('YYYY-MM-DD H:mm:ss')
                    .toString()
            }
        },
        date() {
            if (this.tournament.start_datetime) {
                return moment(this.tournament.start_datetime)
                    .format('YYYY-MM-DD')
                    .toString()
            } else {
                return new Date().toISOString().substr(0, 10)
            }
        },
        time() {
            if (this.tournament.start_datetime) {
                return moment(this.tournament.start_datetime)
                    .format('H:mm')
                    .toString()
            } else {
                return moment(new Date().toISOString().substr(0, 10))
                    .format('H:mm')
                    .toString()
            }
        },
    },
    methods: {
        hasError(fieldName) {
            if (this.errors[fieldName]) {
                console.log(fieldName, ' has errors: ', this.errors[fieldName])
            }
        },
    },
}
</script>
