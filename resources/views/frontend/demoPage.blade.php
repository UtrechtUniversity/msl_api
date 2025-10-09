@section('title', 'demoPage')
<x-layout_main>
    <div class="main-content flex-col">

        this is the main-content. The main content of most of the pages are wrapped in this class

        <div class="sub-content">
            <p>this is sub content. Any child div with content must be in this one. It determines the responsive behaviour. As you can see it has a max width regardless on monitor width</p>
        </div>

        <div class="sub-content">
            <h1>main title on a page</h1>
            <h2>sub2-Title</h2>
            <h3>sub3-title</h3>
            <h4>sub4-title</h4>
            <h5>sub5-title</h5>
            <h6>sub6-title</h6>
            <p>this is text, which is always justified and with padding, unless indicated differently</p>
        </div>

        <div class="py-20 sub-content ">
            <h2>interactive elements</h2>
            <p class="hover-interactive ">anthing that is interactive must have this utility class. Further implementation can be read under links: group-hover</p>
            <p>Assumption: our users have various degrees of tech literacy and it is important to make interactive elements explicit</p>
        </div>

        <div class="py-20 sub-content">
            <h2>buttons</h2>
            <p>all buttons come with primary style and secondary on hover, unless indicated differently</p>
            <div class="flex items-center flex-col">
                <button class="btn">Hover me</button>
            </div>
        </div>

        <div class="pt-20 sub-content">
            <h2>Links</h2>
            <div class="flex items-center flex-col">
                <a href="">Links like this do not have an underline, because the buttons in nav and elsewhere are wrapped as such</a>
                <a href="" class="underline underline-offset-1">unless specified</a>
            </div>


            <h3 class="pt-6">links in text</h3>
            <div class="flex items-center justify-center">
                <p>
                    This is a link <a href="" class="hover-interactive underline">with interactive utility</a>, which works well in a text
                </p>
            </div>
            

            <h3 class="pt-6">containers in a tag: tailwind group-hover on links</h3>
            <div>
                <h4 class="pt-6">Preferred approach</h4>
                <p class="pt-4">The "group" marker is not an utility and cannot be used with "@apply" in the "@layer base" in css. So the "group" 
                    as a class must be called extra. this results to the desired behaviour that the child is marked interactive when the "a" tagged container is hovered.
                Try yourself</p>
                <div class="flex items-center justify-center">
                    <a href="" class="group">parent ----- <div class="inline-block hover-interactive-group underline">This is the child</div> ----- parent</a>
                </div>
                
                <h4 class="pt-6">Less preferred</h4>
                <p class="pt-4">When an "a" tag is used it could have more invisible free space when hovered, which is undesireable behaviour. Like here:</p>
                <div class="flex items-center justify-center">
                    <a href="" class="hover-interactive ">parent ----- <div class="inline-block underline">This is the child</div> ----- parent</a>
                </div>
                
                <p class="pt-4">If the child element has the interactive class it is not clear that an interaction is possible until the child is hovered. This is also undesireable. Like here:</p>
                <div class="flex items-center justify-center">
                    <a href="" class="">parent ----- <div class="inline-block hover-interactive">This is the child</div> ----- parent</a>
                </div>

            </div>
        </div>
        
        <h2 class="pt-20">tab links </h2>
        <div class="tabLinksParent">
            @include('components.tab-links',[
                'categoryName'  => 'Laboratories',
                'routes'        => array(
                        'Map'   => route("labs-map"),
                        'List'  => route("labs-list")
                ),
            ])
            @include('components.tab-links',[
                'categoryName'  => 'Equipment',
                'routes'        => array(
                        'Map'   => route("equipment-map"),
                        'List'  => route("equipment-list"),
                ),
                'routeActive'   => route("equipment-map")

            ])
        </div>

        <h2 class=" pt-6 pt-20">Dividers</h2>
        <div class="sub-content flex flex-col gap-4">
            <h3 class="pt-2">primary-bg</h3>
            <p>norm</p>
            <div class="sub-content flex">
                <div class="sub-content content-divide-y px-4">
                    <p>this is text</p>
                    <p>this is text</p>
                    <p>this is text</p>
                </div>
                <div class="sub-content content-divide-y bg-primary-200 px-4">
                    <p>this is text</p>
                    <p>this is text</p>
                    <p>this is text</p>
                </div>
                <div class="sub-content content-divide-y bg-primary-300 px-4">
                    <p>this is text</p>
                    <p>this is text</p>
                    <p>this is text</p>
                </div>
                <div class="sub-content content-divide-y-secondary px-4">
                    <p>this is text</p>
                    <p>this is text</p>
                    <p>this is text</p>
                </div>
                <div class="sub-content content-divide-y-secondary bg-primary-200 px-4">
                    <p>this is text</p>
                    <p>this is text</p>
                    <p>this is text</p>
                </div>
                <div class="sub-content content-divide-y-secondary bg-primary-300 px-4">
                    <p>this is text</p>
                    <p>this is text</p>
                    <p>this is text</p>
                </div>
            </div>
            <p>light</p>
            <div class="sub-content flex">
                <div class="sub-content content-divide-y-light px-4">
                    <p>this is text</p>
                    <p>this is text</p>
                    <p>this is text</p>
                </div>
                <div class="sub-content content-divide-y-light bg-primary-200 px-4">
                    <p>this is text</p>
                    <p>this is text</p>
                    <p>this is text</p>
                </div>
                <div class="sub-content content-divide-y-light bg-primary-300 px-4">
                    <p>this is text</p>
                    <p>this is text</p>
                    <p>this is text</p>
                </div>
                <div class="sub-content content-divide-y-light-secondary px-4">
                    <p>this is text</p>
                    <p>this is text</p>
                    <p>this is text</p>
                </div>
                <div class="sub-content content-divide-y-light-secondary bg-primary-200 px-4">
                    <p>this is text</p>
                    <p>this is text</p>
                    <p>this is text</p>
                </div>
                <div class="sub-content content-divide-y-light-secondary bg-primary-300 px-4">
                    <p>this is text</p>
                    <p>this is text</p>
                    <p>this is text</p>
                </div>
            </div>
            <h3>secondary bg</h3>
            <p>norm</p>
            <div class="sub-content flex">
                <div class="sub-content content-divide-y bg-secondary-100 px-4">
                    <p>this is text</p>
                    <p>this is text</p>
                    <p>this is text</p>
                </div>
                <div class="sub-content content-divide-y bg-secondary-200 px-4">
                    <p>this is text</p>
                    <p>this is text</p>
                    <p>this is text</p>
                </div>
                <div class="sub-content content-divide-y bg-secondary-300 px-4">
                    <p>this is text</p>
                    <p>this is text</p>
                    <p>this is text</p>
                </div>
                <div class="sub-content content-divide-y-secondary bg-secondary-100 px-4">
                    <p>this is text</p>
                    <p>this is text</p>
                    <p>this is text</p>
                </div>
                <div class="sub-content content-divide-y-secondary bg-secondary-200 px-4">
                    <p>this is text</p>
                    <p>this is text</p>
                    <p>this is text</p>
                </div>
                <div class="sub-content content-divide-y-secondary bg-secondary-300 px-4">
                    <p>this is text</p>
                    <p>this is text</p>
                    <p>this is text</p>
                </div>
            </div>
            <p>light</p>
            <div class="sub-content flex">
                <div class="sub-content content-divide-y-light bg-secondary-100  px-4">
                    <p>this is text</p>
                    <p>this is text</p>
                    <p>this is text</p>
                </div>
                <div class="sub-content content-divide-y-light bg-secondary-200 px-4">
                    <p>this is text</p>
                    <p>this is text</p>
                    <p>this is text</p>
                </div>
                <div class="sub-content content-divide-y-light bg-secondary-300 px-4">
                    <p>this is text</p>
                    <p>this is text</p>
                    <p>this is text</p>
                </div>
                <div class="sub-content content-divide-y-light-secondary bg-secondary-100 px-4">
                    <p>this is text</p>
                    <p>this is text</p>
                    <p>this is text</p>
                </div>
                <div class="sub-content content-divide-y-light-secondary bg-secondary-200 px-4">
                    <p>this is text</p>
                    <p>this is text</p>
                    <p>this is text</p>
                </div>
                <div class="sub-content content-divide-y-light-secondary bg-secondary-300 px-4">
                    <p>this is text</p>
                    <p>this is text</p>
                    <p>this is text</p>
                </div>
            </div>
        </div>


        <h2 class=" pt-6 pt-20">Nested Windows</h2>
        <div class="sub-content bg-primary-200">
            <h3>primary (for main pages)</h3>
            <p>this is a window highlighting information</p>

            <div class="bg-primary-100 h-90 m-10">
                <p>match the background to make it visually lighter. it pops</p>
                <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Beatae iste ea repellat hic repudiandae aut vel corporis sunt officia sed atque perferendis, adipisci labore? Est soluta eum natus doloremque iure.</p>
            </div>

            <div class="bg-primary-300 h-90 m-10">
                <p>this works for interactable headlines/dropdowns, but can be too heavy for too much text:</p>
                <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Eveniet commodi exercitationem eaque nisi odit nostrum beatae, voluptatibus tempore rerum totam hic debitis? Illum at vel illo nemo porro natus soluta.</p>
            </div>
        </div>
        <div class="sub-content bg-secondary-200 text-secondary-900">
            <h3>secondary (for admin pages)</h3>
            <h4>other use cases need to be explored. It could be that it might be useful for highlighting certain info which do not fit into 
                info, alert, error, warning
            </h4>
            <p>this is a window highlighting information</p>

            <div class="bg-secondary-100 h-90 m-10">
                <p>match the background to make it visually lighter. it pops</p>
                <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Beatae iste ea repellat hic repudiandae aut vel corporis sunt officia sed atque perferendis, adipisci labore? Est soluta eum natus doloremque iure.</p>
            </div>

            <div class="bg-secondary-400 h-90 m-10">
                <p>this works for interactable headlines/dropdowns, but can be too heavy for too much text:</p>
                <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Eveniet commodi exercitationem eaque nisi odit nostrum beatae, voluptatibus tempore rerum totam hic debitis? Illum at vel illo nemo porro natus soluta.</p>
            </div>
        </div>

        <h2 class="pt-6 pt-20">word cards</h2>
        <div class="sub-content  bg-primary-200 w-full p-10">

            <h3>No interaction</h3>
            <p>just the class 'word-card'</p>
            <div class="word-card-parent bg-primary-100">
                    <div class="word-card">this is a word-card</div>
                    <div class="word-card">in word-card-parent</div>
                    <div class="word-card">without width limit it can actually take a lot of space, better avoid by using component below</div>
            </div>
            <br>
            <h3>word card component</h3>
            <p>the hover behaviour is different because on click nothing happens. For this hover-neutral is used</p>
            <div class="word-card-parent bg-primary-100 h-40">
                @include('components.word-card',[
                    'word' => 'this is a word'
                ])
                @include('components.word-card',[
                    'word' => 'extreme length Lorem ipsum, dolor sit amet consectetur adipisicing elit. Quos eligendi numquam voluptatum quaerat vel qui eveniet! Earum voluptas, deleniti debitis numquam aperiam inventore nobis, explicabo accusamus nisi eius animi consequuntur.'
                ])
                @include('components.word-card',[
                    'word' => 'this is a word'
                ])
                @include('components.word-card',[
                    'word' => 'this is a word'
                ])
                @include('components.word-card',[
                    'word' => 'this is a word'
                ])
                @include('components.word-card',[
                    'word' => 'this is a word'
                ])
                @include('components.word-card',[
                    'word' => 'longWordlongWordlongWordlongWordlongWordlongWordlongWordlongWord'
                ])

            </div>
        </div>

        <h2 class="pt-6 pt-20">Window Tabs</h2>
        <div class="sub-content">
            <p>here an example on how to condense more information into a tab list. Also allows html tags</p>
            @include('components.tab-list',[
                'allTabs' => array(
                    'First Tab' => [
                        'content' => 'Lorem ipsum dolor sit amet consectetur, adipisicing elit. Rem harum perspiciatis eum laboriosam nostrum ipsam perferendis quae aspernatur itaque recusandae aut, totam ea sit sapiente numquam voluptatem molestiae ducimus unde.',
                        'id' => 'id1'
                    ],
                    'Second Tab' => [
                        'content' => 'Lorem ipsum dolor sit amet consectetur, adipisicing elit. Rem harum perspiciatis eum laboriosam nostrum ipsam perferendis quae aspernatur itaque recusandae aut, totam ea sit sapiente numquam voluptatem molestiae ducimus unde.',
                        'id' => 'id2'
                    ],
                    'Third Tab html tags' => [
                        'content' => '<h2>This is a title</h2> 
                                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. 
                                        Doloremque reprehenderit ex a, corrupti totam aspernatur porro 
                                        assumenda modi aut labore! Qui vel corporis debitis, nesciunt 
                                        odio quibusdam vitae enim doloremque!</p>',
                        'id' => 'id3'
                    ]
                ),
                'checkedElementId' => 'id1'
            ])

        </div>




        <h2 class="pt-6 pt-20">no mobile view</h2>
        <div class="sub-content h-90 bg-primary-200 w-full p-4">
                <p class="">decrease size to see effects</p>
                    {{-- a general no small width view notification --}}
                @include('components.no_mobile_view', [
                    'breakpoint' => 'md'
                ])

                <div class="hidden md:block m-4 bg-primary-100">
                    <p>to be coupled with a div which should not be visible on small screen. like this one for example. Reduce the screen size to see. 
                        The classes in this div are required for it to work. note the same breakpoint as for the component above</p>
                </div>
        </div>

        <h2 class="pt-6 pt-20">Main Window with sidemenu</h2>
        <h3>responsive</h3>        
        {{-- <div> --}}
            <div class="sub-content-wide flex place-content-center w-full h-screen">
                <div class="drawer md:drawer-open ">
                    <input id="my-drawer-2" type="checkbox" class="drawer-toggle" />
                    <div class="drawer-content bg-secondary-100 flex">
                        {{-- content here --}}
                        <div class="w-10 bg-secondary-200 md:hidden relative opacity-75 hover:opacity-100">
                            <label for="my-drawer-2" class="btn drawer-button w-full h-full flex flex-col justify-center "
                            >
                            <p 
                            class=""
                            style="writing-mode: sideways-lr;" >
                                click here to see filters
                              </p>
                        </div>
                        <div class="w-full min-h-full bg-primary-200 pl-4">
                            we are using a daisyUI component for this
                        </div>
    
                    </div>
                    <div class="drawer-side ">
                        <label for="my-drawer-2" aria-label="close sidebar" class="drawer-overlay"></label>
                        <ul class="menu bg-secondary-100 min-h-full w-80 p-4 text-secondary-900">
                        <!-- Sidebar content here -->
                        <li><a>Sidebar Item 1</a></li>
                        <li><a>Sidebar Item 2</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        {{-- </div> --}}


        {{-- </div> --}}


    </div>


</x-layout_main>
