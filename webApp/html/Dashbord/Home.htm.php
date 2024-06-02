
@title($user->email) 
@css("Public/base.css")
@script("Public/app.js")
<div>
    <div class="py-4 card">
        <div class="media">
            <div class="col-span-3">
                <div class="px-4 py-1 bg-yellow-200 rounded-r-md border-l-4 border-indigo-500">
                    <p class="">WELCOME to you v-value($user->email)</p>
                </div>
            </div>
            <div class="col-span-3">
                <div class="px-4 py-2">
                  
                </div>
            </div>
            <div class="col-span-3"></div>
            <div class="col-span-3 px-4">
                <div class="flex justify-end space-x-3">
                    <a href="{{ RouteAs::View('admin') }}" class="link">Admin</a>
                    <a href="{{ RouteAs::View('home') }}" class="link">Home</a>
                    <a href="{{ RouteAs::View('out') }}" class="link">logout</a>
                </div>
            </div>
        </div>
    </div>
    <div class="media">
        <div class="col-span-4"></div>
        <div class="col-span-4">
            <div class="py-4 ">
                <div class="flex justify-center items-center h-full">
                    <div class="text-center py-4 px-4 outline outline-2 rounded-tl-2xl rounded-br-2xl outline-indigo-500">
                        <p class="text-indigo-500 font-bold text-2xl">DEVPARK FRAMEWORK</p>
                    </div>
                </div>
            </div>
        </div>
       
    </div>
</div>