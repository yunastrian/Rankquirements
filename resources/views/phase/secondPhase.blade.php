@extends('phase')

@section('childphase')
<div class="col-md-12">
    <div class="card mt-4">
        <div class="card-header font-weight-bold bg-primary text-white">Phase {{ $phaseNumber }}: {{ $phaseName }}</div>

        <div class="card-body">
            This is {{ $project->name }} in phase {{ $phaseNumber }} kedua cuy
        </div>
        <div class="modal-footer">
            <a class="btn btn-secondary" href="/project/{{ $project->id }}" role="button">Back to project page</a>
            <button type="submit" class="btn btn-success">Submit</button>
        </div>
    </div>
</div>
@endsection
