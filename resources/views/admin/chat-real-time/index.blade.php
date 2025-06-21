@extends('layouts.backend')

@section('title', 'Chat Real Time')

@section('content')
    {{-- <link href="{{ asset('assets/css/chat.css') }}" rel="stylesheet" type="text/css" /> --}}
    <style>
        .chat-conversation-list hr {
            border-color: #ccc;
            opacity: 0.5;
        }

        .chat-conversation-list .badge.bg-secondary {
            background-color: #dee2e6 !important;
            color: #333;
            font-size: 13px;
        }

        .chat-list.right .conversation-list {
            justify-content: flex-end;
        }

        .chat-list.left .conversation-list {
            justify-content: flex-start;
        }

        /* Background và màu sắc cho tin nhắn */
        .bg-primary {
            background-color: #007bff !important;
        }

        .text-white {
            color: white !important;
        }

        .bg-light {
            background-color: #f1f1f1 !important;
        }

        .text-dark {
            color: #333 !important;
        }

        /* Gỡ bỏ underline hoặc border dưới nút dropdown ⋮ */
        .message-box-drop .dropdown-toggle {
            border: none !important;
            text-decoration: none !important;
            outline: none !important;
            box-shadow: none !important;
            background: transparent !important;
            padding: 0;
            margin: 0;
            display: inline-flex;
            align-items: center;
        }

        .chat-conversation-list hr {
            border-color: #757272;
            opacity: 0.5;
            justify-content: center;
            align-items: center;
            display: flex;
        }

        .chat-conversation-list .badge {
            justify-content: center;
            align-items: center;
            display: flex;
            padding: 0.25rem 0.5rem;
            font-size: 13px;
            font-weight: 500;
        }
    </style>
    <div class="page-content">
        <div class="container-fluid">
            <div class="chat-wrapper d-lg-flex gap-1 mx-n4 mt-n4 p-1">
                <div class="chat-leftsidebar minimal-border">
                    <div class="px-4 pt-4 mb-3">
                        <div class="d-flex align-items-start">
                            <div class="flex-grow-1">
                                <h5 class="mb-4">Chats</h5>
                            </div>
                            <div class="flex-shrink-0">
                                <div data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="bottom"
                                    title="Add Contact">

                                    <!-- Button trigger modal -->
                                    <button type="button" class="btn btn-soft-success btn-sm material-shadow-none">
                                        <i class="ri-add-line align-bottom"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="search-box">
                            <input type="text" class="form-control bg-light border-light" placeholder="Search here...">
                            <i class="ri-search-2-line search-icon"></i>
                        </div>
                    </div> <!-- .p-4 -->

                    <ul class="nav nav-tabs nav-tabs-custom nav-success nav-justified" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#chats" role="tab">
                                Chats
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#contacts" role="tab">
                                Contacts
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content text-muted">
                        <div class="tab-pane active" id="chats" role="tabpanel">
                            <div class="chat-room-list pt-3" data-simplebar>
                                <div class="d-flex align-items-center px-4 mb-2">
                                    <div class="flex-grow-1">
                                        <h4 class="mb-0 fs-11 text-muted text-uppercase">Direct Messages</h4>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <div data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="bottom"
                                            title="New Message">

                                            <!-- Button trigger modal -->
                                            <button type="button"
                                                class="btn btn-soft-success btn-sm shadow-none material-shadow">
                                                <i class="ri-add-line align-bottom"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="chat-message-list">

                                    <ul class="list-unstyled chat-list chat-user-list" id="userList">
                                        @foreach ($conversations as $conversation)
                                            <li>
                                                <a href="{{ route('admin.chat.index', $conversation->id) }}"
                                                    class="d-flex align-items-center px-4 py-2">
                                                    <!-- Avatar khách hàng -->
                                                    <div class="flex-shrink-0 me-3">
                                                        <img src="{{ $conversation->customer->avatar ?? asset('images/default-user.png') }}"
                                                            alt="Avatar" class="rounded-circle avatar-xs">
                                                    </div>

                                                    <!-- Nội dung -->
                                                    <div class="flex-grow-1 overflow-hidden">
                                                        <h5 class="text-truncate fs-15 mb-1">
                                                            {{ $conversation->customer->name }}
                                                        </h5>
                                                        <p class="text-truncate text-muted fs-13 mb-0">
                                                            {{ $conversation->messages->first()?->content ?? 'Chưa có tin nhắn' }}
                                                        </p>
                                                    </div>

                                                    <!-- Thời gian -->
                                                    <div class="flex-shrink-0 ms-2">
                                                        <span class="text-muted fs-11">
                                                            {{ optional($conversation->customer->last_seen)->diffForHumans() }}

                                                        </span>
                                                    </div>
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>

                                </div>

                                <div class="d-flex align-items-center px-4 mt-4 pt-2 mb-2">
                                    <div class="flex-grow-1">
                                        <h4 class="mb-0 fs-11 text-muted text-uppercase">Channels</h4>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <div data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="bottom"
                                            title="Create group">
                                            <!-- Button trigger modal -->
                                            <button type="button" class="btn btn-soft-success btn-sm">
                                                <i class="ri-add-line align-bottom"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="chat-message-list">

                                    <ul class="list-unstyled chat-list chat-user-list mb-0" id="channelList">
                                    </ul>
                                </div>
                                <!-- End chat-message-list -->
                            </div>
                        </div>
                        <div class="tab-pane" id="contacts" role="tabpanel">
                            <div class="chat-room-list pt-3" data-simplebar>
                                <div class="sort-contact">
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end tab contact -->
                </div>
                <!-- end chat leftsidebar -->
                <!-- Start User chat -->
                <div class="user-chat w-100 overflow-hidden minimal-border">
                    @if ($selectedConversation)
                        @livewire('chat-realtime', [
                            'selectedConversation' => $selectedConversation,
                            'messages' => $messages,
                        ])
                    @else
                        <div class="d-flex justify-content-center align-items-center h-100">
                            <h4 class="text-muted">Vui lòng chọn một cuộc trò chuyện để bắt đầu</h4>
                        </div>
                    @endif
                </div>
                <!-- end chat-wrapper -->
            </div>
            <!-- container-fluid -->
        </div>
        <!-- End Page-content -->

    @endsection
    @section('script')
        @if ($selectedConversation)
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    const chatForm = document.getElementById('chatinput-form');
                    const chatInput = document.getElementById('chat-input');
                    const conversationId = {{ $selectedConversation->id }};
                    const userId = {{ auth()->id() }};

                    chatForm.addEventListener('submit', function(e) {
                        e.preventDefault();

                        const content = chatInput.value.trim();
                        if (!content) return;

                        fetch("{{ route('admin.chat.send') }}", {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({
                                    conversation_id: conversationId,
                                    sender_id: userId,
                                    content: content
                                })
                            })
                            .then(res => res.json())
                            .then(data => {
                                if (data.success) {
                                    chatInput.value = '';
                                } else {
                                    alert('Gửi tin nhắn thất bại!');
                                }
                            })
                            .catch(error => {
                                console.error('Lỗi:', error);
                                alert('Có lỗi khi gửi tin nhắn!');
                            });
                    });
                });
            </script>
        @endif
    @endsection
