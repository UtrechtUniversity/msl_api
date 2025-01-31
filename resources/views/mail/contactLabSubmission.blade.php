<x-mail::message>
Dear MSL laboratory contact,

An user of the MSL data catalogue has submitted the following contact request for your laboratory:

@foreach ( $formData as $key => $entry)    
{{ $key }}: {{ $entry }}
@endforeach


With kind regards,

{{ config('app.name') }}
</x-mail::message>