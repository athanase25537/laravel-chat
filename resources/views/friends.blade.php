<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Chats') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-md-8">
                                <div class="card" style="height: 80vh;">
                                    <div class="card-header d-flex justify-content-between align-items-center">{{ __('M\'resaka') }}
                                    </div>
                                    <div class="row card-body" style="height: 90%;">
                                        <div class="col rounded-top border p-0 h-100 mx-1 overflow-scroll" id="myFriend">
                                            <h1 class="card-header position-sticky top-0 text-center mb-3">MY Friends</h1>
                                            <div id="friends-content">
                                                @if (!empty($friends))
                                                    @foreach ($friends as $friend)
                                                        <div class="mx-2 mb-3 d-flex px-2 justify-content-between align-items-center bg-light rounded">
                                                            <div class="w-50 d-flex flex-column" id="user-{{ $friend->id }}">
                                                                <p class="m-0 w-100">{{ $friend->name }}</p>
                                                                <p class="small text-danger capitalize">Offline</p>
                                                            </div>
                                                            <div class="bts d-flex align-items-center">
                                                                <a href="{{ route('chats', $friend->id) }}" class="btn"><i class="fa-regular fa-comments fs-4 text-primary"></i></a>
                                                                <a href="{{ route('delete', $friend->id) }}" class="btn chat-delete"><i class="fa-solid fa-trash text-danger fs-4"></i></a>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @else
                                                        <p class="text-danger text-center">You don't have any friend</p>
                                                @endif
                                            </div>
                                        </div>
                                        <div id="friend_add" class="col rounded-top border p-0 h-100 mx-1 overflow-scroll">
                                            <h1 class="position-sticky border border-secondary bg-secondary text-white top-0 card-header text-center mb-3">Do you know ?</h1>
                                            @if (!empty($notFriends))
                                                @foreach ($notFriends as $notFriend)
                                                    <div class="mx-2 mb-3 d-flex justify-content-between align-items-center bg-light rounded px-2">
                                                        <div class="w-50 d-flex flex-column" id="user-{{ $notFriend->id }}">
                                                            <p class="m-0">{{ $notFriend->name }}</p>
                                                            <p class="small text-danger capitalize">Offline</p>
                                                        </div>
                                                        <a href="{{ route('add', $notFriend->id) }}" data-id="{{ $notFriend->id }}" class="btn add_friend"><i class="fa-solid fa-plus fs-5 text-primary"></i></a>
                                                    </div>
                                                @endforeach

                                            @else
                                                <p class="text-success text-center">Every one is your friend</p>
                                            @endif
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

    @vite('resources/js/app.js')

    <script type="module">

        // update friend and not friend list
        window.Echo
        .private('friend-channel.user.{{ Auth::id() }}')
        .listen('FriendEvent', (e) => {
            location.reload(true);
        })

        // get online or offline status
        window.Echo
        .channel('status-channel')
        .listen('OnlineOffline', (data) => {
            let a = $('#user-'+data.userId+' p:last-child');
            console.log(data);

            if(data.status == 'online')
                a.text(data.status).removeClass('text-danger').addClass('text-success');
            else
                a.text(data.status).removeClass('text-success').addClass('text-danger');
        })
    </script>
</x-app-layout>
