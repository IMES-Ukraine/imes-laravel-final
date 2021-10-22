<template>
    <v-content>
        <div class="main-content__container" style="height: calc(100vh - 100px); ">
            <!-- main 4 -->
            <div class="main-chat">
                <div class="chat__block" id="chatBlock">
                    <div class="chat__contacts" style="overflow: hidden;">
                        <ul class="chat-contacts__list js-scroller-chat-list _scrollbar" id="contactList"
                            style="overflow-y: scroll; box-sizing: border-box; margin: 0px; border: 0px none; width: 222px; min-width: 222px; max-width: 222px;"
                            data-baron-v-id="1">
                            <li v-for="item in list"
                                class="chat-contacts__item"
                                @click="chatWindow(item.id, item.id )">
                                {{ item.id }}
                            </li>

                        </ul>
                    </div>
                    <div class="chat__messages" id="chatWindow" style="overflow: hidden;">
                        <div class="chat__header">

                        </div>
                        <div class="chat__outer js-scroller-chat"
                             style="overflow-y: scroll; box-sizing: border-box; margin: 0px; border: 0px none; width: 503px; min-width: 503px; max-width: 503px;"
                             data-baron-v-id="0">
                            <div class="chat__body" id="chatBody">

                                <div v-for="item in chatData"
                                     class="chat__item">
                                    <div :class="  item.data.fromUser ? 'chat-message is-user' : 'chat-message is-my' ">
                                        {{ item.data.content }}
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="chat__bottom">
                            <div class="chat-sender">
                                <input type="text" name="chat-message is-user" class="chat-sender__input"
                                       id="messageInput" placeholder="Введіть ваше повідомлення"  v-model="newMessage">

                                <button id="sendButton" class="chat-sender__button"
                                        aria-label="відправити повідомлення" @click="sendMessage(currentChatId, newMessage)"></button>
                                <span id="current-user__id" data-request-data="id: 0"><button
                                    class="chat-notify__button" data-request="onSubmitSupportResponse"
                                    data-request-flash=""
                                    style="border:none; background-color:transparent; outline:none;">🔔</button></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </v-content>

</template>
<script>

import VContent from "./templates/Content";
import NotificationSidebar from "./templates/notification/sidebar";
import {database, store} from "../firebase/app";
import {limitToLast, query, ref, onValue, get} from "firebase/database";

import {collection, onSnapshot, getDoc, doc, getDocs} from "firebase/firestore";


export default {
    name: "Notification",
    components: {VContent, NotificationSidebar},
    data() {
        return {
            account: null,
            body: null,
            list: [],
            sessionRef: null,
            filterId: null,
            chatData: [],
            currentChatId: null,
            newMessage: ''
        }
    },
    mounted() {
        this.getList();
    },
    methods: {
        async getList() {
            const q = query(collection(store, "sessions"));
            const querySnapshot = await getDocs(q);

            querySnapshot.forEach((item) => {
                // doc.data() is never undefined for query doc snapshots
                this.list.push({id: item.id, data: item.data});
                //      console.log(item.id, " => ", item.data());
            });
        },
        async getData(id) {
            const docRef = doc(store, "sessions", id);
            const docSnap = await getDoc(docRef);

            if (docSnap.exists()) {
                console.log("Document data:", docSnap.data());
            } else {
                // doc.data() will be undefined in this case
                console.log("No such document!");
            }
        },
        async chatWindow(documentId, userId) {
            this.currentChatId = userId;
            this.chatData = [];
            $('#current-user__id').data('request-data', {"id": userId});

            $('.chat__header').html('Чат підтримки ' + userId);

            let collectionRef = collection(store, 'sessions');


            let q = query(collection(doc(store, "sessions", documentId), 'messages'));
            const querySnapshot = await getDocs(q);

            querySnapshot.forEach((item) => {
                this.chatData.push({id: item.id, data: item.data()});
                // console.log(item.id, " => ", item.data());
            });
            $(".chat__outer").scrollTop($(".chat__outer")[0].scrollHeight);

        },
        submitMessage(to, content) {

            this.sessionRef.doc(to).set({
                unreadCount: 0,
            }, {merge: true});

            this.sessionRef.doc(to).collection("messages").doc(String(Date.now())).set({
                content: content,
                fromUser: false,
                isRead: false,
                time: String(Date.now()),
            })
                .then(function () {
                    $('.chat-sender__input').val('');
                })
                .catch(function (error) {
                });

        },
    }
}
</script>

<style scoped>

</style>
