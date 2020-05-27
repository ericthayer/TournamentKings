module.exports = {
    props: ['user'],

    /**
     * The component's data.
     */
    data() {
        return {
            form: new SparkForm({
                id: '',
                gamer_tag: '',
            }),
        }
    },

    /**
     * Bootstrap the component.
     */
    mounted() {
        this.form.gamer_tag = this.user.player.gamer_tag
        this.form.id = this.user.player.id
    },

    methods: {
        /**
         * Update the user's contact information.
         */
        update() {
            Spark.put('/api/settings/player/' + this.form.id, this.form).then(() => {
                Bus.$emit('updatePlayer')
            })
        },
    },
}
