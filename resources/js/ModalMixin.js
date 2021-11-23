import {CARDS, CLIENTS} from "./api/endpoints";

export default {
    data() {
        return {
            responseData: {}
        }
    },
    methods: {
        showMsgBox(text) {
            this.boxOne = ''
            this.$bvModal.msgBoxOk(text)
                .then(value => {
                    this.boxOne = value
                })
                .catch(err => {
                    // An error occurred
                })
        },
        showModal(data) {
            this.$store.dispatch('setModalData', data);
            this.$store.dispatch('setShowUserModal', true);
        },
        closeModal() {
            this.$store.dispatch('setFilter',  '');
            this.$store.dispatch('setShowUserModal', false);
        },
        loadClients(page) {
            if (typeof page === 'undefined') {
                page = 1;
            }
            this.$get(CLIENTS, {page: page}).then( response => {
                this.$store.commit('setClients',  response.data.data || {} );
                this.$store.commit('setResponseData',  response || {} );
                this.responseData = response.data;
            });
        },
        loadCards(page) {
            if (typeof page === 'undefined') {
                page = 1;
            }
            this.$get(CARDS, {page: page}).then( response => {
                this.$store.commit('setCards',  response.data.data || {} );
                this.$store.commit('setResponseData',  response || {} );
                this.responseData = response.data;
            });
        },
    }
}
