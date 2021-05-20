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
                <form action="/project/phase/05" method="post">
                    {{ csrf_field() }}
                    <div class="card-header font-weight-bold bg-primary text-white">Phase {{ $phaseNumber }}: {{ $phaseName }}</div>

                    <div class="card-body">
                        <b>Instruction:</b> <br>
                        Submit the score for each requirement on each criteria<br>
                        Rules:
                        <ul>
                            <li>0: requirement <b>does not affect</b> the criteria </li>
                            <li>1: requirement <b>have little effect</b> on the criteria </li>
                            <li>2: requirement <b>have moderate effect</b> on the criteria </li>
                            <li>3: requirement <b>have major effect</b> on the criteria </li>
                        <ul>
                    </div>

                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Requirement</th>
                                    @foreach($criterias as $criteria)
                                        <th scope="col">{{$criteria->name}} ({{ $criteria->weight }})</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($requirements as $indexR => $requirement)
                                <tr>
                                    <th scope="row" class="align-middle">R{{ $requirement->number + 1 }}</th>
                                    <td class="align-middle">{{ $requirement->name }}</td>
                                    @foreach($criterias as $indexC => $criteria)
                                        <td class="align-middle">
                                            <input type="hidden" name="requirement-{{ $indexR + 1 }}-{{ $indexC + 1 }}" value="{{ $requirement->id }}">
                                            <input type="hidden" name="criteria-{{ $indexR + 1 }}-{{ $indexC + 1 }}" value="{{ $criteria->id }}">
                                            <input type="number" class="form-control" required="required" id="score-{{ $indexR + 1 }}-{{ $indexC + 1 }}" name="score-{{ $indexR + 1 }}-{{ $indexC + 1 }}" min="0" max="3">
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
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
