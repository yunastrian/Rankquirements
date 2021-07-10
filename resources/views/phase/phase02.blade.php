@extends('phase')

@section('childphase')
    <div class="col-md-12">
        <div class="card mt-4">
            <form action="/project/phase/02" method="post">
                {{ csrf_field() }}
                <div class="card-header font-weight-bold bg-primary text-white">Phase {{ $phaseNumber }}: {{ $phaseName }}</div>

                <div class="card-body">
                    <b>Instruction:</b> <br>
                    Moderator selects criteria for deletion (tick the checkbox to delete)  
                </div>

                <div class="card-body">
                    <b>Criteria List:</b>
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Criteria</th>
                                <th scope="col">Delete?</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($criterias as $index => $criteria)
                            <tr>
                                <th scope="row" class="align-middle">{{ $index + 1 }}</th>
                                <td class="align-middle">{{ $criteria->name }}</td>
                                <td>
                                    <center>
                                        <input type="hidden" name="c{{ $criteria->id }}" value="0" />
                                        <input class="form-check-input" type="checkbox" name="c{{ $criteria->id }}" value="1">
                                    </center>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <input type="hidden" id="projectId" name="projectId" value="{{ $project->id }}">
                </div>

                <div class="card-footer bg-white">
                    <a class="btn btn-secondary" href="/project/{{ $project->id }}" role="button">Back to project page</a>
                    @if (($role == 1))
                        <button type="submit" class="btn btn-success float-right">Submit</button>
                    @endif
                </div>
            </form>
        </div>
    </div>
@endsection
