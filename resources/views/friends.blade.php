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
                                        <div class="col rounded-top border p-0 h-100 mx-1 overflow-scroll">
                                            <h1 class="card-header position-sticky top-0 text-center mb-3">MY Friends</h1>
                                            <div id="friends-content">
                                                @if (!empty($friends))
                                                    @foreach ($friends as $friend)
                                                        <div class="mx-2 mb-3 d-flex justify-content-between align-items-center bg-light rounded">
                                                            <p class="mx-2 w-50">{{ $friend->name }}</p>
                                                            <div class="bts">
                                                                <a href="{{ route('chats', $friend->id) }}" class="btn btn-primary">Chat</a>
                                                                <a href="{{ route('delete', $friend->id) }}" data-id="{{ $friend->id }}" class="btn btn-danger chat-delete">Remove</a>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @else
                                                        <p class="text-danger text-center">You don't have any friend</p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col rounded-top border p-0 h-100 mx-1 overflow-scroll">
                                            <h1 class="position-sticky top-0 card-header text-center mb-3">Do you know ?</h1>
                                            @if (!empty($notFriends))
                                                @foreach ($notFriends as $notFriend)
                                                    <div class="mx-2 mb-3 d-flex justify-content-between align-items-center bg-light rounded">
                                                        <p class="mx-2">{{ $notFriend->name }}</p>
                                                        <a href="{{ route('add', $notFriend->id) }}" class="btn btn-primary">Add Friend</a>
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

    <script>
        $(document).ready(() => {
            $('.chat-delete').each(function () {
                $(this).click( (e) => {
                    e.preventDefault();
                    a = $(this).parent().siblings().text();
                    console.log(a);

                    let id = $(this).attr('data-id');
                    $.ajax({
                        type: 'GET',
                        url: "friends/"+id,
                        success: () => {
                            $(this).parent().parent().remove();
                            if($('#friends-content').text().trim()=='') {
                                $('#friends-content').append('<p class="text-danger text-center">You dont have any friend</p>');
                            }
                        },
                        error: function (e) {
                            console.log(e.responseText);
                            $('#chat-btn').prop('disabled', false);
                        }
                    })
                })
            });

        });
    </script>
</x-app-layout>
