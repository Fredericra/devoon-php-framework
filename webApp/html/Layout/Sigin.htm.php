
<div class="px-4 py-4">
    <div class="media">
        <div class="col-span-4"></div>
        <div class="col-span-4">
            <div class="px-4 py-4">
                <div class="shadow-lg rounded-lg">
                    <div class="text-center font-bold text-indigo-600 text-[16px]">
                        <p class="">SignIn</p>                        
                    </div>
                    <div class="flex justify-center items-center">
                        m-layout("Logo.Devoon")
                    </div>
                    <div class="py-4">
                        <div class="mx-4">
                            <form action="{{ RouteAs::View('post.sigin')}} " enctype="multipart/form-data" method="POST" class="space-y-4">
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
                                        <input type="text"  name="password" class="input">
                                    </div>
                                    <div> {{ isset($errors->password)?$errors->password:""  }}  </div>
                                </div>
                                <div class="relative">
                                    <div class="label">Confirm</div>
                                    <div class="">
                                        <input type="text" name="confirm" class="input">
                                    </div>
                                    <div> {{ isset($errors->confirm)?$errors->confirm:""  }}  </div>
                                </div>
                                <div class="px-4 py-4 flex justify-end">
                                    <button type="submit" class="button">SignUp</button>
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