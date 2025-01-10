<x-mail::message>
New contact us form submission:

@foreach ( $formData as $key => $entry)    
{{ $key }}: {{ $entry }}
@endforeach
</x-mail::message>