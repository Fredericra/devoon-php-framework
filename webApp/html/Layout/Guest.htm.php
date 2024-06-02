
<div>
    <div class="media gap-2">
        @if(count((array)$guest)>0)
        @foreach($guest as $value)
        <div class="col-span-4">
            <div class="px-2 py-2 rounded-md hover:shadow-cyan-800 hover:shadow-lg bg-gray-400 h-full duration-1000 card">
                <div class="text-center h-1/4">
                    <p class="text-indigo-950">{{ $value['email'] }} </p>  
                </div>
                <div class="grid grid-cols-3 gap-2 px-2">
                    <div class="col-span-1">
                        <div class="flex justify-center items-center p-2 m-2">
                            <div class="rounded-full h-[100px] bg-white w-[100px]"></div>
                        </div>
                    </div>
                    <div class="col-span-2">
                        <div class="px-2 py-2 border-l-2 border-l-emerald-500">
                            <div class="">
                                <ul class="font-mono text-violet-700">
                                    <li>
                                        {{ $value['username'] }}
                                    </li>
                                    <li>
                                        {{ $value['email'] }}
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="py-2 px-2">
                            <div class="flex justify-end items-center space-x-2">
                                    <div class="">
                                        <a href="{{ RouteAs::View('get.login') }}" class="button">SignUp</a>
                                    </div>
                                    <div class="">
                                        <a href="{{ RouteAs::View('delete.guest',$value['username']) }}" class="button">Trash</a>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
        @else
        <div class="col-span-10 col-start-2 ">
            <div class="flex justify-center h-screen bg-gray-600 font-mono text-white items-center">
                <p class="">Empty guest</p>
            </div>
        </div>
        @endif
    </div>
</div>
