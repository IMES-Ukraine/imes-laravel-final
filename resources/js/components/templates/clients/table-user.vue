<template>
    <div>
        <table class="db__table">
            <!-- line header -->
            <tr class="db__row is-th">
                <th class="db__th">№ зп</th>
                <th class="db__th">№ аккаунта</th>
                <th class="db__th">ПІБ</th>
                <th class="db__th">Реєстраційні дані</th>
                <th class="db__th">Бали</th>
                <!--<th class="db__th">Веріфікований?</th>-->
                <th class="db__th">Управлiння</th>
            </tr>

            <tbody class="search-results">
            <!-- line header -->
            <!-- line -->

            <item
                v-if="requests.length"
                v-for="(request, index) in requests"
                v-bind:key="request.id"
                :index="index + 1"
                :request="request"
                v-on="$listeners"
            >{{ index }}
            </item>

            </tbody>

        </table>
        <!-- modal -->
        <modal-user></modal-user>
        <!-- modal -->

    </div>
</template>

<script>
import Item from './item';
import ModalUser from "../ModalUser";
import ModalMixin from "../../../ModalMixin";

export default {
    name: "table-user",
    components: {Item, ModalUser},
    mixins: [ModalMixin],

    computed: {
        requests() {
            return  this.$store.state.clients;
        },
        filteredId() {
            return this.$store.state.filterId;
        }
    },
    watch: {
        filteredId() {
            if (this.filteredId != '') {
                let user = this.requests.filter(item => item.id == this.filteredId);
                if (undefined !== user[0]) {
                    this.showModal(user[0])
                } else {
                    this.showMsgBox("Немає такого користувача: " + this.filteredId);
                }
            }
        }
    },
}
</script>

<style scoped>

</style>
