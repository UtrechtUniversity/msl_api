@props([
    'entry' => [],
    'textSize' => 'sm',
])

<div class="w-full py-2">
    <table class="table-fixed w-full">
        <tbody>
            <tr>
                <td class="text-{{ $textSize }} p-0">{{ $entry['name'] }}</td>
            </tr>
            <tr>
                <td class="text-{{ $textSize }} p-0">{{ $entry['nameType'] }}</td>
            </tr>
            <tr>
                <td class="w-20 sm:w-40 text-{{ $textSize }} p-0">
                    @foreach ($entry['nameIdentifiers'] as $index => $idWithMetadata)
                        @if ($idWithMetadata['isURL'])
                            <a class="underline hover-interactive" href="{{ $idWithMetadata['value'] }}">
                                {{ $idWithMetadata['value'] }}
                            </a>
                        @else
                            {{ $idWithMetadata }}
                        @endif
                        @if ($index + 1 < count($entry['nameIdentifiers']))
                            <span class="mx-1">|</span>
                        @endif
                    @endforeach
                </td>
            </tr>

            <tr>
                <td class="w-20 sm:w-40 text-{{ $textSize }} p-0">
                    @foreach ($entry['affiliations'] as $index => $affiliation)
                        @if (!empty($affiliation['name']))
                            {{ $affiliation['name'] }}
                        @endif
                        @if ($index + 1 < count($entry['affiliations']))
                            |
                        @endif
                    @endforeach
                </td>
            </tr>

        </tbody>
    </table>
</div>
