@extends('layouts.app')

@section('content')
    <div class="p-10 bg-surface-secondary" style='height:calc(100vh - 87.99px);'>
        <div class="container">
            <div class="card">
                <div class="card-header">
                    <h6>Reported Comment</h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover table-nowrap">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">No.</th>
                                <th scope="col">Comment owner</th>
                                <th scope="col">Comment</th>
                                <th scope="col">Reason</th>
                                <th scope="col">No. of Report</th>
                                <th scope="col">Action</th>
                                {{-- <th></th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($reported_comments as $key => $reported)
                                <tr>
                                    <td data-label="No.">
                                        <span>{{ ($reported_comments->currentPage() - 1) * $reported_comments->perPage() + $loop->iteration }}</span>
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
                                    <td data-label="title"> <span>{{ $reported->message }}</span> </td>
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
                                                onClick="lookup_users({{ $reported->post_id }}, '{{ $reported->message }}')">
                                                <i class="bi bi-eye"></i>
                                            </label>
                                            <label
                                                class="btn btn-warning <?php if ($reported->isSeen == false){ ?>
                                                disable_link <?php   } ?>"
                                                data-bs-toggle="modal"
                                                data-bs-target="#exampleModal-{{ $reported->user_id }}">
                                                <i class="bi bi-exclamation-circle"></i>
                                            </label>
                                            <label class="btn btn-danger">
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
            {!! $reported_comments->links() !!}
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

        function lookup_users(post_id, message) {
            let slug = message.replaceAll(' ', '-')
            window.location.href = '/post/' + post_id + "#" + slug;
        }

        function warn_users(user_id) {
            window.location.href = '/warn-user?id=' + user_id;
        }
    </script>
@endsection
