<template id="upcoming-matches">
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
                <a :href="'/tournaments/' + row.item.tournament_id">{{ row.value }}</a>
            </template>

            <template slot="isActive" slot-scope="row">
                {{ row.value ? 'Yes :)' : 'No :(' }}
            </template>

            <template slot="actions" slot-scope="row">
                <b-button v-if="row.item.actions.post" size="sm" @click="postResults(row.item)" class="mr-1">
                    Post Results
                </b-button>
                <b-button v-if="row.item.actions.edit" size="sm" @click="editResults(row.item)" class="mr-1">
                    Edit Results
                </b-button>
                <b-button v-if="row.item.actions.confirm" size="sm" @click="confirmResults(row.item)" class="mr-1">
                    Confirm Results
                </b-button>
                <b-button v-if="row.item.actions.delete" size="sm" @click="deleteResults(row.item)" class="mr-1">
                    Delete Results
                </b-button>
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
    name: 'UpcomingMatches',
    props: ['user', 'matches'],
    data() {
        return {
            items: [],
            fields: [
                { key: 'name', label: 'Tournament', sortable: true },
                { key: 'round', label: 'Round', sortable: true, class: 'text-center' },
                { key: 'match', label: 'Match', sortable: true, class: 'text-center' },
                { key: 'vs', label: 'VS', sortable: true },
                { key: 'start', label: 'Start Time', sortable: true, sortDirection: 'desc' },
                { key: 'winner', label: 'Winner', sortable: true },
                { key: 'actions', label: 'Actions', sortable: true },
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
    mounted() {
        let formattedMatches = []
        this.matches.forEach(match => {
            formattedMatches.push(this.formatMatch(match))
        })

        this.items = formattedMatches
    },
    methods: {
        formatMatch(match) {
            let gamerTag = this.user.player.gamer_tag
            let opponent = 'N/A'
            let winner = match.areResultsPosted ? this.determineWinner(match) : 'No Results'
            let startTime = moment(match.tournament.start_datetime).format('MMMM Do YYYY, h:mm:ss a')

            let controls = {
                edit: match.canEditResults,
                post: match.canPostResults,
                confirm: match.canConfirmResults,
                delete: match.canDeleteResults,
                match_id: match.id,
            }

            if (match.player_one != null && match.player_one.gamer_tag !== gamerTag) {
                opponent = match.player_one.gamer_tag
            } else {
                if (match.player_two != null && match.player_two.gamer_tag !== gamerTag) {
                    opponent = match.player_two.gamer_tag
                }
            }

            return {
                isActive: false,
                tournament_id: match.tournament.id,
                name: match.tournament.name,
                round: match.round,
                match: match.number,
                vs: opponent,
                start: startTime,
                winner: winner,
                actions: controls,
            }
        },
        determineWinner(match) {
            let winner = 'Loser'

            if (match.winner_player_id != null) {
                if (match.winner_player_id === this.user.player.id) {
                    winner = 'Winner'
                }

                return winner
            }
        },
        clickOnMatch(match) {
            this.$emit('click-on-match', match)
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
        postResults(item) {
            window.location = '/matches/' + item.actions.match_id + '/create'
        },
        editResults(item) {
            window.location = '/matches/' + item.actions.match_id + '/edit'
        },
        confirmResults(item) {
            window.location = '/matches/' + item.actions.match_id + '/confirmresults'
        },
        deleteResults(item) {
            window.location = '/matches/' + item.actions.match_id + '/delete'
        },
    },
}
</script>
