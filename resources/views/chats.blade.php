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
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center">
                                            <i class="fa-solid fa-user fs-3 text-primary"></i>
                                            <div class="d-flex flex-column mx-2" id="user-{{ $friend->id }}">
                                                <p class="m-0 w-100">{{ $friend->name }}</p>
                                                <p class="small text-danger capitalize">Offline</p>
                                            </div>
                                        </div>
                                        <a href="{{ route('friends') }}" class="float-right text-primary border border-primary px-2">Back</a>
                                    </div>
                                    <div class="card-body" style="height: 90%;">
                                        <div class="sms mb-3 text-white" style="overflow: scroll; height: 85%">
                                            <div id="sms-container">
                                                @php
                                                    $cpt = 0;
                                                @endphp

                                                @foreach ($sms as $msg)
                                                    @php
                                                        $cpt++
                                                    @endphp
                                                    @if ($msg->sender_id==Auth::user()->id)
                                                        <div class="d-flex justify-content-end w-50 mb-2" style="transform: translateX(100%)">
                                                            <p class="rounded p-2 bg-primary" style="width: fit-content; max-width: 100%;">{{ $msg->content }}</p>
                                                        </div>
                                                        @php
                                                            $id = $msg->receiver_id;
                                                        @endphp
                                                    @else
                                                        <div class="w-50 mb-2">
                                                            <p style="width: fit-content; max-width: 100%" class="rounded p-2 bg-secondary">{{ $msg->content }}</p>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>

                                        <form id="chat-form" class="form-group d-flex align-items-center" action="{{ route('posts', request()->id) }}" method="post">
                                            @csrf
                                            <input id="sms-content" type="text" name="sms" class="rounded form-control" required>
                                            <button id="chat-btn" class="btn"><i class="fa-solid fa-paper-plane fs-4 text-primary"></i></button>
                                        </form>
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
        $(document).ready(() => {

            // Get friend last message
            window.Echo
            .private('private-channel.user.{{ Auth::id() }}')
            .listen('PrivateEvent', (data) => {
                $('#sms-container').append('<div class="w-50 mb-2"><p style="width: fit-content; max-width: 100%" class="rounded p-2 bg-secondary">'+data.message+'</p></div>')

                $('#sms-container').parent().animate({
                    scrollTop: $('#sms-container').height()
                });
            });

            // Get online or offline status
            window.Echo
                .channel('status-channel')
                .listen('OnlineOffline', (data) => {
                    let a = $('#user-'+data.userId+' p:last-child');
                    if(data.status == 'online')
                        a.text(data.status).removeClass('text-danger').addClass('text-success');
                    else
                        a.text(data.status).removeClass('text-success').addClass('text-danger');
                })

            setInterval(() => {
                $.ajax({
                    type: 'GET',
                    url: "{{ route('friend.status', request()->id) }}",
                    success: (data) => {
                        if(data.status == 'online')
                            $('#user-'+data.friendId+' p:last-child').text(data.status).removeClass('text-danger').addClass('text-success');
                        else
                            $('#user-'+data.friendId+' p:last-child').text(data.status).removeClass('text-success').addClass('text-danger');
                    },
                    error: (e) => {
                        // console.log(e.responseText)
                    }
                })
            }, 500);

            $('#chat-form').submit((e) => {
                e.preventDefault();

                let form = $('#chat-form')[0];
                let data = new FormData(form);

                $('#chat-btn').prop('disabled', true);

                $.ajax({
                    type: 'POST',
                    url: "{{ route('posts', request()->id) }}",
                    data: data,
                    processData: false,
                    contentType: false,
                    success: function (data) {
                        $('#chat-btn').prop('disabled', false);
                        $('#sms-content').val('');
                        $('#sms-container').append('<div class="d-flex justify-content-end w-50 mb-2" style="transform: translateX(100%)"><p class="rounded p-2 bg-primary" style="width: fit-content; max-width: 100%;">'+data.sms+'</p></div>');

                        $('#sms-container').parent().animate({
                            scrollTop: $('#sms-container').height()
                        });

                    },
                    error: function (e) {
                        console.log(e.responseText);
                        $('#chat-btn').prop('disabled', false);
                    }
                });
            });
        })
    </script>
</x-app-layout>
