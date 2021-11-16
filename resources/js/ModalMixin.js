import {CARDS, CLIENTS} from "./api/endpoints";

export default {
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
        async loadClients() {
            this.$get(CLIENTS).then( response => {
                this.$store.dispatch('setClients',  response.data || {} );
                this.$store.dispatch('setResponseData',  response || {} );

            });
        },
        async loadCards() {
            this.$get(CARDS).then( response => {
                this.$store.dispatch('setCards',  response.data || {} );
                this.$store.dispatch('setResponseData',  response || {} );
            });
        },
    }
}
