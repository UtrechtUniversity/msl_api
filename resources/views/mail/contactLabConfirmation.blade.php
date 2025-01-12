<x-mail::message>
# Dear {{ $formData['firstName'] }}!

Thank you for using the lab contact page at MSL. Your message will be forwarded to the laboratory contact person.


With kind regards,

{{ config('app.name') }}
</x-mail::message>