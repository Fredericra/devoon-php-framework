
<div class="px-4 py-4">
    <div class="media">
        <div class="col-span-4"></div>
        <div class="col-span-4">
            <div class="px-4 py-4">
                <div class="shadow-lg rounded-lg">
                    <div class="text-center font-bold text-indigo-600 text-[16px]">
                        <p class="">Login</p>                        
                    </div>
                    <div class="flex justify-center items-center">
                        m-layout("Logo.Devoon")
                    </div>
                    <div class="py-4">
                        <div class="mx-4">
                            
                            <form action="{{ RouteAs::View('post.login')}}" enctype="multipart/form-data"  method="post" class="space-y-4">
                                <div class="relative">
                                    <div class="label">email</div>
                                    <div class="">
                                        <input type="text" value="{{ isset($response->email)?$response->email:'' }}" name="email" class="input">
                                    </div>
                                    <div> {{ isset($errors->email)?$errors->email:""  }}  </div>
                                </div>
                                <div class="relative">
                                    <div class="label">username</div>
                                    <div class="">
                                        <input type="text" value="{{ isset($response->username)?$response->username:'' }}" name="username" class="input">
                                    </div>
                                    <div> {{ isset($errors->username)?$errors->username:""  }}  </div>
                                </div>
                                <div class="relative">
                                    <div class="label">Password</div>
                                    <div class="">
                                        <input type="password" value="{{ isset($response->password)?$response->password:'' }}" name="password" class="input">
                                    </div>
                                    <div> {{ isset($errors->password)?$errors->password:""  }}  </div>
                                </div>
                              
                                <div class="px-4 py-4 flex justify-between">
                                    <div>
                                     @if(isset($errors->password) && empty($errors->email) && empty($errors->username))
                                        <p class="text-mono">
                                            forget
                                            <a href="{{ RouteAs::View('get.forget',$response->username) }}" class="link">password?</a>
                                        </p>
                                     @endif
                                    </div>
                                    <div>
                                        <button type="submit" class="button">LogIn</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-span-4">
           
        </div>
    </div>
</div>