@extends('layouts.app')

@section('content')
<?php
    $tournament_link = '/tournaments/' . $tournament->id;
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><h1>Delete Tournament</h1></div>

                <div class="card-body">
                    <form method="POST" action="{{ $tournament_link }}">
                        @csrf
                        @method('DELETE')

                        <p>Are you sure you want to delete this tournament?</p>
                        <h6>Name: <span class="text-muted">{{ $tournament->name }}</span> </h6>
                        <h6>Game: <span class="text-muted">{{ $tournament->gameType->name }}</span> </h6>
                        <h6>Type: <span class="text-muted">{{ $tournament_types[$tournament->tournament_type] }}</span> </h6>
                        <h6>Notes: <span class="text-muted">{{ $tournament->description}}</span> </h6>
                        <h6>Start Date/Time: <span class="text-muted">{{ date('m/d/Y g:i A', strtotime($tournament->start_datetime)) }}</span> </h6>
                        <h6>Available Slots: <span class="text-muted">{{ $tournament->total_slots }}</span> </h6>
                        <h6>Admin: <span class="text-muted">{{ $tournament->createdByPlayer->gamer_tag }}</span> </h6>
                        <br>

                        <div class="form-group row mb-0">
                            <div class="col-md-6">
                                <a class="btn btn-primary" href="{{ $tournament_link }}" role="button">Cancel</a>
                                &nbsp;&nbsp;
                                <button type="submit" class="btn btn-primary">
                                    Delete
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
