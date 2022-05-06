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

    <div class="p-10 bg-surface-secondary" style='height:calc(100vh - 87.99px);'>
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
                                <th scope="col">No.</th>
                                <th scope="col">Name</th>
                                <th scope="col">Username</th>
                                <th scope="col">Email</th>
                                <th scope="col">Feedback</th>
                                {{-- <th scope="col">Lead Score</th> --}}
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($feedbacks as $i => $feedback)
                                <tr>
                                    <td data-label="No.">
                                        <span>{{ ($feedbacks->currentPage() - 1) * $feedbacks->perPage() + $loop->iteration }}</span>
                                    </td>
                                    <td data-label="Name">
                                        <img alt="..."
                                            src="{{ asset('storage/posts/profiles/' . $feedback->profile_image) }}"
                                            onerror="this.onerror=null;this.src='{{ $feedback->profile_image }}';"
                                            class="avatar avatar-sm rounded-circle me-2">

                                        <a class="text-heading font-semibold" href="/user/{{ $feedback->user_id }}">
                                            {{ $feedback->firstname }}
                                            {{ $feedback->lastname }}</a>
                                    </td>
                                    <td data-label="Username"> <span>{{ $feedback->username }}</span> </td>
                                    <td data-label="Eamil"> <a class="text-current" href="#">{{ $feedback->email }}</a>
                                    </td>
                                    <td class='text-wrap'>
                                        {{ $feedback->message }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="mt-5 d-flex justify-content-center">
            {!! $feedbacks->links() !!}
        </div>
    </div>
@endsection
