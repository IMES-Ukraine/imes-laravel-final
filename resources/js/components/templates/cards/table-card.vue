<template>
    <div>
        <button @click="addCard()">Додати карту</button>
        <table class="db__table">
            <!-- line header -->
            <tr class="db__row is-th">
                <th class="db__th">№ зп</th>
                <th class="db__th">Код</th>
                <th class="db__th">Назва</th>
                <th class="db__th">Вартість</th>
                <th class="db__th">Опис</th>
                <th class="db__th">Переглянути</th>
                <th class="db__th">Управлiння</th>
            </tr>

            <tbody class="search-results">
            <!-- line header -->
            <!-- line -->

            <item
                v-if="requests.length"
                v-for="(request, index) in requests"
                v-bind:key="request.id"
                :index="index"
                :request="request"
                v-on="$listeners"
            >{{ index }}
            </item>

            </tbody>

        </table>
        <!-- modal -->
        <modal-card></modal-card>
        <!-- modal -->

    </div>
</template>

<script>
import Item from './item';
import ModalCard from "../ModalCard";
import ModalMixin from "../../../ModalMixin";

export default {
    name: "table-card",
    components: {Item, ModalCard},
    mixins: [ModalMixin],
    data() {
        return {
            modalData: {}
        }
    },
    computed: {
        requests() {
            return  this.$store.state.cards;
        },
        currentRequest() {
            return this.$store.state.modalData;
        },
        filteredId() {
            return this.$store.state.filterId;
        }
    },
    watch: {
        filteredId() {
            if (this.filteredId != '') {
                let card = this.requests.filter(item => item.id == this.filteredId);
                if (undefined !== card[0]) {
                    this.showModal(card[0])
                } else {
                    this.showMsgBox("Немає такої карти: " + this.filteredId);
                }
            }
        }
    },
    methods: {
        addCard() {
            this.showModal({})
        }


    }
}
</script>

<style scoped>

</style>
