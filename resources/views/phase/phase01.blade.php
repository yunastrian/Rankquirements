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
                <form action="/project/phase/01" method="post">
                    {{ csrf_field() }}
                    <div class="card-header font-weight-bold bg-primary text-white">Phase {{ $phaseNumber }}: {{ $phaseName }}</div>

                    <div class="card-body">
                        <b>Instruction:</b> <br>
                        Submit criterias according to your wishes related to the project (minimum 3, maximum 5)
                    </div>

                    <div class="card-body">
                        <div class="form-group">
                            <label for="projectName">Criteria 1</label>
                            <input type="text" class="form-control" id="criteria1" required="required" name="criteria1">
                        </div>
                        <div class="form-group">    
                            <label for="projectName">Criteria 2</label>
                            <input type="text" class="form-control" id="criteria2" required="required" name="criteria2">
                        </div>
                        <div class="form-group">        
                            <label for="projectName">Criteria 3</label>
                            <input type="text" class="form-control" id="criteria3" required="required" name="criteria3">
                        </div>
                        <div class="form-group"> 
                            <label for="projectName">Criteria 4</label>
                            <input type="text" class="form-control" id="criteria4" name="criteria4">
                        </div>
                        <div class="form-group">
                            <label for="projectName">Criteria 5</label>
                            <input type="text" class="form-control" id="criteria5" name="criteria5">
                        </div>
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
