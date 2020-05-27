module.exports = {
    props: ['user'],

    /**
     * The component's data.
     */
    data() {
        return {
            platform_types: [],
            form: new SparkForm({
                platform_type_id: '',
            }),
        }
    },

    /**
     * Bootstrap the component.
     */
    mounted() {
        this.getPlatformTypes()
        this.form.platform_type_id = this.user.player.platform_type.id
    },
    computed: {},
    methods: {
        /**
         * Update the user's contact information.
         */
        update() {
            Spark.put('/settings/player', this.form).then(() => {
                Bus.$emit('updatePlayer')
            })
        },
        getPlatformTypes() {
            axios.get('/api/settings/platform_types').then(function(response) {
                this.platform_types = response.data
                console.log(this.platform_types)
            })
        },
    },
}
