<player-profile :user="user" inline-template>
    <div>
        <!-- Update Profile Photo -->
        @include('settings.player-profile.update-gamer-tag')

        <!-- Update Contact Information -->
{{--        @include('settings.player-profile.update-platform-selection')--}}
    </div>
</player-profile>
