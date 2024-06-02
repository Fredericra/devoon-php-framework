@style("Public/base.css")
@title("devspark framework")  
@script("Public/app.js")
<div>
    <div class="indigo-400 text-indigo-950 card shadow-cyan-400 py-2" id="hello">
        <div class="media">
            <div class="col-span-3 px-4">
                <a href="/" class="link">
                    m-layout("Logo/Devoon")
                </a>
            </div>
            <div class="col-span-3"></div>
            <div class="col-span-3"></div>
            <div class="col-span-3 px-4">
                <div class="flex justify-end space-x-3">
                    @if(Admin::Is())
                    <div class="">
                        <a href="{{ RouteAs::View('main') }} " class="link">Dashbord</a>
                    </div>
                    @endif
                    <div class="">
                        <a href="{{ RouteAs::View('admin') }} " class="link">AppConfig</a>
                    </div>
                    @if(!Admin::Is())
                    <div class="">
                        <a href="{{ RouteAs::View('get.guest')}} " class="link">Guest</a>
                    </div>
                    <div class="">
                        <a href="{{ RouteAs::View('get.sigin')}} " class="link">SigIn</a>
                    </div>
                    <div class="">
                        <a href="{{ RouteAs::View('get.login') }} " class="link">LogIn</a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="px-4 py-4">
        @if(RouteAs::Is('get.login'))
        m-layout("Layout.Login",["id"=>4])
        @elseif(RouteAs::Is('get.sigin'))
        m-layout("Layout.Sigin",["id"=>4]) 
        @elseif(RouteAs::Is('get.guest'))
        m-layout("Layout.Guest")
        @elseif(RouteAs::Is('login.guest'))
        m-layout("Layout.GuestLogin")
        @else
        <div class="flex justify-center items-center">
            m-layout("Logo.Devoon")
        </div>
        m-layout("Layout.Home")
      @endif
    </div>
<div class="bg-gray-400 py-2 fixed bottom-0 w-full">
        <div class="flex justify-end space-x-2 items-end px-4">
            m-layout("Logo.Devoon")
            <p class="text-white">  @copyright {{ date("Y") }} devoon</p>
        </div>
</div>    
<script>

</script>
