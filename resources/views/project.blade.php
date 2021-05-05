@extends('layouts.app')

@section('content')
<div class="container">
    @isset(request()->msg)
        @if( request()->get('msg') == 1 )
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                Project added successfully
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
    @endisset
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header font-weight-bold">Perangkat Lunak Ujian Daring</div>

                <div class="card-body">
                    <!-- @if(count($projects) == 0)
                        There is no project
                    @else -->
                    <table class="table table-striped">
                        <thead>
                            <tr>
                            <th scope="col">#</th>
                            <th scope="col">Requirement</th>
                            <th scope="col">Score</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row" class="align-middle">R1</th>
                                <td class="align-middle">Sistem dapat menampilkan soal</td>
                                <td class="align-middle">3.56</td>
                            </tr>
                        <!-- @foreach($projects as $index => $project)
                            <tr>
                                <th scope="row" class="align-middle">{{ $index + 1 }}</th>
                                <td class="align-middle">{{ $project['name'] }}</td>
                                <td class="align-middle">{{ $project['role'] }}</td>
                                <td class="align-middle"><a class="btn btn-primary" href="/project/{{ $project['id'] }}" role="button">Open</a></td>
                            </tr>
                        @endforeach -->
                        </tbody>
                    </table>
                    <!-- @endif -->
                    
                    <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#exampleModal">
                        Add Requirement
                    </button>
                </div>
            </div>
        </div>
        <!-- Modal -->
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
                                <label for="projectName">Requirement Statement</label>
                                <input type="text" class="form-control" id="projectName" required="required" name="projectName">
                            </div>
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
