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


        <h2 class=" pt-6 pt-20">Nested Windows</h2>
        <div class="sub-content bg-primary-200">
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


    </div>

</x-layout_main>
