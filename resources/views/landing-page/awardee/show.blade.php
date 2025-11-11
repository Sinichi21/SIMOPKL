<x-landing-page.layout>

    <div class="mx-auto h-[10rem] w-full bg-cover bg-center bg-no-repeat relative mt-14 lg:mt-20">
        <div class="absolute top-0 w-full h-full"></div>
        <div
            class="container flex flex-col flex-wrap content-center items-center left-1/2 -translate-x-1/2 top-1/2 -translate-y-1/2 justify-center p-4 mx-auto md:py-28 text-black absolute">
            <h1 class="text-4xl antialiased font-bold text-center md:text-5xl">
                Profil Awardee
            </h1>
        </div>
    </div>

    <section class="pb-10 flex flex-col items-center justify-center">
        <div class="flex flex-col md:flex-row items-center justify-center flex-wrap w-full">
            <!-- Image Container -->
            <div class="flex-shrink-0 w-full md:w-1/2 px-5 lg:px-0 mt-2 text-center">
                <img src="{{ $user->pp_url ? asset('storage/' . $user->pp_url) : asset('img/undraw_profile.svg') }}"
                    alt="{{ $user->awardee->username }}" class="h-72 mx-auto mb-4 md:mb-0">
            </div>
            <!-- Form Container -->
            <div class="flex flex-col w-full md:w-1/2 px-5 lg:px-0 mt-2">
                <form class="space-y-4 md:space-y-6">
                    <h2 class="block mb-2 text-3xl font-medium text-black">{{ $user->awardee->username }}</h2>
                    <div>
                        <label for="fullname" class="block mb-2 text-sm font-medium text-black">Nama Lengkap</label>
                        <input type="text" name="fullname" id="fullname"
                            class="bg-gray-50 border border-gray-300 text-black rounded-lg block w-2/3 p-2.5"
                            Value="{{ $user->awardee->fullname }}" disabled />
                    </div>
                    <div>
                        <label for="email" class="block mb-2 text-sm font-medium text-black">Email</label>
                        <input type="email" name="email" id="email"
                            class="bg-gray-50 border border-gray-300 text-black rounded-lg block w-2/3 p-2.5"
                            Value="{{ $user->email}}" disabled />
                    </div>
                    <div>
                        <label for="year" class="block mb-2 text-sm font-medium text-black">Tahun Awardee</label>
                        <input type="text" name="year" id="year"
                            class="bg-gray-50 border border-gray-300 text-black rounded-lg block w-2/3 p-2.5"
                            Value="{{ $user->awardee->year }}" disabled />
                    </div>
                    <div>
                        <label for="study" class="block mb-2 text-sm font-medium text-black">Pendidikan</label>
                        <textarea name="study" id="study"
                            class="bg-gray-50 border border-gray-300 text-black rounded-lg block w-3/4 h-20 p-2.5 resize-none text-justify"
                            disabled>{{ $user->awardee->degree }} - {{ $user->awardee->studyProgram->faculty->name }} - {{ $user->awardee->studyProgram->name }}</textarea>
                    </div>
                </form>
                <!-- Button Container -->
                <div class=" flex text-center mt-6 w-3/4 justify-end">
                    <a href="{{ route('landing-page.awardee.index') }}"
                        class="inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Kembali
                    </a>
                </div>
            </div>

    </section>

</x-landing-page.layout>