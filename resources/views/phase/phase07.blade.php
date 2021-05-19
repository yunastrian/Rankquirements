@extends('phase')

@section('childphase')
    <div class="col-md-12">
        <div class="card mt-4">
            <form action="/project/phase/07" method="post">
                {{ csrf_field() }}
                <div class="card-header font-weight-bold bg-primary text-white">Phase {{ $phaseNumber }}: {{ $phaseName }}</div>

                <div class="card-body">
                    <b>Instruction:</b> <br>
                    Moderator fill new score from discussion result
                </div>

                <div class="card-body">
                    <b>Score results:</b>
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Requirement</th>
                                @foreach($criterias as $criteria)
                                    <th scope="col">{{$criteria->name}} ({{$criteria->weight}})</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($requirements as $indexR => $requirement)
                            <tr>
                                <th scope="row" class="align-middle">R{{ $requirement->number + 1 }}</th>
                                <td class="align-middle">{{ $requirement->name }}</td>
                                @foreach($criterias as $indexC => $criteria)
                                    @foreach($scores as $score)
                                        @if (($score->idRequirement == $requirement->id) && ($score->idCriteria == $criteria->id))
                                            <td class="align-middle {{ $score->status == 1 ? 'table-success' : ''  }}">{{ $score->score }}</td>
                                        @endif
                                    @endforeach  
                                @endforeach
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    @if (($role == 1) && (!$isAllApproved))
                        <b>Fill unapproved score:</b>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Requirement</th>
                                    <th scope="col">Criteria</th>
                                    <th scope="col">New Score</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($requirements as $indexR => $requirement)
                                @foreach($criterias as $indexC => $criteria)
                                    @foreach($scores as $score)
                                        @if (($score->idRequirement == $requirement->id) && ($score->idCriteria == $criteria->id) && ($score->status == 0))
                                            <tr>
                                                <th scope="row" class="align-middle">R{{ $requirement->number + 1 }}</th>
                                                <td class="align-middle">{{ $requirement->name }}</td>
                                                <td class="align-middle">{{ $criteria->name }} ({{ $criteria->weight }})</td>
                                                <td class="align-middle">
                                                    <input type="hidden" name="scoreId[]" value="{{ $score->id }}">
                                                    <input type="number" class="form-control" required="required" name="scoreVal[]" min="0" max="3" step="0.01">
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach  
                                @endforeach
                            @endforeach
                            </tbody>
                        </table>
                    @endif

                    <input type="hidden" id="projectId" name="projectId" value="{{ $project->id }}">
                </div>

                <div class="card-footer bg-white">
                    <a class="btn btn-secondary" href="/project/{{ $project->id }}" role="button">Back to project page</a>
                    @if (($role == 1) && (!$isAllApproved))
                        <button type="submit" class="btn btn-success float-right">Submit</button>
                    @endif
                </div>
            </form>
        </div>
    </div>
@endsection
