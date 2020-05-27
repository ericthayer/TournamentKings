<update-player-information :user="user" inline-template>
    <div class="card card-default">

        <div class="card-header">{{__('Player Information')}}</div>

        <div class="card-body">
            <!-- Success Message -->
            <div class="alert alert-success" v-if="form.successful">
                {{__('Your contact information has been updated!')}}
            </div>

            <form role="form">
                <!-- Name -->

                <div class="form-group row">
                    <label class="col-md-4 col-form-label text-md-right">{{__('Gamer Tag')}}</label>

                    <div class="col-md-6">
                        <input type="text" class="form-control" name="gamer_tag" v-model="form.gamer_tag" :class="{'is-invalid': form.errors.has('gamer_tag')}">

                        <span class="invalid-feedback" v-show="form.errors.has('gamer_tag')">
                        @{{ form.errors.get('gamer_tag') }}
                    </span>
                    </div>
                </div>

                <!-- E-Mail Address -->
                <div class="form-group row">
                    <label class="col-md-4 col-form-label text-md-right">{{__('Gaming Platform')}}</label>

                    <div class="col-md-6">
                        <select id="platform_type_id" name="platform_type_id"  :class="{'is-invalid': form.errors.has('platform_type_id'), 'form-control': true}" v-model="form.platform_type_id" >
                            <option value="">{{ __('tournaments.please-select') }}</option>
                            @foreach ($platform_types as $platform_type)
                                <option value="{{ $platform_type->id }}" {{ (old('platform_type_id', $tournament->platform_type_id) == $platform_type->id ? "selected" : "") }}>{{ $platform_type->name}}</option>
                            @endforeach
                        </select>

                        <span class="invalid-feedback" v-show="form.errors.has('platform_type_id')">
                        @{{ form.errors.get('platform_type_id') }}
                    </span>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-4 col-form-label text-md-right">{{__('Game')}}</label>
                    <div class="col-md-6">
                        <select id="game_type_id" name="game_type_id"  :class="{'is-invalid': form.errors.has('game_type_id'), 'form-control': true}" v-model="form.game_type_id" >
                            <option value="">{{ __('tournaments.please-select') }}</option>
                            @foreach ($game_types as $game_type)
                                <option value="{{ $game_type->id }}" {{ (old('game_type_id', $tournament->game_type_id) == $game_type->id ? "selected" : "") }}>{{ $game_type->name}}</option>
                            @endforeach
                        </select>

                        <span class="invalid-feedback" v-show="form.errors.has('game_type_id')">
                            @{{ form.errors.get('game_type_id') }}
                        </span>
                    </div>
                </div>


                <!-- Update Button -->
                <div class="form-group row mb-0">
                    <div class="col-md-6 offset-md-4">
                        <button type="submit" class="btn btn-primary"
                                @click.prevent="update"
                                :disabled="form.busy">

                            {{__('Update')}}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</update-player-information>