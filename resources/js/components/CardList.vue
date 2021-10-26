<template>
    <v-content>
        <template v-slot:sidebar>
            <SidebarUsers></SidebarUsers>
        </template>

        <div class="main-db">
            <div class="db__block">
                <div class="db-edit card">
                    <div class="card-body">
                        <cards-table
                            v-on:onDisableCard="onDisableCard"
                            v-on:onEnableCard="onEnableCard"
                            v-on:onDeleteCard="onDeleteCard"
                        ></cards-table>
                    </div>
                </div>
            </div>
        </div>
    </v-content>
</template>

<script>
import VContent from "./templates/Content";
import SidebarUsers from "./templates/SidebarUsers";
import CardsTable from './templates/cards/table-card'
import { CARDS, CARD_ENABLE, CARD_DISABLE} from "../api/endpoints"
import VPreloader from "./fragmets/preloader"
import ModalMixin from "../ModalMixin";

export default {
    name: "Cards",
    components: {
        VContent,SidebarUsers,CardsTable,VPreloader
    },
    mixins: [ModalMixin],
    computed: {
        requests() {
         return this.$store.state.cards;
        }
    },
    methods: {

        hasRequests() {
            return !!Object.keys(this.requests).length
        },
        async onDisableCard(id) {

            this.$get(CARD_DISABLE + '/' + id).then()

            for (const [index, value] of Object.entries(this.requests)) {
                if (value.id === id) {
                    value.is_activated = 0
                    return
                }
            }
        },
        async onEnableCard(id) {

            this.$get(CARD_ENABLE + '/' + id).then()

            for (const [index, value] of Object.entries(this.requests)) {
                if (value.id === id) {
                    value.is_activated = 1
                    return
                }
            }
        },
        async onDeleteCard(id) {

            this.$delete(CARDS + '/' + id).then()
            $('#db-remove--' + id + ' .is-close').click();

            for (const [index, value] of Object.entries(this.requests)) {
                if (value.id === id) {
                    this.requests.splice(index, 1)
                    return
                }
            }
        }
    },
    mounted() {
        this.loadCards();
    }

}
</script>
