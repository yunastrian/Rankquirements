@extends('layouts.app')

@section('content')
<div class="container">
    @isset(request()->msg)
        @if( request()->get('msg') == 1 )
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                Requirement added successfully
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
    @endisset
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header font-weight-bold">Software Requirements</div>

                <div class="card-body">
                    @if(count($requirements) == 0)
                        There is no requirements
                    @else
                    <table class="table table-striped">
                        <thead>
                            <tr>
                            <th scope="col">#</th>
                            <th scope="col">Requirement</th>
                            <th scope="col">Score</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($requirements as $requirement)
                            <tr>
                                <th scope="row" class="align-middle">R{{ $requirement->number + 1 }}</th>
                                <td class="align-middle">{{ $requirement->name }}</td>
                                <td class="align-middle">{{ $requirement->score }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    @endif
                    
                    <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#exampleModal">
                        Add Requirement
                    </button>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header font-weight-bold">Participants</div>

                <div class="card-body">
                    <h5>Moderator:</h5>
                    <ul>
                        <li>Coffee</li>
                    </ul>

                    <h5 class="mt-4">Members:</h5>
                    <ul>
                        <li>Coffee</li>
                        <li>Coffee</li>
                        <li>Coffee</li>
                    </ul>

                    <button type="button" class="btn btn-primary mt-4" data-toggle="modal" data-target="#memberModal">
                        Add Member
                    </button>
                </div>
            </div>
        </div>

        <!-- Requirement Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <form action="/requirement/add" method="post">
                        {{ csrf_field() }}
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Add Requirement</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="projectName">Requirement statement</label>
                                <input type="text" class="form-control" id="requirementName" required="required" name="requirementName">
                            </div>
                            <input type="hidden" id="projectId" name="projectId" value="{{ $id }}">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-success">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Member Modal -->
        <div class="modal fade" id="memberModal" tabindex="-1" aria-labelledby="memberModal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <form action="/project/addMember" method="post">
                        {{ csrf_field() }}
                        <div class="modal-header">
                            <h5 class="modal-title" id="memberModal">Add Member</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="projectName">Pick user</label>
                                <input type="text" class="form-control" id="requirementName" required="required" name="requirementName">
                            </div>
                            <input type="hidden" id="projectId" name="projectId" value="{{ $id }}">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-success">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
