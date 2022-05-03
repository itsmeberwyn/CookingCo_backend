@extends('layouts.app')


@section('content')
    <div class="container mt-5 d-flex justify-content-center ">
        <div class="card p-3" style='width:700px;'>
            <div class="d-flex align-items-start">
                <div class="image">
                    <img src="https://images.unsplash.com/photo-1522075469751-3a6694fb2f61?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=500&q=80"
                        class="rounded" width="120" height='120'>
                </div>

                <div class="ms-3 w-100">
                    <h4 class="mb-0 mt-0 text-capitalize">{{ $user->firstname }} {{ $user->lastname }}</h4>
                    <span>{{ $user->email }}</span>

                    <div class="p-2 px-4 mt-2 d-flex justify-content-between rounded text-black  w-50">

                        <div class="d-flex flex-column align-items-center">
                            <span class="rating">Post</span>
                            <span class="number3">{{ $totalPost }}</span>
                        </div>
                        <div class="d-flex flex-column align-items-center">
                            <span class="articles">Following</span>
                            <span class="number1">38</span>
                        </div>
                        <div class="d-flex flex-column align-items-center">
                            <span class="followers">Followers</span>
                            <span class="number2">980</span>
                        </div>
                    </div>


                    {{-- <div class="button mt-2 d-flex flex-row align-items-center">

                        <button class="btn btn-sm btn-outline-primary w-100">Chat</button>
                        <button class="btn btn-sm btn-primary w-100 ml-2">Follow</button>
                    </div> --}}
                    <div class='mt-4'>
                        <small>{{ $user->bio }}</small>
                    </div>
                </div>
            </div>

            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                <label class="btn border-bottom" id='post__segment'>
                    POST
                </label>
                <label class="btn" id='recipe__segment'>
                    RECIPE
                </label>

            </div>


            <div class='d-flex flex-column align-items-center justify-content-center' id='posts__container'>
                @foreach ($posts as $chunks)
                    <div class='row mt-4 row__card' id='row_{{ $loop->index + 1 }}'>
                        @foreach ($chunks as $post)
                            <div class=" col-6 d-flex justify-content-center" style="width: 200px; height: 200px;">
                                <img class="card-img-top rounded border"
                                    style="width: 180px; height: 180px; object-fit: cover;"
                                    src="{{ asset('storage/posts/' . $post->post_image) }}" alt="Card image cap">
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>

            <div class='d-flex flex-column align-items-center justify-content-center' id='posts__container'>
                @foreach ($recipes as $chunks)
                    <div class='row mt-4 recipe__row__card' id='recipe_row_{{ $loop->index + 1 }}'>
                        @foreach ($chunks as $post)
                            <div class=" col-6 d-flex justify-content-center" style="width: 200px; height: 200px;">
                                <img class="card-img-top rounded border"
                                    style="width: 180px; height: 180px; object-fit: cover;"
                                    src="{{ asset('storage/posts/' . $post->post_image) }}" alt="Card image cap">
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>


        </div>

        <script type="text/javascript">
            $(document).ready(function() {
                $('.recipe__row__card').each(function(index) {
                    $('#recipe_row_' + (index + 1)).hide();
                })


                $('#recipe__segment').click(function(e) {
                    $('.row__card').each(function(index) {
                        $('#row_' + (index + 1)).hide();
                    })

                    $('.recipe__row__card').each(function(index) {
                        $('#recipe_row_' + (index + 1)).show();
                    })


                    $('#post__segment').removeClass('border-bottom');
                    $('#recipe__segment').addClass('border-bottom');

                    // console.log($('#posts__container').is(":visible"));
                });
                $('#post__segment').click(function(e) {
                    $('.row__card').each(function(index) {
                        $('#row_' + (index + 1)).show();
                    })

                    $('.recipe__row__card').each(function(index) {
                        $('#recipe_row_' + (index + 1)).hide();
                    })

                    $('#recipe__segment').removeClass('border-bottom');
                    $('#post__segment').addClass('border-bottom');

                });
            });
        </script>
    @endsection
