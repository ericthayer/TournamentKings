@extends('layouts.tourneybot')

@section('content')
<section class="callout clean my-auto">
    <div class="container">
        <h2 id="tournament-kings">
            <img class="logo" src="media/images/ui/logo-tk-crown-dark.svg" alt="">
            <span class="sr-only">Tournament Kings</span>
        </h2>
        <p>Invite the Tourney Bot to your server to easily run tournaments, manage payments, and prove who's the best on your cord.</p>
        <a href="//discordapp.com/oauth2/authorize?client_id=546264541042966528&scope=bot&permissions=27760" class="btn-primary" style="min-width: 20em;">Invite Tourney Bot</a>
        <div class="flex pt-4">
            <a href="//discord.gg/hrp9rpH" class="btn-outline-light mx-2">Support Server</a>
            <a href="//discord.gg/h5B5Qzq" class="btn-outline-light mx-2">Join TK Server</a>
        </div>
    </div>
    </section>
@endsection
