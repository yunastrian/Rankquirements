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
                        Select criterias those you think are most suitable for used (minimum 1, maximum 5) <br>
                        <i>make sure not to select the same criteria</i>
                    </div>

                    <div class="card-body">
                        <div class="row justify-content-around">
                            <div class="col-md-5">
                                <b>Criteria List:</b>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Criteria</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($criterias as $index => $criteria)
                                        <tr>
                                            <th scope="row" class="align-middle">{{ $index + 1 }}</th>
                                            <td class="align-middle">{{ $criteria->name }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        
                            <div class="col-md-5">
                                <b>Vote for criteria:</b>
                                <div class="form-group">
                                    <label for="criteria1">Criteria 1</label>
                                    <select class="form-control" id="criteria1" name="criteria1">
                                        <option></option>
                                        @foreach($criterias as $index => $criteria)
                                            <option value="{{ $criteria->id }}">{{ $criteria->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="criteria2">Criteria 2</label>
                                    <select class="form-control" id="criteria2" name="criteria2">
                                        <option></option>
                                        @foreach($criterias as $index => $criteria)
                                            <option value="{{ $criteria->id }}">{{ $criteria->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="criteria3">Criteria 3</label>
                                    <select class="form-control" id="criteria3" name="criteria3">
                                        <option></option>
                                        @foreach($criterias as $index => $criteria)
                                            <option value="{{ $criteria->id }}">{{ $criteria->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="criteria4">Criteria 4</label>
                                    <select class="form-control" id="criteria4" name="criteria4">
                                        <option></option>
                                        @foreach($criterias as $index => $criteria)
                                            <option value="{{ $criteria->id }}">{{ $criteria->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="criteria5">Criteria 5</label>
                                    <select class="form-control" id="criteria5" name="criteria5">
                                        <option></option>
                                        @foreach($criterias as $index => $criteria)
                                            <option value="{{ $criteria->id }}">{{ $criteria->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
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
