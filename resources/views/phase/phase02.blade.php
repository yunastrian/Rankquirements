@extends('phase')

@section('childphase')
    @if($role == 1)
    <!-- ADMIN VIEW -->
        <div class="col-md-12">
            <div class="card mt-4">
                <div class="card-header font-weight-bold bg-primary text-white">Phase {{ $phaseNumber }}: {{ $phaseName }}</div>

                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Name</th>
                                <th scope="col">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($users as $index => $user)
                            <tr>
                                <th scope="row" class="align-middle">{{ $index + 1 }}</th>
                                <td class="align-middle">{{ $user['name'] }}</td>
                                <td class="align-middle">{{ $user['status'] }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer bg-white">
                    <a class="btn btn-secondary" href="/project/{{ $project->id }}" role="button">Back to project page</a>
                </div>
            </div>
        </div>
    @else
    <!-- MEMBER VIEW -->
        <div class="col-md-12">
            <div class="card mt-4">
                <form action="/project/phase/02" method="post">
                    {{ csrf_field() }}
                    <div class="card-header font-weight-bold bg-primary text-white">Phase {{ $phaseNumber }}: {{ $phaseName }}</div>

                    <div class="card-body">
                        <b>Instruction:</b> <br>
                        Moderator Eliminate
                    </div>

                    <div class="card-body">
                        <!-- To do -->

                        <input type="hidden" id="projectId" name="projectId" value="{{ $project->id }}">
                    </div>

                    <div class="card-footer bg-white">
                        <a class="btn btn-secondary" href="/project/{{ $project->id }}" role="button">Back to project page</a>
                        <button type="submit" class="btn btn-success float-right">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
@endsection
