@component('mail::message')
# Introduction

The body of your message.

@component('mail::button', ['url' => $url.'/update-password?token='.$token])
Button Text
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
