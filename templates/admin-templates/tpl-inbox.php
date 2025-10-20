<?php include_once(__DIR__ . '/../inc_head.php') ?>

<style>
    #chatBox {
        display: flex;
        flex-direction: column-reverse;
        height: 300px;
        overflow-y: auto;
    }

    .chat-app .people-list {
        width: 280px;
        position: absolute;
        left: 0;
        top: 0;
        padding: 20px;
        z-index: 7
    }

    .chat-app .chat {
        margin-left: 280px;
        border-left: 1px solid #eaeaea
    }

    a {
        cursor: pointer;
    }

    .people-list {
        -moz-transition: .5s;
        -o-transition: .5s;
        -webkit-transition: .5s;
        transition: .5s
    }

    .people-list .chat-list li {
        padding: 10px 15px;
        list-style: none;
        border-radius: 3px
    }

    .people-list .chat-list li:hover {
        background: #efefef;
        cursor: pointer
    }

    .people-list .chat-list li.active {
        background: #efefef
    }

    .people-list .chat-list li .name {
        font-size: 15px
    }

    .people-list .chat-list img {
        width: 45px;
        border-radius: 50%
    }

    .people-list img {
        float: left;
        border-radius: 50%
    }

    .people-list .about {
        float: left;
        padding-left: 8px
    }

    .people-list .status {
        color: #999;
        font-size: 13px
    }

    .modal-header .btn-close {
        margin-bottom: 30px;
    }

    .chat .chat-header img {
        float: left;
        border-radius: 40px;
        width: 40px
    }

    .chat .chat-header .chat-about {
        float: left;
        padding-left: 10px
    }

    .chat .chat-history {
        padding: 20px;
        border-bottom: 2px solid #fff
    }

    .chat .chat-history ul {
        padding: 0
    }

    .chat .chat-history ul li {
        list-style: none;
        margin-bottom: 30px
    }

    .chat .chat-history ul li:last-child {
        margin-bottom: 0px
    }

    .chat .chat-history .message-data {
        margin-bottom: 15px
    }

    .chat .chat-history .message-data img {
        border-radius: 40px;
        width: 40px
    }

    .chat .chat-history .message-data-time {
        color: #434651;
        padding-left: 6px
    }

    .chat .chat-history .message {
        color: #444;
        padding: 18px 20px;
        line-height: 26px;
        font-size: 16px;
        border-radius: 7px;
        display: inline-block;
        position: relative
    }

    .chat .chat-history .message:after {
        bottom: 100%;
        left: 7%;
        border: solid transparent;
        content: " ";
        height: 0;
        width: 0;
        position: absolute;
        pointer-events: none;
        border-bottom-color: #fff;
        border-width: 10px;
        margin-left: -20px
    }

    .chat .chat-history .my-message {
        background: #efefef
    }

    .chat .chat-history .my-message:after {
        bottom: 100%;
        left: 30px;
        border: solid transparent;
        content: " ";
        height: 0;
        width: 0;
        position: absolute;
        pointer-events: none;
        border-bottom-color: #efefef;
        border-width: 10px;
        margin-left: -10px
    }

    .chat .chat-history .other-message {
        background: #e8f1f3;
        text-align: right
    }

    .chat .chat-history .other-message:after {
        border-bottom-color: #e8f1f3;
        left: 93%
    }

    .chat .chat-message {
        padding: 10px;
        width: 100%;
    }

    .online,
    .offline,
    .me {
        margin-right: 2px;
        font-size: 8px;
        vertical-align: middle
    }

    .online {
        color: #86c541
    }

    .offline {
        color: #e47297
    }

    .me {
        color: #1d8ecd
    }

    .float-right {
        float: right
    }

    .clearfix:after {
        visibility: hidden;
        display: block;
        font-size: 0;
        content: " ";
        clear: both;
        height: 0
    }

    .input-group-text {
        height: 38px;
    }

    @media only screen and (max-width: 767px) {
        .chat-app .people-list {
            height: 465px;
            width: 100%;
            overflow-x: auto;
            background: #fff;
            left: -400px;
            display: none
        }

        .chat-app .people-list.open {
            left: 0
        }

        .chat-app .chat {
            margin: 0
        }

        .chat-app .chat .chat-header {
            border-radius: 0.55rem 0.55rem 0 0
        }

        .chat-app .chat-history {
            height: 300px;
            overflow-x: auto
        }
    }

    @media only screen and (min-width: 768px) and (max-width: 992px) {
        .chat-app .chat-list {
            height: 650px;
            overflow-x: auto
        }

        .chat-app .chat-history {
            height: 600px;
            overflow-x: auto
        }
    }

    @media only screen and (min-device-width: 768px) and (max-device-width: 1024px) and (orientation: landscape) and (-webkit-min-device-pixel-ratio: 1) {
        .chat-app .chat-list {
            height: 480px;
            overflow-x: auto
        }

        .chat-app .chat-history {
            height: calc(100vh - 350px);
            overflow-x: auto
        }
    }

    #chats img {
        width: 40px;
    }

    .username {
        width: 40px;
        height: 40px;
    }
</style>
</head>

<body>
    <main class="page-wrapper">
        <?php include_once(__DIR__ . '/../inc_header.php') ?>
        <section class="my-5">
            <div class="container">
                <div class="row vh-100 gap-lg-0 gap-4">
                    <div class="col-lg-4 bg-light">
                        <h1>Chats</h1>
                        <div class="form-group my-3">
                            <input type="text" name="search" id="search" placeholder="search" class="form-control">
                        </div>
                        <hr>
                        <div class="list-group" id="chats">
                            <div class="accordion" id="accordionExample">
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#users-collapse"
                                            aria-expanded="true" aria-controls="users-collapse">
                                            Users
                                        </button>
                                    </h2>
                                    <div id="users-collapse" class="accordion-collapse collapse vh-50 overflow-auto"
                                        data-bs-parent="#accordionExample">
                                        <?php
                                        if ($chat_users) {
                                            foreach ($chat_users as $chatsuser) { ?>

                                                <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-start chat-user"
                                                    data-from-id="<?= $chatsuser->user_id ?>">
                                                    <div class="d-flex align-items-center">
                                                        <?= $chatsuser->profile_image != '' ?
                                                            '<img src="' . $chatsuser->profile_image . '" alt="Avatar" class="rounded-circle avator-img">' :
                                                            '<div class="border rounded-circle username d-flex align-items-center justify-content-center">' . strtoupper(substr($chatsuser->full_name, 0, 2)) . '</div>'
                                                            ?>


                                                        <div class="ms-3">
                                                            <h6 class="mb-0 avatar-name"><?= $chatsuser->full_name ?></h6>
                                                        </div>
                                                    </div>
                                                </div>

                                            <?php }
                                        } else {
                                            echo '<p class="m-3 p-3">No user is here for a chat send request to start chatting</p>';
                                        } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="card vh-100 overflow-hidden d-none" id="chat-room">

                            <div class="card-header chat">
                                <div class="chat-header clearfix">
                                    <div class="row">
                                        <div class="col d-flex align-items-center">
                                            <div class="">
                                                '<img src="" alt="Avatar" class="rounded-circle" id="avatar-image">
                                            </div>
                                            <div class="chat-about">
                                                <h6 class="m-b-0" id="avatar-name"></h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body" id="chatBox">
                                <div class="row clearfix">
                                    <div class="col-lg-12">
                                        <div class="chat">
                                            <div class="chat-history">
                                                <ul class="m-b-0" id="messageList">

                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer chat">
                                <div class="chat-message clearfix">
                                    <form id="send-message" novalidate>
                                        <div class="form-group mb-0">
                                            <textarea name="message" id="message" class="form-control" required
                                                maxlength="200"></textarea>
                                        </div>
                                        <div class="mt-2 d-flex justify-content-end">
                                            <input type="hidden" name="to_user" id="to_user" value="">
                                            <button type="submit" class="btn btn-primary btn-sm">Send</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-center" id="chat-message">
                            <p>Welcome to chat ! select user to continue chat</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <?php include_once(__DIR__ . '/../inc_footer.php') ?>
    <script src="<?= $baseurl ?>/templates/js/validate.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {

            var csrfToken;

            async function fetchCsrfToken() {

                const url = '<?= $baseurl ?>/user/control/_generate_token.php';
                try {

                    const response = await fetch(url);

                    if (!response.ok) {
                        throw new Error(`Response Error:${response.status}`);
                    }

                    const json = await response.json();

                    csrfToken = json.token;

                } catch (error) {
                    console.error(error);
                }

            }

            fetchCsrfToken();


            const chatBox = document.getElementById('chatBox');
            chatBox.scrollTop = chatBox.scrollHeight;

            const form = document.getElementById('send-message');
            if (form) {
                form.addEventListener('submit', (event) => {
                    event.preventDefault();
                    if (!validateForm(form)) {
                        return;
                    } else {
                        var formData = new FormData(form);
                        formData.append('token', csrfToken);

                        const url = '<?= $baseurl ?>/user/control/send_message.php';
                        fetch(url, {
                            method: 'POST',
                            body: formData
                        })
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error('Error: Could not send message.');
                                }

                                return response.json();
                            })
                            .then(responseObject => {

                                if (responseObject.status) {
                                    fetchCsrfToken();
                                    const message_value = document.getElementById('message');
                                    // set last message 
                                    const to_user = document.getElementById('to_user').value;
                                    const chatUserElement = document.querySelector(`.chat-user[data-from-id="${to_user}"]`);
                                    // chatUserElement.querySelector('.last_message').textContent = message_value.value;

                                    message_value.value = '';

                                    // append message in chatbox
                                    appendMessage(responseObject.message);
                                } else {
                                    console.log('Error: ' + responseObject.message);
                                }
                            })
                            .catch(error => {
                                alert(error.message);
                            });
                    }
                });

                const inputs = form.querySelectorAll('input, textarea, select, file');
                inputs.forEach(input => {
                    input.addEventListener('blur', () => {
                        validateInput(input);
                    });
                });
            }

            function appendMessage(message) {
                const messageList = document.getElementById('messageList');
                const messageTime = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }) + ', Today';

                const messageHTML = `<li class="clearfix">
                                                <div class="message-data">
                                                    <span class="message-data-time">${messageTime}</span>
                                                </div>
                                                <div class="message my-message">${message}</div>
                                            </li>
                                        `;

                messageList.insertAdjacentHTML('beforeend', messageHTML);
                const chatBox = document.getElementById('chatBox');
                chatBox.scrollTop = chatBox.scrollHeight;
            }

            // get chat room for selected user

            const chatUserElements = document.querySelectorAll('.chat-user');

            chatUserElements.forEach(function (chatUserElement) {

                chatUserElement.addEventListener('click', function (e) {


                    const from_user_id = chatUserElement.getAttribute('data-from-id');

                    const avatar_image = chatUserElement.querySelector('.avator-img')
                        ? chatUserElement.querySelector('.avator-img').src
                        : 'https://bootdey.com/img/Content/avatar/avatar1.png';

                    const username = chatUserElement.querySelector('.avatar-name')
                        ? chatUserElement.querySelector('.avatar-name').innerText
                        : '';


                    chatUserElements.forEach(function (element) {
                        element.classList.remove('bg-light');
                    });

                    e.target.classList.add("bg-light");

                    var formData = new FormData(form);
                    formData.append('token', csrfToken);
                    formData.append('receiver_id', from_user_id);

                    const url = '<?= $baseurl ?>/user/control/get_chatroom.php';
                    fetch(url, {
                        method: 'POST',
                        body: formData
                    })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Error: Could not send message.');
                            }

                            return response.json();
                        })
                        .then(responseObject => {

                            if (responseObject.status) {
                                fetchCsrfToken();

                                document.getElementById('chat-room').classList.remove('d-none');
                                document.getElementById('chat-message').classList.add('d-none');
                                document.getElementById('to_user').value = from_user_id;


                                document.getElementById('avatar-image').src = avatar_image;
                                document.getElementById('avatar-name').textContent = username;
                                document.getElementById('messageList').innerHTML = '';

                                document.getElementById('unreadMessagesBadge').textContent = responseObject.unread_messages;

                                responseObject.message.forEach(message => {
                                    addMessageToList(message, avatar_image);
                                });
                            } else {
                                console.log('Error: ' + responseObject.message);
                            }
                        })
                        .catch(error => {
                            console.log(error.message);
                        });
                });
            });

            function formatDateTime(dateTime) {
                const createdDate = new Date(dateTime);
                const now = new Date();
                const formattedTime = createdDate.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', hour12: true });
                const formattedDate = createdDate.toLocaleDateString() === now.toLocaleDateString() ? 'today' : createdDate.toLocaleDateString('en-GB');
                return `${formattedTime}, ${formattedDate}`;
            }

            function addMessageToList(message, avatar_image) {

                const loggeduser = '<?= $user->get_loggeduser()->id ?>';
                const messageList = document.getElementById('messageList');
                const listItem = document.createElement('li');
                listItem.className = 'clearfix';
                // listItem.id = `text_${message.id}`;

                const messageDataDiv = document.createElement('div');
                messageDataDiv.className = `message-data ${message.sender_id != loggeduser ? 'd-flex justify-content-end align-items-center gap-3' : ''}`;

                const messageDataTimeSpan = document.createElement('span');
                messageDataTimeSpan.className = 'message-data-time';
                messageDataTimeSpan.textContent = formatDateTime(message.created_at);
                messageDataDiv.appendChild(messageDataTimeSpan);
                if (message.sender_id != loggeduser) {
                    const avatarImg = document.createElement('img');
                    avatarImg.src = avatar_image;
                    avatarImg.alt = 'avatar';
                    messageDataDiv.appendChild(avatarImg);
                }

                listItem.appendChild(messageDataDiv);

                const messageDiv = document.createElement('div');
                messageDiv.className = `message ${message.sender_id != loggeduser ? 'other-message float-right' : 'message my-message'}`;
                messageDiv.textContent = message.message_body;

                listItem.appendChild(messageDiv);
                messageList.appendChild(listItem);
            }



        });
    </script>


</body>

</html>