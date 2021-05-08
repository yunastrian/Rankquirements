@extends('phase')

@section('childphase')
    @if($role == 1)
        <div class="col-md-12">
            <div class="card mt-4">
                <div class="card-header font-weight-bold bg-primary text-white">Phase {{ $phaseNumber }}: {{ $phaseName }}</div>

                <div class="card-body">
                    This is {{ $project->name }} in phase {{ $phaseNumber }} keduaaa <br>
                    the results is {{ $results }}
                </div>
                <div class="card-footer bg-white">
                    <a class="btn btn-secondary" href="/project/{{ $project->id }}" role="button">Back to project page</a>
                    <button type="submit" class="btn btn-success float-right">Submit</button>
                </div>
            </div>
        </div>
    @else
        <div class="col-md-12">
            <div class="card mt-4">
                <div class="card-header font-weight-bold bg-primary text-white">Phase {{ $phaseNumber }}: {{ $phaseName }}</div>

                <div class="card-body">
                    This is {{ $project->name }} in phase {{ $phaseNumber }} keduaaa
                </div>
                <div class="card-footer bg-white">
                    <a class="btn btn-secondary" href="/project/{{ $project->id }}" role="button">Back to project page</a>
                    <button type="submit" class="btn btn-success float-right">Submit</button>
                </div>
            </div>
        </div>
    @endif
@endsection
