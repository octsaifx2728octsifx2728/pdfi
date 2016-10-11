
var fb_api={
   fb_user:{},
   fb_userload_callback:[],
   fb_loadUser:function(token){
    var url="https://graph.facebook.com/me?access_token="+token+"&callback=fb_api.fb_loadUserHandler";
    var script=document.createElement("script");
    script.type="text/javascript";
    script.src=url;
    document.getElementsByTagName("head")[0].appendChild(script);
   },
   fb_loadUserHandler:function(user){
    fb_api.user=user;
    if(fb_api.fb_userload_callback.length){
      for(i in fb_api.fb_userload_callback){
	fb_api.fb_userload_callback[i](user);
      }
    }
   }
};

$(document).ready(
   function(){
    fb_api.fb_loadUser(fb_token);
   }
);
