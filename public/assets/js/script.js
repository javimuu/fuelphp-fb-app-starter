/*
function getLoginStatus() {
    FB.getLoginStatus(function(response) {
        if(response.status === 'not_authorized') { // not authorised
            // trigger auth
            FB.ui({
                client_id : FB_APP_ID,
                method : 'oauth',
                perms : FB_APP_PERMS,// docs say scope - but this makes it work
                scope : FB_APP_PERMS
            }, 	function(response) {
                // loop back and force check
                getLoginStatus();
            });
        } else if(response.status === 'connected') { // connected
            appRequest(response.authResponse.userID)
        }
    }, true);
}
*/
/*
function appRequest() {
    FB.ui({
        method: 'apprequests',
        message: 'Message'
    }, function(response) {
        if(response != undefined){
            // log('Friends: ');
            // debug(response.id);
        } else {
            // redirect user
            window.location = BASE_URL + 'controller/action';
        }

    });
}
*/
/*
function publish_opengraph()
{
    FB.api(
        '/me/' + FB_APP_NAMESPACE + ':action_type', // change action_type
        'post',
        { object_type: BASE_URL + 'controller/action_of_action_type' }, // change object_type and URL to action_type
        function(response) {
            if (!response || response.error) {
                // log('OG error occurred');
            } else {
                // log('OG success. ID: ' + response.id);
            }
        }
    );
}
*/
/*
function publish_to_feed()
{
    FB.ui({
        method: 'feed',
        name: 'Name',
        link: FB_APP_URL,
        picture: BASE_URL + 'assets/img/og.jpg', // 200x200px
        caption: '',
        description: 'Description'
    }, function(response) {
        if (!response || response.error) {
         // log('Feed error occurred');
        } else {
         // log('Feed success. POST ID: ' + response.post_id);
        }
    });
}
*/
$(function(){

});
