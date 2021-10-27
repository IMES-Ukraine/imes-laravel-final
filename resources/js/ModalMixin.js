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
            this.$store.state.modalData = data;
            this.$store.state.showUserModal = true;
        },
        closeModal() {
            this.$store.state.showUserModal = false;
        },
        async loadClients() {
            this.$get(CLIENTS).then( response => {
                this.$store.state.clients = response.data || {};
            });
        },
        async loadCards() {
            this.$get(CARDS).then( response => {
                this.$store.state.cards = response.page.data || {};
            });
        },
    }
}
