<template id="upcoming-tournaments">
    <b-container fluid>
        <!-- Main table element -->
        <b-table
            show-empty
            stacked="md"
            :items="items"
            :fields="fields"
            :current-page="currentPage"
            :per-page="perPage"
            :filter="filter"
            :sort-by.sync="sortBy"
            :sort-desc.sync="sortDesc"
            :sort-direction="sortDirection"
            @filtered="onFiltered"
        >
            <template slot="name" slot-scope="row">
                {{ row.value }}
            </template>

            <template slot="tournament" slot-scope="row">
                <a :href="'/tournaments/' + row.item.id">{{ row.value }}</a>
            </template>

            <template slot="isActive" slot-scope="row">
                {{ row.value ? 'Yes :)' : 'No :(' }}
            </template>

            <template slot="actions" slot-scope="row">
                <b-button size="sm" @click="info(row.item, row.index, $event.target)" class="mr-1">
                    Info modal
                </b-button>
                <b-button size="sm" @click="row.toggleDetails">
                    {{ row.detailsShowing ? 'Hide' : 'Show' }} Details
                </b-button>
            </template>

            <template slot="controls" slot-scope="row">
                <div v-for="(value, key) in row.item.controls" :key="key">
                    <div v-if="value == true && key == 'edit'">
                        <a :href="'/tournaments/' + row.item.id + '/edit'"
                            ><i class="fa fa-pencil-square-o" aria-hidden="true"></i
                        ></a>
                    </div>
                    <div v-if="value == true && key == 'cancel'">
                        <a :href="'/tournaments/' + row.item.id + '/delete'"
                            ><i class="fa fa-trash" aria-hidden="true"></i
                        ></a>
                    </div>
                </div>
            </template>

            <template slot="row-details" slot-scope="row">
                <b-card>
                    <ul>
                        <li v-for="(value, key) in row.item" :key="key">{{ key }}: {{ value }}</li>
                    </ul>
                </b-card>
            </template>
        </b-table>

        <b-row>
            <b-col md="6" class="my-1" v-if="showPagination">
                <b-pagination
                    v-model="currentPage"
                    :total-rows="totalRows"
                    :per-page="perPage"
                    class="my-0"
                ></b-pagination>
            </b-col>
        </b-row>

        <!-- Info modal -->
        <b-modal id="modal-info" @hide="resetModal" :title="modalInfo.title" ok-only>
            <pre>{{ modalInfo.content }}</pre>
        </b-modal>
    </b-container>
</template>

<script>
export default {
    name: 'UpcomingTournaments',
    props: ['user', 'tournaments'],
    data() {
        return {
            items: [],
            fields: [
                { key: 'tournament', label: 'Tournament', sortable: true, sortDirection: 'desc' },
                { key: 'game', label: 'Game', sortable: true, class: 'text-center' },
                { key: 'slots', label: 'Available Slots', sortable: true },
                { key: 'start_date', label: 'Start Date/Time', sortable: true },
                { key: 'total_pot', label: 'Total Pot', sortable: true },
                { key: 'winner', label: 'Winner', sortable: true },
                { key: 'admin', label: 'Admin', sortable: true },
                { key: 'controls', label: 'Actions', sortable: false },
            ],
            currentPage: 1,
            perPage: 10,
            pageOptions: [5, 10, 15],
            sortBy: null,
            sortDesc: false,
            sortDirection: 'asc',
            filter: null,
            modalInfo: { title: '', content: '' },
        }
    },
    mounted() {
        let formattedTournaments = []
        this.tournaments.forEach(tournament => {
            formattedTournaments.push(this.formatTournament(tournament))
        })

        this.items = formattedTournaments
    },
    computed: {
        totalRows() {
            return this.items.length
        },
        showPagination() {
            return this.totalRows > this.perPage
        },
        sortOptions() {
            // Create an options list from our fields
            return this.fields
                .filter(f => f.sortable)
                .map(f => {
                    return { text: f.label, value: f.key }
                })
        },
    },
    methods: {
        formatTournament(tournament) {
            const controls = this.controlActions(tournament)
            const {
                id,
                name,
                game_type,
                available_slots: slots,
                start_datetime,
                created_by_player,
                winner_gamer_tag: winner,
                total_pot,
            } = tournament

            const isActive = false
            const start_date = moment(start_datetime).format('MMMM Do YYYY, h:mm:ss a')
            const admin = created_by_player.gamer_tag
            const game = game_type.name
            return {
                isActive,
                id,
                tournament: name,
                game,
                slots,
                start_date,
                winner,
                admin,
                controls,
                total_pot,
            }
        },
        info(item, index, button) {
            this.modalInfo.title = `Row index: ${index}`
            this.modalInfo.content = JSON.stringify(item, null, 2)
            this.$root.$emit('bv::show::modal', 'modalInfo', button)
        },
        resetModal() {
            this.modalInfo.title = ''
            this.modalInfo.content = ''
        },
        onFiltered(filteredItems) {
            // Trigger pagination to update the number of buttons/pages due to filtering
            this.totalRows = filteredItems.length
            this.currentPage = 1
        },
        controlActions(tournament) {
            return {
                edit: tournament.can_edit,
                cancel: tournament.can_delete,
            }
        },
    },
}
</script>
