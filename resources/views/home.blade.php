@extends('layouts.app')

@section('content')
    {{-- <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Dashboard') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        {{ __('You are logged in!') }}
                    </div>
                </div>
            </div>
        </div> --}}

    <div class="p-10 bg-surface-secondary">
        <div class="container">
            <div class="card">
                <div class="card-header">
                    <h6>Feedbacks</h6>
                    {{-- <h6>Reported User</h6> --}}
                </div>
                <div class="table-responsive">
                    <table class="table table-hover table-nowrap">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Name</th>
                                <th scope="col">Username</th>
                                <th scope="col">Email</th>
                                <th scope="col">Feedback</th>
                                {{-- <th scope="col">Lead Score</th> --}}
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @for ($i = 0; $i < 10; $i++)
                                <tr>
                                    <td data-label="ID"> <span>{{ $i }}</span> </td>
                                    <td data-label="Name"> <img alt="..."
                                            src="https://images.unsplash.com/photo-1502823403499-6ccfcf4fb453?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=3&w=256&h=256&q=80"
                                            class="avatar avatar-sm rounded-circle me-2"> <a
                                            class="text-heading font-semibold" href="#"> Robert Fox </a> </td>
                                    <td data-label="Username"> <span>Kamote</span> </td>
                                    <td data-label="Eamil"> <a class="text-current" href="#">robert.fox@example.com</a>
                                    </td>
                                    <td class='text-wrap'>
                                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Blanditiis voluptates,
                                        maiores obcaecati tempora assumenda consequuntur quasi doloribus dolorem fuga
                                        laudantium sint commodi earum vel temporibus dolores similique corrupti cum?
                                        Laudantium.
                                    </td>
                                    {{-- <td data-label="Company"> <span class="badge bg-soft-success text-success">7/10</span>
                                    </td>
                                    <td data-label="" class="text-end">
                                        <div class="dropdown"> <a class="text-muted" href="#" role="button"
                                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i
                                                    class="bi bi-three-dots-vertical"></i> </a>
                                            <div class="dropdown-menu dropdown-menu-end"> <a href="#!"
                                                    class="dropdown-item"> Action </a> <a href="#!" class="dropdown-item">
                                                    Another action </a> <a href="#!" class="dropdown-item"> Something
                                                    else
                                                    here </a> </div>
                                        </div>
                                    </td> --}}
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
