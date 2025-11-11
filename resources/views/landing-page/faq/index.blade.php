<x-landing-page.layout>

    <div class="mx-auto h-[20rem] w-full bg-cover bg-center bg-no-repeat relative mt-14 lg:mt-20"
        style="background-image: url('./assets/faq.jpg')">
        <div class="absolute top-0 w-full h-full bg-black opacity-40"></div>
        <div
            class="container absolute flex flex-col flex-wrap items-center content-center justify-center p-4 mx-auto text-white -translate-x-1/2 -translate-y-1/2 left-1/2 top-1/2 md:py-28">
            <h1 class="text-5xl antialiased font-bold text-center md:text-6xl">
                Frequently Asked Questions
            </h1>
        </div>
    </div>
    <section class="flex flex-col items-center min-h-screen pb-10">
        <form action="{{route('landing-page.faq.index')}}" method="GET" class="w-full max-w-md px-5 mt-10 lg:px-0">
            <label for="default-search" class="mb-2 text-sm font-medium text-gray-900 sr-only">Search</label>
            <div class="relative">
                <div class="absolute inset-y-0 flex items-center pointer-events-none start-0 ps-3">
                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                    </svg>
                </div>
                <input type="search" id="default-search" name="search"
                    class="block w-full p-4 text-sm text-gray-900 border border-gray-300 rounded-lg ps-10 bg-gray-50 focus:ring-amber-500 focus:border-amber-500"
                    placeholder="Cari FAQ..." />
                <button type="submit"
                    class="text-white absolute end-2.5 bottom-2.5 bg-amber-600 hover:bg-amber-700 focus:ring-4 focus:outline-none focus:ring-amber-300 font-medium rounded-lg text-sm px-4 py-2">
                    Cari
                </button>
            </div>
        </form>
        <div class="container flex flex-col justify-center p-4 mx-auto md:p-8">
            <div class="flex flex-col gap-3 sm:px-8 lg:px-12 xl:px-32">
                @if ($faqs->isEmpty())
                <p class="font-semibold text-center">FAQ tidak ditemukan</p>
                @else
                @foreach ($faqs as $faq)
                <details
                    class="p-4 [&_svg]:open:-rotate-180 block bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-100">
                    <summary
                        class="flex items-center justify-between gap-4 list-none outline-none cursor-pointer focus:underline">
                        <div>{{$faq->question}}</div>
                        <div>
                            <svg class="transition-all duration-300 transform rotate-0" fill="none" height="20"
                                width="20" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2" viewBox="0 0 24 24">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </div>
                    </summary>

                    <div class="mt-5 answer-container"
                        data-route="{{route('landing-page.faq.show', ['faq' => $faq->id])}}">
                        {!! $faq->answer !!}
                    </div>
                </details>
                @endforeach
                @endif
            </div>
        </div>

        {{$faqs->links()}}
    </section>
    <a href="{{ $waLink }}?text=Hallo" target="_blank" rel="noopener noreferrer"
        class="fixed flex items-center justify-center w-16 text-white rounded-full shadow-2xl lg:w-20 aspect-square bg-gradient-to-tr from-green-500 to-emerald-400 bottom-5 lg:bottom-10 right-5 lg:right-10">
        <svg viewBox="0 0 24 24" fill="#fff" xmlns="http://www.w3.org/2000/svg"
            class="w-[40px] h-[40px] lg:w-[56px] lg:h-[56px]">
            <g id="SVGRepo_bgCarrier" strokeWidth="0"></g>
            <g id="SVGRepo_tracerCarrier" strokeLinecap="round" strokeLinejoin="round"></g>
            <g id="SVGRepo_iconCarrier">
                <path
                    d="M17.6 6.31999C16.8669 5.58141 15.9943 4.99596 15.033 4.59767C14.0716 4.19938 13.0406 3.99622 12 3.99999C10.6089 4.00135 9.24248 4.36819 8.03771 5.06377C6.83294 5.75935 5.83208 6.75926 5.13534 7.96335C4.4386 9.16745 4.07046 10.5335 4.06776 11.9246C4.06507 13.3158 4.42793 14.6832 5.12 15.89L4 20L8.2 18.9C9.35975 19.5452 10.6629 19.8891 11.99 19.9C14.0997 19.9001 16.124 19.0668 17.6222 17.5816C19.1205 16.0965 19.9715 14.0796 19.99 11.97C19.983 10.9173 19.7682 9.87634 19.3581 8.9068C18.948 7.93725 18.3505 7.05819 17.6 6.31999ZM12 18.53C10.8177 18.5308 9.65701 18.213 8.64 17.61L8.4 17.46L5.91 18.12L6.57 15.69L6.41 15.44C5.55925 14.0667 5.24174 12.429 5.51762 10.8372C5.7935 9.24545 6.64361 7.81015 7.9069 6.80322C9.1702 5.79628 10.7589 5.28765 12.3721 5.37368C13.9853 5.4597 15.511 6.13441 16.66 7.26999C17.916 8.49818 18.635 10.1735 18.66 11.93C18.6442 13.6859 17.9355 15.3645 16.6882 16.6006C15.441 17.8366 13.756 18.5301 12 18.53ZM15.61 13.59C15.41 13.49 14.44 13.01 14.26 12.95C14.08 12.89 13.94 12.85 13.81 13.05C13.6144 13.3181 13.404 13.5751 13.18 13.82C13.07 13.96 12.95 13.97 12.75 13.82C11.6097 13.3694 10.6597 12.5394 10.06 11.47C9.85 11.12 10.26 11.14 10.64 10.39C10.6681 10.3359 10.6827 10.2759 10.6827 10.215C10.6827 10.1541 10.6681 10.0941 10.64 10.04C10.64 9.93999 10.19 8.95999 10.03 8.56999C9.87 8.17999 9.71 8.23999 9.58 8.22999H9.19C9.08895 8.23154 8.9894 8.25465 8.898 8.29776C8.8066 8.34087 8.72546 8.403 8.66 8.47999C8.43562 8.69817 8.26061 8.96191 8.14676 9.25343C8.03291 9.54495 7.98287 9.85749 8 10.17C8.0627 10.9181 8.34443 11.6311 8.81 12.22C9.6622 13.4958 10.8301 14.5293 12.2 15.22C12.9185 15.6394 13.7535 15.8148 14.58 15.72C14.8552 15.6654 15.1159 15.5535 15.345 15.3915C15.5742 15.2296 15.7667 15.0212 15.91 14.78C16.0428 14.4856 16.0846 14.1583 16.03 13.84C15.94 13.74 15.81 13.69 15.61 13.59Z"
                    fill="#fff"></path>
            </g>
        </svg>
    </a>
    <button id="scrollTop"
        class="fixed z-50 hidden px-4 py-2 font-bold text-white rounded-lg cursor-pointer bottom-24 lg:bottom-32 lg:right-10 right-5 bg-amber-500 hover:bg-amber-700"
        aria-label="Scroll to top">
        ↑ Top
    </button>

    @section('script')
    <script type="text/javascript">
        $(document).ready(function() {
            $('.answer-container').each(function() {
                if ($(this).find('img').length > 0) {
                    var p = $(this).find('p')[0]
                    p = $(p)
                    var text = p.text()
                    p.text(text + "...")
                    $(this).empty()
                    $(this).html(p)
                    var route = $(this).data('route')
                    a = `<a class="text-blue-600 hover:underline" href=${route}>Lihat selengkapnya</a>`
                    $(this).append(a)
                }
            })
        })
    </script>
    @endsection

</x-landing-page.layout>
