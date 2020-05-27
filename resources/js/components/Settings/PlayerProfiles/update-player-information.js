Vue.component('update-player-information', {
    props: ['user'],
    data() {
        return {
            form: new SparkForm({
                id: '',
                gamer_tag: '',
                platform__type_id: '',
            }),
        }
    },
    computed: {},
    mounted() {
        this.form.gamer_tag = this.user.player.gamer_tag
        this.form.platform_type_id = this.user.player.platform_type_id
    },
    methods: {
        update() {
            Spark.put(this.formUrl, this.form).then(response => {
                window.location.href = response
            })
        },
    },
})
