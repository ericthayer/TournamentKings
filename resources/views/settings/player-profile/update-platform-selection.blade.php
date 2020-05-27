<update-platform-selection :user="user" inline-template>
    <div class="card card-default">
        <div class="card-header">{{__('Platform Selection')}}</div>

        <div class="card-body">
            <!-- Success Message -->
            <div class="alert alert-success" v-if="form.successful">
                {{__('Your contact information has been updated!')}}
            </div>

            <form role="form">
                <!-- Name -->
                <div class="form-group row">
                    <label class="col-md-4 col-form-label text-md-right">{{__('Platform')}}</label>

                    <div class="col-md-6">
                        <select type="text" class="form-control" name="platform_type_id" :class="{'is-invalid': form.errors.has('platform_type_id')}">

                            <option v-for="platform_type in platform_types" value="key">name</option>
                        </select>
                        <span class="invalid-feedback" v-show="form.errors.has('platform_type_id')">
                            @{{ form.errors.get('platform_type_id') }}
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
</update-platform-selection>
