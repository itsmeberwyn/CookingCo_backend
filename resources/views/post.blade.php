@extends('layouts.app')
<link href="{{ asset('css/post.css') }}" rel="stylesheet">

@section('content')
    <div class="container mt-5 mb-5">
        <div class="row d-flex align-items-center justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="d-flex justify-content-between p-2 px-3">
                        <div class="d-flex flex-row align-items-center"> <img
                                src={{ asset('storage/posts/profiles/' . $post[0]->profile_image) }} width="50"
                                class="rounded-circle">
                            <div class="d-flex flex-column ms-2 text-capitalize">
                                <a href="/user/{{ $post[0]->user_id }}" class="text-black">
                                    <span class="font-weight-bold">{{ $post[0]->firstname }}
                                        {{ $post[0]->lastname }}</span>
                                </a>
                                <small class="text-primary">{{ $post[0]->email }}</small>
                            </div>
                        </div>
                        <div class="d-flex flex-row mt-1 ellipsis"> <small
                                class="mr-2">{{ $time }}</small> <i class="fa fa-ellipsis-h"></i>
                        </div>
                    </div>

                    <div class='d-flex justify-content-center bg-secondary'>
                        <img src="{{ asset('storage/posts/' . $post[0]->post_image) }}" class="img-fluid"
                            style="width: 600px; height: 600px; object-fit: cover;">
                    </div>

                    <div class="p-2">
                        <h5 class="text-justify text-capitalize">{{ $post[0]->title }}</h5>
                        <p class="text-justify ">{{ $post[0]->caption }}</p>
                        <hr>

                        @if ($post[0]->recipe !== null)
                            <div class='d-flex justify-content-around mb-3'>
                                <div class='d-flex flex-column justify-content-center align-items-center'>
                                    <h5>Duration</h5>
                                    <span>
                                        {{ $post[0]->recipe->duration }}
                                    </span>
                                </div>
                                <div class='d-flex flex-column justify-content-center align-items-center'>
                                    <h5>Calories</h5>
                                    <span>
                                        {{ $post[0]->recipe->calories }}
                                    </span>
                                </div>
                                <div class='d-flex flex-column justify-content-center align-items-center'>
                                    <h5>Servings</h5>
                                    <span>
                                        {{ $post[0]->recipe->servings }}
                                    </span>
                                </div>
                            </div>
                            <hr>


                            <div class="list-group mb-2">
                                <h5 class="text-justify text-capitalize mb-2">Ingredients</h5>

                                @foreach ($ingredients as $ingredient)
                                    <li class="list-group-item list-group-item-action">{{ $loop->index + 1 }}.
                                        {{ $ingredient->ingredient }}</li>
                                @endforeach
                            </div>
                            <hr>

                            <div class="list-group mb-2">
                                <h5 class="text-justify text-capitalize mb-2">Procedures</h5>

                                @foreach ($procedures as $procedure)
                                    <li class="list-group-item list-group-item-action">{{ $loop->index + 1 }}.
                                        {{ $procedure->procedure }}</li>
                                @endforeach
                            </div>
                            <hr>
                        @endif


                        {{-- <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex flex-row muted-color"> <span>2 comments</span></div>
                        </div> --}}
                        {{-- <hr> --}}

                        <div class="comments">
                            @foreach ($comments as $comment)
                                <div class="d-flex flex-row mb-2 border-bottom pb-2"
                                    id="{{ preg_replace('/\s+/', '-', $comment->message) }}"> <img
                                        src="{{ asset('storage/posts/profiles/' . $comment->profile_image) }}" width="40"
                                        class="rounded-image">
                                    <div class="d-flex flex-column ms-2">
                                        <span class="name">{{ $comment->firstname }}
                                            {{ $comment->lastname }}</span>
                                        <small class="comment-text">{{ $comment->message }}</small>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
