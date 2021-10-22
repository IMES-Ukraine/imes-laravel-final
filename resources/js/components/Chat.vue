<template>
    <v-content>
        <div class="main-content__container" style="height: calc(100vh - 100px); ">
            <!-- main 4 -->
            <div class="main-chat">
                <div class="chat__block" id="chatBlock">
                    <div class="chat__contacts" style="overflow: hidden;">
                        <ul class="chat-contacts__list js-scroller-chat-list _scrollbar" id="contactList"
                            style="overflow-y: scroll; box-sizing: border-box; margin: 0px; border: 0px none; width: 222px; min-width: 222px; max-width: 222px;" >

                            <li v-for="item in list"
                                :class="item.unread ? 'chat-contacts__item unread' : 'chat-contacts__item' "
                                :key="item.id"
                                @click="chatWindow(item.id, item.id )">
                                {{ item.id }}
                                <div v-if="item.unread > 0" class="unread_count">{{ item.unread }}</div>
                            </li>

                        </ul>
                    </div>
                    <div class="chat__messages" id="chatWindow" style="overflow: hidden;">
                        <div class="chat__header">
                            Чат підримки користувача {{ currentChatId }}
                        </div>
                        <div class="chat__outer js-scroller-chat"
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
                                       id="messageInput" placeholder="Введіть ваше повідомлення" v-model="newMessage">

                                <button id="sendButton" class="chat-sender__button"
                                        aria-label="відправити повідомлення"
                                        @click="sendMessage(currentChatId, newMessage)">

                                </button>
                                <span id="current-user__id">
                                    <button
                                        class="chat-notify__button" data-request="onSubmitSupportResponse"
                                        data-request-flash=""
                                        style="border:none; background-color:transparent; outline:none;">🔔</button>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </v-content>

</template>
<style>
.unread {
    background-color: lightpink;
    position: relative;
}

.unread_count {
    font-weight: bold;
    float: right;
}
.chat__header {
    font-size: 15px;
    font-weight: bold;
}

</style>

<script>

import VContent from "./templates/Content";
import NotificationSidebar from "./templates/notification/sidebar";
import {database, store} from "../firebase/app";
import {update, set, query, ref, onValue, get} from "firebase/database";

import {collection, setDoc, addDoc, getDoc, doc, getDocs, onSnapshot} from "firebase/firestore";


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
            currentChat: {},
            newMessage: ''
        }
    },
    mounted() {
        this.getList();
    },
    methods: {
        async getList() {
            this.list = [];
            const q = query(collection(store, "sessions"));
            const querySnapshot = await getDocs(q);

            querySnapshot.forEach((item) => {
                // doc.data() is never undefined for query doc snapshots
                this.list.push( {id: item.id, unread: item.data().unreadCount} );
             //   console.log(item.id, " => ", item.data().unreadCount);
            });
        },
        async getData(id) {
            this.currentChat = {};
            const docRef = doc(store, "sessions", id);
            const docSnap = await getDoc(docRef);

            if (docSnap.exists()) {
                this.currentChat = docSnap.data();
                console.log("Document data:", this.currentChat);
            } else {
                // doc.data() will be undefined in this case
                console.log("No such document!");
            }
            return this.currentChat;
        },
        async chatWindow(documentId) {
            this.currentChatId = documentId;
            await this.getData(documentId);
            this.chatData = [];

            // Формируем запрос на все записи указанного чата
            onSnapshot(collection(doc(store, "sessions", documentId), 'messages'), (chatDoc) => {
                this.chatData = [];
                // Загружаем записи
                chatDoc.forEach((item) => {
                    this.chatData.push({id: item.id, data: item.data()});
                });
                $(".chat__outer").scrollTop($(".chat__outer")[0].scrollHeight);
            });


// Сбрасываем счётчик просмотров
            if (this.currentChat.unreadCount) {
                await setDoc(doc(store, 'sessions', documentId), {
                    unreadCount: 0,
                }).then(() => {
                    this.getList();
                });
            }
        },

        sendMessage(chatId) {
            console.log(chatId, this.newMessage);
            let time = String(Date.now());
            let messData = {
                content: this.newMessage,
                fromUser: false,
                isRead: false,
                time: time,
            };
            setDoc(doc(doc(store, "sessions", chatId), 'messages', time ), messData).then( () => {
                this.newMessage = '';
                // this.chatWindow(this.currentChatId)
            });
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
