
<x-mail::message>
    @foreach ( $data as $key => $entry)
        
        {{ $key }}: {{ $entry }}
 
    @endforeach
</x-mail::message>
