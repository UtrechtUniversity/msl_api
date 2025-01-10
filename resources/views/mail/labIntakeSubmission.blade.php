<x-mail::message>
New lab intake form submission:

@foreach ( $formData as $key => $entry)    
{{ $key }}: {{ $entry }}
@endforeach
</x-mail::message>