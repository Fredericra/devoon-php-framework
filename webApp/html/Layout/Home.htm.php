<div>
    <div>
        <div class="media">
            @foreach($response->dash as $dash)
            <div class="col-span-6">
                <div class="px-4 py-4 relative">
                    <div class="bg-gray-100 h-[200px] w-full px-4 py-2 shadow-md hover:shadow-2xl hover:shadow-indigo-600 rounded-md hover:rounded-xl duration-1000">
                        <div class="text-center">
                            <p class="fon-bold text-[22px] text-indigo-">
                                @if(isset($dash->documentation))
                                <a href="{{ RouteAs::View('documentation') }}" class="link">
                                    v-value($dash->title)
                                </a>
                                @else
                                v-value($dash->title)
                                @endif
                            </p>
                        </div>

                        <div class=" border-l-2 border-indigo-600">
                            <div class="py-4 px-4">

                                <p class="indent-12 text-[17px] font-thin">
                                    v-value($dash->value)
                                </p>
                            </div>
                        </div>
                        <div>
                            @foreach($dash->command as $key =>$comand)
                            <div class="flex justify-start px-4">
                                <div class="flex justify-between space-x-5">
                                    <span class="underline text-indigo-500"> v-value($key) </span>
                                    <span class="text-gray-400">v-value($comand)</span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>