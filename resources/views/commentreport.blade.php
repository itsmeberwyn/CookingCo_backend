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
                                            <label class="btn btn-warning">
                                                <i class="bi bi-exclamation-circle"></i>
                                            </label>
                                            <label class="btn btn-danger">
                                                <i class="bi bi-x-octagon"></i>
                                            </label>
                                        </div>
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="mt-5 d-flex justify-content-center">
            {!! $reported_comments->links() !!}
        </div>
    </div>

    <script>
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
    </script>
@endsection
