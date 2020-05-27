@component('mail::message')
# Hello, {{ $lead->Name }}!

Thanks for your interest in Tournament Kings, we wanted to let you know we got your form submission.

We will be adding your name to our list of beta candidates.

Please know, that there will be several rounds of beta testing open to small groups, and not everyone will be able to participate in a beta.

However, we will let you know if and when you make it into a closed beta group.

Regards,<br>{{ config('app.name') }}
@endcomponent
