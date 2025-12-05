@section('title', 'Data repositories')

<x-layout_main>
    <div class="main-content flex-col">

        <div class="detail-div !bg-primary-100">
            <h1>Data repositories</h1>
            <p class="max-w-(--breakpoint-md) pb-10">
                EPOS MSL currently provides access to MSL relevant data at the data repositories shown on this page.
                The fastest route to make your data discoverable by MSL, is to publish your data at one of these. Note
                that some of these are only accessible for
                researchers affiliated to the hosting institutes. Would you like to publish elsewhere? <a
                    href="{{ route('contact-us') }}" title="Contact us">Let us know</a>!
                We can then start working towards including data from your repository too.
            </p>
        </div>

        <div class="flex flex-wrap justify-center gap-4 max-w-(--breakpoint-lg) pb-20">
            @foreach ($repositories as $repo)
                @if ($repo['hide'] == 'false')
                    <div class="card bg-base-200 size-80 shadow-xl flex justify-between place-items-center flex-col p-2">
                        <div class="hover-interactive size-full">
                            <a href="{{ $repo['url'] }}" target="_blank"
                                title="{{ $repo['organization_display_name'] }}">
                                <img class="h-42 object-contain w-full"
                                    src={{ asset('images/' . str_replace(' ', '', $repo['image_url'])) }}
                                    alt={{ $repo['organization_display_name'] }} />
                                <h6 class="text-center">
                                    {{ $repo['organization_display_name'] }}
                                </h6>
                            </a>
                        </div>

                        <div class="py-4">
                            <a href="/data-access?organization[]={{ $repo['name'] }}">
                                <button class="btn btn-md btn-primary">View Datasets</button>
                            </a>
                        </div>

                    </div>
                @endif
            @endforeach
        </div>
    </div>

    <div class="flex flex-col justify-center items-center p-10 ">

    </div>
</x-layout_main>
