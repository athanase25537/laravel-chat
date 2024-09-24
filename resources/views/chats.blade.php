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
                                        <p>{{ $friend->name }}</p>
                                        <a href="{{ route('friends') }}" class="float-right text-primary border border-primary px-2">Back</a>
                                    </div>
                                    <div class="card-body" style="height: 90%;">
                                        <div class="sms mb-3 text-white" style="overflow: scroll; height: 75%">
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

                                        <form id="chat-form" action="{{ route('posts', request()->id) }}" method="post">
                                            @csrf
                                            <input id="sms-content" type="text" name="sms" class="mb-3 form-control" required>
                                            <button id="chat-btn" class="btn btn-primary">Envoyer</button>
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

            window.Echo
            .private('private-channel.user.{{ Auth::id() }}')
            .listen('PrivateEvent', (data) => {
                $('#sms-container').append('<div class="w-50 mb-2"><p style="width: fit-content; max-width: 100%" class="rounded p-2 bg-secondary">'+data.message+'</p></div>')

                $('#sms-container').parent().animate({
                    scrollTop: $('#sms-container').height()
                });
            });

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
