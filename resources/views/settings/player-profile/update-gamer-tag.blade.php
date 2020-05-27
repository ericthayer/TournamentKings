<update-gamer-tag :user="user" inline-template>
    <div class="card card-default">
        <div class="card-header">{{__('Gamer Tag')}}</div>

        <div class="card-body">
            <!-- Success Message -->
            <div class="alert alert-success" v-if="form.successful">
                {{__('Your player profile has been updated!')}}
            </div>

            <form role="form">
                <!-- Gamer Tag -->
                <div class="form-group row">
                    <label class="col-md-4 col-form-label text-md-right">{{__('Gamer Tag')}}</label>

                    <div class="col-md-6">
                        <input type="text" class="form-control" name="gamer_tag" v-model="form.gamer_tag" :class="{'is-invalid': form.errors.has('gamer_tag')}">

                        <span class="invalid-feedback" v-show="form.errors.has('gamer_tag')">
                            @{{ form.errors.get('gamer_tag') }}
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
</update-gamer-tag>
