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

                    <div class="chat-content d-lg-flex">
                        <!-- start chat conversation section -->
                        <div class="w-100 overflow-hidden position-relative">
                            <!-- conversation user -->
                            <div class="position-relative">


                                <div class="position-relative" id="users-chat">
                                    <div class="p-3 user-chat-topbar">
                                        <div class="row align-items-center">
                                            <div class="col-sm-4 col-8">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0 d-block d-lg-none me-3">
                                                        <a href="javascript: void(0);" class="user-chat-remove fs-18 p-1"><i
                                                                class="ri-arrow-left-s-line align-bottom"></i></a>
                                                    </div>
                                                    <div class="flex-grow-1 overflow-hidden">
                                                        @if ($selectedConversation)
                                                            <div class="d-flex align-items-center">
                                                                <img src="{{ $selectedConversation->customer->avatar ? asset('storage/avatars' . $selectedConversation->customer->avatar) : asset('images/default-user.png') }}"
                                                                    alt="Avatar" class="rounded-circle avatar-xs me-2">
                                                                <div class="flex-grow-1 overflow-hidden">
                                                                    <h5 class="text-truncate mb-0 fs-16">
                                                                        {{ $selectedConversation->customer->name }}
                                                                    </h5>
                                                                    <p class="text-truncate text-muted fs-14 mb-0">
                                                                        <small>
                                                                            @if ($selectedConversation->customer->status === 'online')
                                                                                <span
                                                                                    class="badge bg-success">Online</span>
                                                                            @elseif ($selectedConversation->customer->last_seen)
                                                                                <span class="badge bg-warning">
                                                                                    Hoạt động
                                                                                    {{ \Carbon\Carbon::parse($selectedConversation->customer->last_seen)->diffForHumans() }}
                                                                                </span>
                                                                            @else
                                                                                <span
                                                                                    class="badge bg-secondary">Offline</span>
                                                                            @endif
                                                                        </small>
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        @else
                                                            <div class="d-flex align-items-center">
                                                                <div class="flex-grow-1 overflow-hidden">
                                                                    <h5 class="text-truncate mb-0 fs-16 text-muted">
                                                                        Chưa chọn người để trò chuyện
                                                                    </h5>
                                                                </div>
                                                            </div>
                                                        @endif

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-8 col-4">
                                                <ul class="list-inline user-chat-nav text-end mb-0">
                                                    <li class="list-inline-item m-0">
                                                        <div class="dropdown">
                                                            <button
                                                                class="btn btn-ghost-secondary btn-icon material-shadow-none"
                                                                type="button" data-bs-toggle="dropdown"
                                                                aria-haspopup="true" aria-expanded="false">
                                                                <i data-feather="search" class="icon-sm"></i>
                                                            </button>
                                                            <div
                                                                class="dropdown-menu p-0 dropdown-menu-end dropdown-menu-lg">
                                                                <div class="p-2">
                                                                    <div class="search-box">
                                                                        <input type="text"
                                                                            class="form-control bg-light border-light"
                                                                            placeholder="Search here..."
                                                                            onkeyup="searchMessages()" id="searchMessage">
                                                                        <i class="ri-search-2-line search-icon"></i>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>

                                                    <li class="list-inline-item d-none d-lg-inline-block m-0">
                                                        <button type="button"
                                                            class="btn btn-ghost-secondary btn-icon material-shadow-none"
                                                            data-bs-toggle="offcanvas"
                                                            data-bs-target="#userProfileCanvasExample"
                                                            aria-controls="userProfileCanvasExample">
                                                            <i data-feather="info" class="icon-sm"></i>
                                                        </button>
                                                    </li>
                                                    <li class="list-inline-item m-0">
                                                        <div class="dropdown">
                                                            <button
                                                                class="btn btn-ghost-secondary btn-icon material-shadow-none"
                                                                type="button" data-bs-toggle="dropdown"
                                                                aria-haspopup="true" aria-expanded="false">
                                                                <i data-feather="more-vertical" class="icon-sm"></i>
                                                            </button>
                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                <a class="dropdown-item d-block d-lg-none user-profile-show"
                                                                    href="#"><i
                                                                        class="ri-user-2-fill align-bottom text-muted me-2"></i>
                                                                    View Profile</a>
                                                                <a class="dropdown-item" href="#"><i
                                                                        class="ri-inbox-archive-line align-bottom text-muted me-2"></i>
                                                                    Archive</a>
                                                                <a class="dropdown-item" href="#"><i
                                                                        class="ri-mic-off-line align-bottom text-muted me-2"></i>
                                                                    Muted</a>
                                                                <a class="dropdown-item" href="#"><i
                                                                        class="ri-delete-bin-5-line align-bottom text-muted me-2"></i>
                                                                    Delete</a>
                                                            </div>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>

                                    </div>
                                    <!-- end chat user head -->
                                    <div class="chat-conversation p-3 p-lg-4" id="chat-conversation" data-simplebar>
                                        <div id="elmLoader" class="text-center py-4">

                                        </div>
                                        @php
                                            $previousDate = null;
                                        @endphp

                                        @php
                                            $previousDate = null;
                                        @endphp

                                        <ul class="list-unstyled chat-conversation-list" id="users-conversation">
                                            @foreach ($messages as $message)
                                                @php
                                                    $isMine = $message->sender_id === auth()->id();
                                                    $isAdmin = $message->sender->isAdmin();
                                                    $side = $isAdmin ? 'right' : 'left';
                                                    $bgColor = $isAdmin
                                                        ? 'bg-primary text-white'
                                                        : 'bg-light text-dark';
                                                    $date = $message->created_at->format('Y-m-d');
                                                @endphp

                                                @if ($date !== $previousDate)
                                                    <li class="my-4">
                                                        <div class="d-flex align-items-center justify-content-center">
                                                            <hr class="flex-grow-1 border-top border-light mx-2" />
                                                            <span class="badge bg-light text-dark shadow-sm px-3 py-1">
                                                                {{ \Carbon\Carbon::parse($date)->isToday() ? 'Today' : (\Carbon\Carbon::parse($date)->isYesterday() ? 'Yesterday' : \Carbon\Carbon::parse($date)->translatedFormat('d F Y')) }}
                                                            </span>
                                                            <hr class="flex-grow-1 border-top border-light mx-2" />
                                                        </div>
                                                    </li>
                                                    @php
                                                        $previousDate = $date;
                                                    @endphp
                                                @endif


                                                <li class="chat-list {{ $side }}"
                                                    id="message-{{ $message->id }}">
                                                    <div class="conversation-list">
                                                        {{-- Avatar nếu là bên trái --}}
                                                        @if (!$isMine)
                                                            <div class="chat-avatar">
                                                                <img src="{{ $message->sender->avatar ?? asset('storage/avatars/' . $message->sender->avatar) }}"
                                                                    alt="Admin" class="avatar-xs rounded-circle">
                                                            </div>
                                                        @endif

                                                        <div class="user-chat-content">
                                                            <div class="ctext-wrap">
                                                                <div class="ctext-wrap-content {{ $bgColor }}"
                                                                    id="{{ $message->id }}"
                                                                    style="border-radius: 10px; padding: 10px;">
                                                                    <p class="mb-0 ctext-content">{{ $message->content }}
                                                                    </p>
                                                                </div>

                                                                {{-- Dropdown hành động --}}
                                                                <div class="dropdown align-self-start message-box-drop">
                                                                    <a class="dropdown-toggle" href="#"
                                                                        role="button" data-bs-toggle="dropdown"
                                                                        aria-haspopup="true" aria-expanded="false">
                                                                        <i class="ri-more-2-fill"></i>
                                                                    </a>
                                                                    <div class="dropdown-menu">
                                                                        <a class="dropdown-item reply-message"
                                                                            href="#"><i
                                                                                class="ri-reply-line me-2 text-muted align-bottom"></i>Reply</a>
                                                                        <a class="dropdown-item" href="#"><i
                                                                                class="ri-share-line me-2 text-muted align-bottom"></i>Forward</a>
                                                                        <a class="dropdown-item copy-message"
                                                                            href="#"><i
                                                                                class="ri-file-copy-line me-2 text-muted align-bottom"></i>Copy</a>
                                                                        <a class="dropdown-item" href="#"><i
                                                                                class="ri-bookmark-line me-2 text-muted align-bottom"></i>Bookmark</a>
                                                                        <a class="dropdown-item delete-item"
                                                                            href="#"><i
                                                                                class="ri-delete-bin-5-line me-2 text-muted align-bottom"></i>Delete</a>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="conversation-name">
                                                                <span
                                                                    class="d-none name">{{ $message->sender->name }}</span>
                                                                <small
                                                                    class="text-muted time">{{ $message->created_at->format('h:i A') }}</small>
                                                                <span class="text-success check-message-icon"><i
                                                                        class="bx bx-check-double"></i></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>





                                        <div id="chat-end-message" class="text-center mt-4" style="display:none;">
                                            <p class="text-muted mb-0">End of conversation</p>
                                        </div>
                                    </div>
                                    <div class="alert alert-warning alert-dismissible copyclipboard-alert px-4 fade show "
                                        id="copyClipBoard" role="alert">
                                        Message copied
                                    </div>
                                </div>
                            </div>

                            <!-- end chat-conversation -->



                            <div class="chat-input-section p-3 p-lg-4">
                                <form id="chatinput-form" class="message-form">
                                    <div class="row g-0 align-items-center">
                                        <!-- Nút emoji -->
                                        <div class="col-auto pe-2">
                                            <button type="button" class="btn btn-light rounded-circle p-2"
                                                id="emoji-btn">
                                                <i class="bx bx-smile fs-4 text-primary"></i>
                                            </button>
                                        </div>

                                        <!-- Input soạn tin nhắn -->
                                        <div class="col px-2">
                                            <div class="position-relative">
                                                <input type="text" id="chat-input"
                                                    class="form-control chat-input bg-light border-0 rounded-pill shadow-sm"
                                                    placeholder="Type your message..." autocomplete="off">
                                                <div class="chat-input-feedback">
                                                    Please enter a message
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Nút gửi -->
                                        <div class="col-auto ps-2">
                                            <button type="submit"
                                                class="btn btn-primary rounded-circle p-2 message-send-btn">
                                                <i class="bx bx-send fs-5"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>


                            <div class="replyCard">
                                <div class="card mb-0">
                                    <div class="card-body py-3">
                                        <div class="replymessage-block mb-0 d-flex align-items-start">
                                            <div class="flex-grow-1">
                                                <h5 class="conversation-name"></h5>
                                                <p class="mb-0"></p>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <button type="button" id="close_toggle"
                                                    class="btn btn-sm btn-link mt-n2 me-n3 fs-18">
                                                    <i class="bx bx-x align-middle"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end chat-wrapper -->

    </div>
    <!-- container-fluid -->
    </div>
    <!-- End Page-content -->

    <div class="offcanvas offcanvas-end border-0" tabindex="-1" id="userProfileCanvasExample" aria-modal="true"
        role="dialog" style="width: 400px;">
        <div class="offcanvas-body profile-offcanvas p-0">
            <!-- Header với background image -->
            <div class="position-relative">
                <div class="profile-cover position-relative"
                    style="height: 200px; background-image: url('{{ asset('images/golden-gate.jpg') }}'); background-size: cover; background-position: center;">
                    <div style="position: absolute; inset: 0; background: rgba(0,0,0,0.3);"></div>
                </div>
                <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3"
                    data-bs-dismiss="offcanvas" aria-label="Close"></button>

                <!-- Avatar centered between cover and white background -->
                <div class="text-center"
                    style="position: absolute; left: 50%; bottom: 0; transform: translate(-50%, 50%);">
                    <div class="position-relative d-inline-block">
                        <img src="{{ $selectedConversation->customer->avatar ?? asset('images/default-user.png') }}"
                            alt="Avatar" class="avatar-lg img-thumbnail rounded-circle mx-auto profile-img"`
                            style="width: 150px; height: 150px; border-radius: 50%; object-fit: cover;">
                    </div>
                </div>



            </div>

            <!-- White background content -->
            <div class="bg-white" style="padding-top: 75px;">
                <!-- Name and Status -->
                <div class="text-center mb-4">
                    @if ($selectedConversation)
                        <h4 class="mb-1">{{ $selectedConversation->customer->name }}</h4>
                        <p class="text-success mb-0">
                            <small>
                                @if ($selectedConversation->customer->status === 'online')
                                    <span class="badge bg-success">Online</span>
                                @elseif ($selectedConversation->customer->last_seen)
                                    <span class="badge bg-warning">
                                        Hoạt động
                                        {{ \Carbon\Carbon::parse($selectedConversation->customer->last_seen)->diffForHumans() }}
                                    </span>
                                @else
                                    <span class="badge bg-secondary">Offline</span>
                                @endif
                            </small>
                        </p>
                        <!-- Contact Information -->
                        <div class="px-4">
                            <div class="card bg-light border-0 mb-3">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="flex-shrink-0">
                                            <i class="ri-phone-line fs-4 text-primary"></i>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <p class="text-muted mb-0">Phone Number</p>
                                            <h6 class="mb-0">{{ $selectedConversation->customer->phone }}</h6>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="flex-shrink-0">
                                            <i class="ri-mail-line fs-4 text-primary"></i>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <p class="text-muted mb-0">Email Address</p>
                                            <h6 class="mb-0">{{ $selectedConversation->customer->email }}</h6>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <i class="ri-map-pin-line fs-4 text-primary"></i>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <p class="text-muted mb-0">Location</p>
                                            <h6 class="mb-0">{{ $selectedConversation->customer->location }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Action buttons -->
                            <div class="d-flex gap-2 mb-4">
                                <button class="btn btn-primary flex-grow-1">
                                    <i class="ri-message-3-line me-1"></i> Message
                                </button>
                                <button class="btn btn-light flex-grow-1">
                                    <i class="ri-user-follow-line me-1"></i> Follow
                                </button>
                            </div>
                        </div>
                    @else
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1 overflow-hidden">
                                <h5 class="text-truncate mb-0 fs-16 text-muted">
                                    Chưa chọn người để trò chuyện
                                </h5>
                            </div>
                        </div>
                    @endif

                </div>


            </div>
        </div>
    </div>

@endsection
