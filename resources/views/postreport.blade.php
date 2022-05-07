@extends('layouts.app')

@section('content')
    <div class="p-10 bg-surface-secondary" style='height:calc(100vh - 87.99px);'>
        <div class="container">
            <div class="card">
                <div class="card-header">
                    <h6>Reported Post</h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover table-nowrap">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">No.</th>
                                <th scope="col">Post owner</th>
                                <th scope="col">Post Title</th>
                                <th scope="col">Post Caption</th>
                                <th scope="col">Reason</th>
                                <th scope="col">No. of Report</th>
                                <th scope="col">Action</th>
                                {{-- <th></th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($reported_posts as $key => $reported)
                                <tr>
                                    <td data-label="No.">
                                        <span>{{ ($reported_posts->currentPage() - 1) * $reported_posts->perPage() + $loop->iteration }}</span>
                                    </td>
                                    <td data-label="Name">
                                        <img alt="..."
                                            src="{{ asset('storage/posts/profiles/' . $reported->profile_image) }}"
                                            onerror="this.onerror=null;this.src='{{ $reported->profile_image }}';"
                                            class="avatar avatar-sm rounded-circle me-2">
                                        <a class="text-heading font-semibold" href="/user/{{ $reported->user_id }}">
                                            {{ $reported->firstname }}
                                            {{ $reported->lastname }}</a>
                                    </td>
                                    <td data-label="Username"> <span>{{ $reported->title }}</span> </td>
                                    <td data-label="Eamil">{{ $reported->caption }}
                                    </td>
                                    <td class='text-wrap'>
                                        @foreach (json_decode($reported->reason) as $reason)
                                            {{ $loop->iteration }}. {{ $reason }} <br>
                                        @endforeach
                                    </td>
                                    <td data-label="report">{{ $reported->noreports }}
                                    </td>
                                    <td class='text-wrap'>
                                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                            <label class="btn btn-success" id='checkuser'
                                                onClick="lookup_users({{ $reported->post_id }})">
                                                <i class="bi bi-eye"></i>
                                            </label>
                                            <label
                                                class="btn btn-warning <?php if ($reported->isSeen == false){ ?>
                                                disable_link <?php   } ?>"
                                                data-bs-toggle="modal"
                                                data-bs-target="#exampleModal-{{ $reported->user_id }}">
                                                <i class="bi bi-exclamation-circle"></i>
                                            </label>
                                            <label
                                                class="btn btn-danger  <?php if ($reported->isBan){ ?>
                                                disable_link <?php   } ?>"
                                                data-bs-toggle="modal"
                                                data-bs-target="#exampleModal-ban-{{ $reported->user_id }}">
                                                <i class="bi bi-x-octagon"></i>
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                                <div class="modal fade" id="exampleModal-{{ $reported->user_id }}" tabindex="-1"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Warning</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>You are about to send a warning to user {{ $reported->firstname }}
                                                    {{ $reported->lastname }}. Are you sure you want to continue?</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Cancel</button>
                                                <button type="button" class="btn btn-primary"
                                                    onClick="warn_users({{ $reported->user_id }})">Yes</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- for verifying the ban --}}
                                <form method="post" action="{{ url('/ban-user?id=' . $reported->user_id) }}">
                                    @csrf
                                    <div class="modal fade" id="exampleModal-makesure-{{ $reported->user_id }}"
                                        tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Warning</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>You are about to ban user {{ $reported->firstname }}
                                                        {{ $reported->lastname }}. Are you sure you want to continue?</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-primary">Yes</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- for banning user --}}
                                    <div class="modal fade" id="exampleModal-ban-{{ $reported->user_id }}"
                                        tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Ban User</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="list-group">
                                                        <button type="button"
                                                            class="list-group-item list-group-item-action">
                                                            <div class='form-check'>
                                                                <input class="form-check-input" type="radio" name="banuser"
                                                                    id="ban1day-{{ $reported->user_id }}" value="1">
                                                                <label class="form-check-label" for="ban1day">
                                                                    Ban for 1 Day
                                                                </label>
                                                            </div>
                                                        </button>
                                                        <button type="button"
                                                            class="list-group-item list-group-item-action">
                                                            <div class='form-check'>
                                                                <input class="form-check-input" type="radio" name="banuser"
                                                                    id="ban3days-{{ $reported->user_id }}" value="3">
                                                                <label class="form-check-label" for="ban3days">
                                                                    Ban for 3 Days
                                                                </label>
                                                            </div>
                                                        </button>
                                                        <button type="button"
                                                            class="list-group-item list-group-item-action">
                                                            <div class='form-check'>
                                                                <input class="form-check-input" type="radio" name="banuser"
                                                                    id="ban5days-{{ $reported->user_id }}" value="5">
                                                                <label class="form-check-label" for="ban5days">
                                                                    Ban for 5 Days
                                                                </label>
                                                            </div>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Close</button>
                                                    <button type="button" class="btn btn-primary"
                                                        onClick="ban_user('{{ $reported->user_id }}')">Save
                                                        changes</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            @endforeach
                        </tbody>
                    </table>

                    @if (\Session::has('success'))
                        <div class="modal fade" id="succes-modal" tabindex="-1"
                            aria-labelledby="exampleModalLabel-success" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel-success">Success</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>{!! \Session::get('success') !!}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="mt-5 d-flex justify-content-center">
            {!! $reported_posts->links() !!}
        </div>
    </div>

    <script>
        $(window).on('load', function() {
            $('#succes-modal').modal('show');
        });
        $(document).ready(function() {
            console.log("ready")
            // $('#checkuser').click(function(e) {
            //     window.location.href = 'http://example.com';
            // });
        });

        function lookup_users(post_id) {
            window.location.href = '/post/' + post_id;
        }

        function warn_users(user_id) {
            window.location.href = '/warn-user?id=' + user_id;
        }

        function ban_user(modalid) {
            if (document.querySelector(`input[id="ban1day-${modalid}"]:checked`)?.value || document.querySelector(
                    `input[id="ban3days-${modalid}"]:checked`)?.value || document.querySelector(
                    `input[id="ban5days-${modalid}"]:checked`)
                ?.value) {
                $('#exampleModal-ban-' + modalid).modal('hide');
                $('#exampleModal-makesure-' + modalid).modal('show');
            }
        }
    </script>
@endsection
