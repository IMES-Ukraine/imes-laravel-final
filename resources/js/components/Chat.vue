<template>
    <v-content>
        <div class="main-content__container">
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
                                       id="messageInput" placeholder="Введіть ваше повідомлення">
                                <button id="sendButton" class="chat-sender__button"
                                        aria-label="відправити повідомлення"></button>
                                <span id="current-user__id" data-request-data="id: 0"><button
                                    class="chat-notify__button" data-request="onSubmitSupportResponse"
                                    data-request-flash=""
                                    style="border:none; background-color:transparent; outline:none;">🔔</button></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <script id="template-message-item" type="text/template">
                <div class="chat__item">
                    <div class="chat-message ${class}">
                        ${content}
                    </div>
                </div>
            </script>
        </div>

    </v-content>

</template>
<style>
.chat__item {
    padding-left: 27px;
    padding-right: 25px;
    line-height: 1;
}

.chat-message {
    position: relative;
    display: inline-block;
    vertical-align: top;
    min-width: 175px;
    max-width: 50%;
    padding: 10px 20px;
    background-color: #E5E6EA;
    -webkit-border-radius: 18px;
    border-radius: 18px;
    color: #000;
    font-size: 12px;
    font-weight: 400;
    line-height: 1.25;
    margin: 5px 0;
}

.chat-message.is-my {
    float: right;
    background-color: #00B7FF;
    color: #fff;
}

</style>

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
            chatData: []
        }
    },
    mounted() {
        this.getList();
        console.log(this.list);

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

            $('#current-user__id').data('request-data', {"id": userId});

            //     this.unsubscribe();

            $('.chat__header').html('Чат підтримки ' + userId);

            let collectionRef = collection(store, 'sessions');


            let chatWindowHtml = '';
            let template = document.getElementById("template-message-item");
            let templateHtml = template.innerHTML;

            let q = query(collection(doc(store, "sessions", documentId), 'messages'));
            const querySnapshot = await getDocs(q);

            querySnapshot.forEach((item) => {
                this.chatData.push({id: item.id, data: item.data()});
                console.log(item.id, " => ", item.data());
            });


            /*

                    $('.chat-sender__button').data('to', documentId);


                    unsubscribe = collectionRef.doc(documentId).collection("messages").onSnapshot(snapshot => {
                        var received = new Promise((r, j) => {
                            snapshot.docChanges().forEach(function (change) {
                                if (change.type === "added") {

                                    chatWindowHtml = '';

                                    let messageClass = change.doc.data().fromUser ? 'is-user' : 'is-my';

                                    chatWindowHtml = templateHtml.replace(/\${content}/g, change.doc.data().content)
                                        .replace(/\${class}/g, messageClass);
                                    r();
                                }
                            });

                        });

                        received.then(() => {
                            $('#chatBody').append(chatWindowHtml);
                            $(".chat__outer").scrollTop($(".chat__outer")[0].scrollHeight);
                        });

                    });
            */
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
        chatList(id) {

            this.db.collection('sessions').onSnapshot(function (doc) {

                let innerHtml = '';

                doc.docs.forEach(function (val) {

                    let sessionId = val.id;

                    if (0 === this.filterId.val().length) {
                        if (val.data().unreadCount > 0) {

                            innerHtml = innerHtml + '<li data-message="' + val.data().unreadCount + '" class="chat-contacts__item is-message" onclick="chatWindow(\'' + val.id + '\', \'' + val.id + '\' );">' + val.id + '</li>';
                        } else {
                            innerHtml = innerHtml + '<li class="chat-contacts__item" onclick="chatWindow(\'' + val.id + '\', \'' + val.id + '\' );">' + val.id + '</li>';
                        }
                    } else {
                        if (id) if (id == sessionId) innerHtml = '<li class="chat-contacts__item" onclick="chatWindow(\'' + val.id + '\', \'' + val.id + '\' );">' + val.id + '</li>';
                    }
                    //console.log(this.filterId.val());


                });

                $('#contactList').html(innerHtml);

            });
        },
        unsubscribe() {

        }
    }
}
</script>

<style scoped>

</style>
