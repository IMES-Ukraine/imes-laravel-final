<template>
    <v-content>
        <template v-slot:sidebar>
            <div class="sidebar-content__block">
                <!-- активная в данный момент ссылка с классом is-active -->
                <button type="button" class="btn btn-outline-primary btn-block is-active">
                    Чати
                </button>
                <div class="input-group input-group mt-5 mb-4">
                    <input type="text" id="filterId" v-on:keyup.enter="findChat()" class="form-control input-is-small input-has-append"
                           placeholder="пошук по № аккаунта"
                           v-model="filterId"
                           aria-label="пошук по № аккаунта" >
                    <div class="input-group-append">
                        <button class="button-group-input" aria-label="знайти" @click="findChat()">
                            <span class="icon-is-search"></span>
                        </button>
                    </div>
                </div>
            </div>
        </template>


        <div class="main-content__container" style="height: calc(100vh - 133px); ">
            <!-- main 4 -->
            <div class="main-chat">
                <div class="chat__block" id="chatBlock">
                    <div class="chat__contacts" style="overflow: hidden;">
                        <ul class="chat-contacts__list js-scroller-chat-list _scrollbar" id="contactList" >

                            <li v-for="item in list"
                                :class="{unread: item.unread, current: (item.id == currentChatId), 'chat-contacts__item': true}"
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
    .main-content__container {
        height: calc(100vh - 133px);
    }

    .chat__block {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: stretch;
        -ms-flex-align: stretch;
        align-items: stretch;
        -webkit-box-pack: start;
        -ms-flex-pack: start;
        justify-content: flex-start;
    }

    .chat__contacts {
        -ms-flex-negative: 0;
        flex-shrink: 0;
        width: 204px;
    }

    .chat-contacts__item {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        -webkit-box-pack: start;
        -ms-flex-pack: start;
        justify-content: flex-start;
        min-height: 42px;
        font-weight: 500;
        font-size: 12px;
        line-height: 1.25;
        letter-spacing: -0.0017em;
        color: #4F4F4F;
        border-bottom: 1px solid #EDEDED;
        padding: 10px 21px;
        padding-right: 45px;
    }

    .chat-contacts__item:last-child {
        border-bottom: none;
    }

    .chat-contacts__item.current {
        border-color: #EDEDED;
        border-width: 1px;
        background: #D5F8E0;
    }

    .chat-contacts__item.unread {
        background: transparent;
    }

    .chat__messages {
        width: 100%;
    }

    .chat__header {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        -webkit-box-pack: end;
        -ms-flex-pack: end;
        justify-content: flex-end;
        font-weight: normal;
        font-size: 9px;
        line-height: 1.22;
        letter-spacing: -0.0017em;
        text-transform: lowercase;
        padding: 0 13px;
    }

    .chat__outer {
        width: 100%;
        padding: 30px;
    }

    .chat__body {
        width: 100%;
        min-height: 100%;
        padding: 0;
    }

    .chat__item {
        padding: 0;
        margin-bottom: 10px;
    }

    .chat__item:last-child {
        margin-bottom: 0;
    }

    .chat-message {
        margin: 0;
    }

    .chat__bottom {
        padding: 0 26px;
        padding-bottom: 18px;
    }

    .chat-sender__input {
        font-size: 14px;
        line-height: 22px;
        letter-spacing: -0.41px;
        color: #000;
        padding-left: 15px;
    }

    .chat-sender__input::-webkit-input-placeholder {
        color: #A1A1A1;
    }

    .chat-sender__input::-moz-placeholder {
        color: #A1A1A1;
    }

    .chat-sender__input:-ms-input-placeholder {
        color: #A1A1A1;
    }

    .chat-sender__input::-ms-input-placeholder {
        color: #A1A1A1;
    }

    .chat-sender__input::placeholder {
        color: #A1A1A1;
    }

    .chat-sender__button {
        -ms-flex-negative: 0;
        flex-shrink: 0;
    }

    #current-user__id {
        display: none;
    }

    .unread_count {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        -webkit-box-pack: center;
        -ms-flex-pack: center;
        justify-content: center;
        width: 15px;
        height: 15px;
        font-weight: bold;
        font-size: 8px;
        line-height: 1;
        letter-spacing: -0.0017em;
        color: #fff;
        float: right;
        text-align: center;
        border-radius: 50%;
        background: #10DE50;
        position: absolute;
        top: 50%;
        right: 9px;
        -webkit-transform: translate(0, -50%);
        -ms-transform: translate(0, -50%);
        transform: translate(0, -50%);
    }
</style>

<script>


import VContent from "./templates/Content";
import NotificationSidebar from "./templates/notification/sidebar";
import {store} from "../firebase/app";

import {collection, setDoc, getDoc, doc, onSnapshot} from "firebase/firestore";
import {NOTIFICATION_TO} from "../api/endpoints";


export default {
    name: "Chat",
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
            onSnapshot(collection(store, "sessions"), (chatList) => {
                this.list = [];
                chatList.forEach((item) => {
                    this.list.push({id: item.id, unread: item.data().unreadCount});
                    //   console.log(item.id, " => ", item.data().unreadCount);
                });
            })
        },
        async getData(id) {
            this.currentChat = {};
            const docRef = doc(store, "sessions", id);
            const docSnap = await getDoc(docRef);

            if (docSnap.exists()) {
                this.currentChat = docSnap.data();
            } else {
                // doc.data() will be undefined in this case
                console.log("No such document!");
            }
            return this.currentChat;
        },
        async chatWindow(documentId) {
            this.currentChatId = documentId;
            await this.getData(documentId);  //получаем текущий объект чата
            this.chatData = [];

            // Формируем слущателя для записей указанного чата
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

        findChat() {
            this.chatWindow(this.filterId);
        },

        sendMessage(chatId) {
            let time = String(Date.now());
            let messData = {
                content: this.newMessage,
                fromUser: false,
                isRead: false,
                time: time,
            };
            setDoc(doc(doc(store, "sessions", chatId), 'messages', time ), messData).then( () => {
                this.newMessage = '';
                axios.post(NOTIFICATION_TO, {
                    to: chatId,
                    body: 'Вы получили сообщение от службы техподдержки. Зайдите в чат приложения',
                    title: 'Сообщение в чате'
                });
                // this.chatWindow(this.currentChatId)
            });
        },
        // submitMessage(to, content) {
        //
        //     this.sessionRef.doc(to).set({
        //         unreadCount: 0,
        //     }, {merge: true});
        //
        //     this.sessionRef.doc(to).collection("messages").doc(String(Date.now())).set({
        //         content: content,
        //         fromUser: false,
        //         isRead: false,
        //         time: String(Date.now()),
        //     })
        //         .then(function () {
        //             $('.chat-sender__input').val('');
        //         })
        //         .catch(function (error) {
        //         });
        //
        // },
    }
}
</script>

<style scoped>

</style>
