window.hcfbvalidated = hc_fb_validated;
window.hc_advert = false;
var hcreferer = 'http://josequesada.com/';var hctrack = '';var hctrackdomain = 'josequesada.com';if (hcreferer.indexOf(document.domain) < 0) {
 del_cookie("hc_referer");
 del_cookie("hc_referer_domain");
 hybridconnect_setCookie("hc_referer",hcreferer,730);
 hybridconnect_setCookie("hc_referer_domain",hctrackdomain,730);
}
if (hctrack) {
 del_cookie("hc_tracking");
 hybridconnect_setCookie("hc_tracking",hctrack,730);
}
    jQuery.noConflict();

    var hc_current_connector = 0;
    var hc_current_randid = 0;
    var hc_current_variationid = 0;
    var user_facebook_logged = null;
    var ajaxurl = window.hc_ajax_url;

//only load code if FB app is validated
if(window.hc_fb_validated!=0) {
    FB.init({
        appId      : window.hc_fb_appid, // App ID
        status     : true, // check login status
        cookie     : true, // enable cookies to allow the server to access the session
        oauth      : true, // enable OAuth 2.0
        xfbml      : true  // parse XFBML

    });
    FB.Event.subscribe('auth.statusChange', function(response) {
        if (response.authResponse) {
            FB.api('https://graph.facebook.com/me/', {accessToken: response.authResponse.accessToken}, function(user) {
                user_facebook_logged = user;
                hc_show_loader(jQuery(".hc_facebook_not_connected" + hc_current_randid));
                submit_subscribe_connector(user_facebook_logged.name, user_facebook_logged.email, hc_current_connector, 0, hc_current_randid, hc_current_variationid);
            });
        } else {
            if (response.status == "not_authorized") {
                jQuery('.hc_facebook_not_connected').show();
            } else {
                jQuery('.hc_facebook_not_connected').show();
            }
        }
    });
   }
   function check_ie_position(thisConnector) {
             var optin_height = parseInt(jQuery(thisConnector).height());
             var optin_width = parseInt(jQuery(thisConnector).width());
             jQuery(thisConnector).css("top","50%");
             jQuery(thisConnector).css("left","50%");
             jQuery(thisConnector).css("margin-left","-" + (optin_width/2) + "px");
             if(parseInt(jQuery(window).height()/2) - (optin_height/2) < 20) { connector_top_margin=(jQuery(window).height()/2)-20; } else { connector_top_margin = optin_height/2; }
             jQuery(thisConnector).css("margin-top","-" + connector_top_margin + "px");
             jQuery(thisConnector).css("position","absolute");
   }
   function check_other_browser_position(thisConnector) {
     var optin_height = parseInt(jQuery(thisConnector).height());
     var height_difference = parseInt(jQuery(window).height()) - optin_height;
     jQuery(thisConnector).css("vertical-align","top");
     if(height_difference<20) { connector_top_margin=20; } else { connector_top_margin = height_difference/2; }
     jQuery(thisConnector).css("margin-top",connector_top_margin + "px");
   }
   function calculateSqueezePosition(connector) {
      thisConnector = "." + connector;
      var template_wrapper = jQuery(thisConnector).parents(".hc_template_frontend_wrapper").first();
      if(jQuery(template_wrapper).siblings(".hc_hidden_squeeze_alignment").first().val()=="1") {
         // centre alignment set to true
         var optin_height = parseInt(jQuery(thisConnector).height());
         if(parseInt(jQuery(window).height()) - optin_height > 100) {
           if ( jQuery.browser.msie && jQuery.browser.version<9) {
           check_ie_position(thisConnector);
         }
         else {
           check_other_browser_position(thisConnector);
         }
       }
       else {
         jQuery(thisConnector).css("margin-top","20px");
       }

       jQuery(window).resize( function() {
     var thisConnector = jQuery(".connectorWrapper:visible");
     if ( jQuery.browser.msie && jQuery.browser.version<9) {
           check_ie_position(thisConnector);
         }
         else {
           check_other_browser_position(thisConnector);
         }
   });
     }
     }


     jQuery(document).ready(function(){

    // move custom code to after body in dom tree
    jQuery('body').prepend(jQuery('div.customsubmit'));
    //attach enter key button event handle to input forms for submitting form on enter
    jQuery(".connectorInputFields").keyup(function(event) { if (event.keyCode == 13) {  jQuery(this).parent().siblings("div.emailButtonContainer").find(".navButton").click(); } });


        // create connectors already displayed array
        connectorsAlreadyDisplayed = new Array();
        window.hcAlreadyVisible=false;

        if(window.hc_fb_validated!=0) {
        FB.getLoginStatus(function(response) {
            jQuery('.facebookConnectorWrapper').hide();
            jQuery('.oneClickConnectorWrapper').hide();
            if (response.authResponse) {
                FB.api('https://graph.facebook.com/me/', {accessToken: response.authResponse.accessToken}, function(user) {
                    user_facebook_logged = user;
                    jQuery('.oneClickConnectorWrapper').show();
                    // destroy other instances
                    window.hybridConnectedStatus="hc_facebook_connected";
                });
            } else {
                if (response.status == "not_authorized") {
                    jQuery('.facebookConnectorWrapper').show();
                    window.hybridConnectedStatus="hc_facebook_not_connected";
                } else {
                    jQuery('.facebookConnectorWrapper').show();
                    window.hybridConnectedStatus="hc_shortcode_form";
                }
            }
            displayConnectorsInPage(response.status);
            window.loggedInStatus=response.status;
        });

        } else {
         displayConnectorsInPage("appFailure");
         window.loggedInStatus="appFailure";
         window.hybridConnectedStatus="hc_shortcode_form";
        }

        for (var i = 0; i < hc_connectors.length; i++) {
            var temp_connector = hc_connectors[i];
            var dtpl = jQuery("#hc_hidden_default_template" + hc_rand_ids[i]).val();
            var is_responsive = jQuery("#hc_hidden_is_responsive" + hc_rand_ids[i]).val();
            if (is_responsive == 1) {
                hc_make_connector_responsive(hc_rand_ids[i]);
            }
        }
        jQuery(".fb-login-button iframe").click(function(){
          hc_current_connector = jQuery(this).parents().find(".connectorWrapper").first().val();
        });

        jQuery('.hc_fb_form_connector_submit').click(function() {
            var thisConnectorSelector=jQuery(this).parents(".oneClickConnectorWrapper").first();
            var hc_hidden_connector_id = jQuery(thisConnectorSelector).find('.hc_hidden_connector_id').val();
            var id_variation = jQuery(thisConnectorSelector).find('.hc_hidden_variation_id').val();
            var hc_hidden_randid = jQuery(thisConnectorSelector).find('.hc_hidden_connector_randid').val();
            var hc_check_connector_used = jQuery('#hc_check_connector_used' + hc_hidden_connector_id).val();
            if (hc_check_connector_used == 1) {
                return false;
            }
            if(jQuery("#hc_hidden_connector_type" + hc_hidden_randid).val()=="None") {
             typage = jQuery("#hc_hidden_ad_ty_page" + hc_hidden_randid).val();
             window.location=typage;
            return false;
            }
            hc_show_loader(thisConnectorSelector);
            submit_subscribe_connector(user_facebook_logged.name, user_facebook_logged.email, hc_hidden_connector_id, 0, hc_hidden_randid, id_variation);
            return false;
        });
        jQuery('.hc_form_connector_submit').click(function(){
            var thisConnectorSelector=jQuery(this).parents(".emailConnectorWrapper").first();
            var hc_hidden_connector_id = jQuery(thisConnectorSelector).find(".hc_hidden_connector_id").val();
            var hc_hidden_randid = jQuery(thisConnectorSelector).find('.hc_hidden_connector_randid').val();
            var name = jQuery(thisConnectorSelector).find("input#hc_txt_name"+hc_hidden_randid).val();
            var email = jQuery(thisConnectorSelector).find("input#hc_txt_email"+hc_hidden_randid).val();
            var id_variation = jQuery(thisConnectorSelector).find('.hc_hidden_variation_id').val();
            var hc_hidden_post_id = jQuery('.hc_hidden_post_id').val();
            var hc_valid_data=hybridCheckInputData(hc_hidden_randid);
            if(!hc_valid_data) {
              return false;
            }
            hc_show_loader(thisConnectorSelector);
            submit_subscribe_connector(name, email, hc_hidden_connector_id, hc_hidden_post_id, hc_hidden_randid, id_variation);
            return false;
        });
        jQuery('.hc_shortcode_facebook').find(".hc_button_connect").click(function(){
            var thisConnectorSelector = jQuery(this).parents('oneClickConnectorWrapper').first();
            var hc_hidden_connector_id = jQuery(thisConnectorSelector).find('.hc_hidden_connector_id').val();
            var id_variation = jQuery(thisConnectorSelector).find('.hc_hidden_variation_id').val();
            var hc_check_connector_used = jQuery('#hc_check_connector_used' + hc_hidden_connector_id).val();
            var hc_hidden_randid = jQuery(thisConnectorSelector).find('.hc_hidden_connector_randid').val();
            if (hc_check_connector_used == 1) {
                return false;
            }
            if(jQuery("#hc_hidden_connector_type" + hc_hidden_randid).val()=="None") {
             typage = jQuery("#hc_hidden_ad_ty_page" + hc_hidden_randid).val();
             window.location=typage;
            return false;
            }
            hc_show_loader(thisConnectorSelector);
            submit_subscribe_connector(user_facebook_logged.name, user_facebook_logged.email, hc_hidden_connector_id, 0, hc_hidden_randid, id_variation);
            return false;
        });
        //submit for the custom html form
        jQuery('.hc_custom_form_submit').click(function(){
            var name = jQuery(this).parents('.emailConnectorWrapper').find("input[type=text][name=name]").val();
            var email = jQuery(this).parents('.emailConnectorWrapper').find("input[type=text][name=email]").val();
            var thisConnectorSelector = jQuery(this).parents('emailConnectorWrapper').first();
            var hc_hidden_connector_id = jQuery(thisConnectorSelector).find('.hc_hidden_connector_id').val();
            var id_variation = jQuery(thisConnectorSelector).find('.hc_hidden_variation_id').val();
            var hc_hidden_randid = jQuery(thisConnectorSelector).find('.hc_hidden_connector_randid').val();
            var hc_hidden_post_id = jQuery('.hc_hidden_post_id').val();
            var hc_valid_data=hybridCheckInputData(hc_hidden_randid);
            if(!hc_valid_data) {
              return false;
            }
            hc_show_loader(thisConnectorSelector);
            submit_subscribe_connector(name, email, hc_hidden_connector_id, hc_hidden_post_id, hc_hidden_randid, id_variation);
            return false;
        });

        // set up the lightbox / optin box scroll triggers.
        // only run if lightbox
        var lightboxBuilt=0;
        var optinBuilt=0;
        var lb_scroll_size=0;
        var optin_scroll_size=0;
        var loadScrollListener=0;

        // build data for scrolling data
        for (var i = 0; i < hc_connectors.length; i++) {

            //only run through if connector is lightbox or optin
            var isLightbox = jQuery("#islightbox" + hc_rand_ids[i]);
            if(isLightbox) {
                // make sure scroll trigger data not already built
                if(!lightboxBuilt || !optinBuilt) {
                    var temp_connector = hc_connectors[i];
                    optinOrLight = jQuery("#lightbox_or_optin" + hc_rand_ids[i]).val();
                    lightboxActivated = jQuery("#lightboxActivated" + hc_rand_ids[i]).val();
                    optinActivated = jQuery("#optinActivated" + hc_rand_ids[i]).val();
                    if((optinOrLight=="lightbox" && lightboxActivated=="1") || (optinOrLight=="optin" && optinActivated=="1")) {
                        //figure out if optin or lightbox
                        if(optinOrLight=="optin") { scrollExtension="_optin_"; } else { scrollExtension="_"; }

                        // is scroll enabled for this optin / lightbox?
                        var hc_scroll_enabled = jQuery(".hc_hidden_scroll_enable" + scrollExtension + hc_rand_ids[i] + "[value='1']");
                        var hc_scroll_enabled_value=jQuery(hc_scroll_enabled).val();

                        // scroll is enabled for this connetor - build variables - only ever maximum of one lightbox and one scroll in box - set scroll listener to 1
                        if(hc_scroll_enabled_value==1) {
                            loadScrollListener=1;
                            var cookie_enabled = 0;
                            var cookie_name = null;
                            var randid = hc_rand_ids[i];
                            // get scroll size for optin/lightbox using scrollextension
                            hc_scroll_size = parseInt(jQuery('#hc_hidden_scroll_size' + scrollExtension + randid).val().replace("%", ""));

                            if(optinOrLight=="lightbox" ) { lb_scroll_enabled=hc_scroll_enabled_value; lb_scroll_size=hc_scroll_size; lb_connector_id=randid; lightboxBuilt=1 }
                            else {
                                if(optinOrLight=="optin") { optin_scroll_enabled=hc_scroll_enabled_value; optin_scroll_size=hc_scroll_size; optin_connector_id=randid; optinBuilt=1; }
                            }
                        }
                    }
                    //close is_lightbox check below this line
                }
            }
        }

        // all data now built. Load scroll listener
        var disable_lb = false;
        var disable_optin = false;

        // only load jquery scroll if scroll listener activated
        if(loadScrollListener==1) {
        jQuery.fn.exists = function(){return this.length>0;}
        // try to find comments marker, if not use document.height
        if(jQuery("#hc_comments_marker").exists()) {
                  offset=jQuery("#hc_comments_marker").offset();
                  d = offset.top;
                }
        else if(jQuery("#hc_comments_manual_marker").exists()) {
                offset=jQuery("#hc_comments_manual_marker").offset();
                d = offset.top;
        }
        else {
                  d = jQuery(document).height();
                }

            jQuery(window).scroll(function(){

                var s = jQuery(window).scrollTop();

                c = jQuery(window).height();
                scrollPercent = (s / (d-c)) * 100;

                // if either lightbox or optin scroll sizes are set
                if (lb_scroll_size || optin_scroll_size) {

                    // define the triggers for either lightbox or optin
                    if ((scrollPercent >= lb_scroll_size && disable_lb == false) || (scrollPercent >= optin_scroll_size && disable_optin == false)) {

                        // if triggered, display lightbox
                        if(lightboxBuilt && scrollPercent >= lb_scroll_size && disable_lb == false) {
                            //    console.log("Scroll Trigger : Lightbox & ID = " + lb_connector_id + ": Scroll Percentage " + lb_scroll_size);
                            showLightBox(lb_connector_id, "fade", "no");
                            disable_lb=true;
                        }
                        // if triggered, display optin box
                        if(optinBuilt==1 && scrollPercent >= optin_scroll_size && disable_optin == false) {
                            //   console.log("Scroll Trigger : Optin & ID = " + optin_connector_id + ": Scroll Percentage " + optin_scroll_size);
                            showLightBox(optin_connector_id, "animate", "no");
                            disable_optin=true;
                        }
                    }
                }
            });
        }

    });


    function checkSubscriptionsWithFacebook()
    {
        var hc_ajax_nonce = jQuery('#hc_hidden_fb_ajaxnonce').val();
        if (!user_facebook_logged) {
            return;
        }
        for (var i = 0; i < hc_connectors.length; i++) {
            var temp_connector = hc_connectors[i];
            if (!temp_connector) {
                return;
            }
            var data = {
                action: 'hc_frontend_check_subscription',
                email: user_facebook_logged.email,
                id_connector: hc_rand_ids[i],
                security: hc_ajax_nonce
            };
            jQuery.post(ajaxurl, data, function(response) {
                if (response > 0) {
                    jQuery('#hc_connect_with_facebook' + response).html('You have successfuly connected!');
                    jQuery('#hc_check_connector_used' + response).val(1);
                }
            });
        }
    }
    function displayConnectorsInPage(status)
    {

        var connectorsTriggered = new Array();
        var connectorsType = new Array();
        var timedtempids = new Array();
        var timedtypes = new Array();
        var timeddurations = new Array();
        var timedcookies = new Array();
        var timedCookieNames = new Array();
        var lightboxTimedBuilt="0";
        var optinTimedBuilt = "0";
        var timedconnectorids = new Array();

        for (var i = 0; i < hc_connectors.length; i++) {

            var temp_connector = hc_connectors[i];
            if (!temp_connector) {
                return;
            }
            var element_displayed = "hc_shortcode_form";
            window.element_displayed="hc_shortcode_form";
            var element_hidden = "hc_shortcode_facebook";
            window.element_hidden = "hc_shortcode_facebook";
            if(status=="appFailure") {
              if(temp_connector.Type=="None") {
              // it's an ad so show one click box
              window.hc_advert = true;
              window.facebookConnected=true;
              var element_displayed = "hc_shortcode_facebook";
              window.element_displayed = "hc_shortcode_facebook";
              var element_hidden = "hc_shortcode_form";
              window.element_hidden = "hc_shortcode_form";
              if(window.is_squeeze) { calculateSqueezePosition("hc_facebook_connected"  + hc_rand_ids[i]);  }
              jQuery('.hc_facebook_connected' + hc_rand_ids[i]).fadeIn();
              jQuery('.hc_shortcode_form' + hc_rand_ids[i]).remove();
              jQuery('.hc_facebook_not_connected' + hc_rand_ids[i]).remove();
              } else {
              // no facebook app connection so show form
              var element_displayed = "hc_shortcode_form";
              window.element_displayed="hc_shortcode_form";
              var element_hidden = "hc_shortcode_facebook";
              window.element_hidden = "hc_shortcode_facebook";
              if(window.is_squeeze) { calculateSqueezePosition('hc_shortcode_form' + hc_rand_ids[i]);  }
              jQuery('.hc_shortcode_form' + hc_rand_ids[i]).fadeIn();
              jQuery('.hc_shortcode_facebook' + hc_rand_ids[i]).remove();
              }

            } else {
            switch (temp_connector.Type) {
                case 'Hybrid':
                // not logged in
                    if (status=="unknown") {
                        var element_displayed = "hc_shortcode_form";
                        window.element_displayed="hc_shortcode_form";
                        var element_hidden = "hc_shortcode_facebook";
                        window.element_hidden = "hc_shortcode_facebook";
                         if(window.is_squeeze) { calculateSqueezePosition("hc_shortcode_form"  + hc_rand_ids[i]);  }
                        jQuery('.hc_shortcode_form' + hc_rand_ids[i]).fadeIn();
                        jQuery('.hc_shortcode_facebook' + hc_rand_ids[i]).remove();
                // logged in but not connected
                    } else if (status=="not_authorized") {
                        var element_displayed = "hc_shortcode_facebook";
                        window.element_displayed = "hc_shortcode_facebook";
                        var element_hidden = "hc_shortcode_form";
                        window.element_hidden = "hc_shortcode_form";
                        if(window.is_squeeze) { calculateSqueezePosition("hc_facebook_not_connected" + hc_rand_ids[i]);  }
                        jQuery('.hc_facebook_not_connected' + hc_rand_ids[i]).fadeIn();
                        jQuery('.hc_shortcode_form' + hc_rand_ids[i]).remove();
                        jQuery('.hc_facebook_connected' + hc_rand_ids[i]).remove();
                 // connected to app
                    } else if (status=="connected") {
                        var element_displayed = "hc_shortcode_facebook";
                        window.element_displayed = "hc_shortcode_facebook";
                        var element_hidden = "hc_shortcode_form";
                        window.element_hidden = "hc_shortcode_form";
                        if(window.is_squeeze) { calculateSqueezePosition("hc_facebook_connected"  + hc_rand_ids[i]);  }
                        jQuery('.hc_facebook_connected' + hc_rand_ids[i]).fadeIn();
                        jQuery('.hc_shortcode_form' + hc_rand_ids[i]).remove();
                        jQuery('.hc_facebook_not_connected' + hc_rand_ids[i]).remove();
                    }
                    break;
                case 'Facebook':
                if (status=="connected") {
                        window.facebookConnected=true;
                        var element_displayed = "hc_shortcode_facebook";
                        window.element_displayed = "hc_shortcode_facebook";
                        var element_hidden = "hc_shortcode_form";
                        window.element_hidden = "hc_shortcode_form";
                        if(window.is_squeeze) { calculateSqueezePosition("hc_facebook_connected"  + hc_rand_ids[i]);  }
                        jQuery('.hc_facebook_connected' + hc_rand_ids[i]).fadeIn();
                        jQuery('.hc_shortcode_form' + hc_rand_ids[i]).remove();
                        jQuery('.hc_facebook_not_connected' + hc_rand_ids[i]).remove();
                    } else {
                        window.facebookConnected=false;
                        var element_displayed = "hc_shortcode_facebook_not_connected";
                        window.element_displayed = "hc_shortcode_facebook_not_connected";
                        var element_hidden = "hc_shortcode_form";
                        window.element_hidden = "hc_shortcode_form";
                        if(window.is_squeeze) { calculateSqueezePosition("hc_facebook_not_connected"  + hc_rand_ids[i]);  }
                        jQuery('.hc_facebook_not_connected' + hc_rand_ids[i]).fadeIn();
                        jQuery('.hc_shortcode_form' + hc_rand_ids[i]).remove();
                        jQuery('.hc_facebook_connected' + hc_rand_ids[i]).remove();
                    }
                     break;
                case 'Form':
                    var element_displayed = "hc_shortcode_form";
                    window.element_displayed="hc_shortcode_form";
                    var element_hidden = "hc_shortcode_facebook";
                    window.element_hidden = "hc_shortcode_facebook";
                    if(window.is_squeeze) { calculateSqueezePosition("hc_shortcode_form" + hc_rand_ids[i]);  }
                    jQuery('.hc_shortcode_form' + hc_rand_ids[i]).fadeIn();
                    jQuery('.hc_shortcode_facebook' + hc_rand_ids[i]).remove();
                    break;
                case 'None':
                    window.hc_advert = true;
                    window.facebookConnected=true;
                    var element_displayed = "hc_shortcode_facebook";
                    window.element_displayed = "hc_shortcode_facebook";
                    var element_hidden = "hc_shortcode_form";
                    window.element_hidden = "hc_shortcode_form";
                    if(window.is_squeeze) { calculateSqueezePosition("hc_facebook_connected"  + hc_rand_ids[i]);  }
                    jQuery('.hc_facebook_connected' + hc_rand_ids[i]).fadeIn();
                    jQuery('.hc_shortcode_form' + hc_rand_ids[i]).remove();
                    jQuery('.hc_facebook_not_connected' + hc_rand_ids[i]).remove();
                    break;
            }
            }


            var lightbox_on = jQuery("#hc_hidden_lightbox_on" + hc_rand_ids[i]).val();

            // hidden_lightbox_on always matches when either optin or lightbox.  It's a flag to say this has come from lightbox frontend function
            if (lightbox_on == 1) {

                var mytempid = hc_rand_ids[i];

                // check if optin box or lightbox
                optinOrLight = jQuery("#lightbox_or_optin" + mytempid).val();

                // clear variables for iterations
                lightbox_on_time="0";
                lightbox_time_enable="0";
                cookie_enabled="0";
                optin_on_time="0";
                optin_time_enable="0";
                var timetorun="0";

                // get variables depending on whether a lightbox or optin box display -  can only display one lightbox or one optin per page from timed function
                if(optinOrLight=="lightbox") {
                    lightbox_on_time = jQuery("#hc_hidden_lightbox_ontime" + mytempid).val();
                    lightbox_time_enable = jQuery("#hc_hidden_time_enable" + mytempid).val();

                    // catch empty data, set to 0
                    if (lightbox_on_time == "") { lightbox_on_time = 0; }
                    var timetorun = parseInt(lightbox_on_time + "000");

                } else if (optinOrLight=="optin") {
                    optin_on_time = jQuery("#optin_on_time" + mytempid).val();
                    optin_time_enable = jQuery("#optin_time_enable" + mytempid).val();
                    // catch empty data, set to 0
                    if (optin_on_time == "") { optin_on_time = 0; }
                    var timetorun = parseInt(optin_on_time + "000");
                }

                lightboxActivated = jQuery("#lightboxActivated" + mytempid).val();
                optinActivated = jQuery("#optinActivated" + mytempid).val();

                // check if this iteration has timer enabled or not
                if ((lightbox_time_enable!="0" && lightboxTimedBuilt==0 && lightboxActivated=="1" ) || (optin_time_enable!="0" && optinTimedBuilt==0 && optinActivated=="1")) {
                    var thisConnectorID=jQuery("#hc_hidden_this_connector_id"+mytempid).val();
                    var thisConnectorType=jQuery("#lightbox_or_optin"+mytempid).val();
                    var timerAlreadyAdded=false;

                    // make sure connector ID hasn't already been added to timed loop.  Two connectors with the same ID and type can't be added to timer.
                    for (var y = 0; y < timedtempids.length; y++) {
                        if(thisConnectorID==timedconnectorids[y] && thisConnectorType==timedtypes[y]) {
                            timerAlreadyAdded=true;
                        }
                    }

                    if(!timerAlreadyAdded) {
                        // build array of data to instantiate timeout function
                        timedconnectorids.push(thisConnectorID);
                        timedtempids.push(mytempid);
                        timedtypes.push(optinOrLight);
                        timeddurations.push(timetorun);

                    }

                    if(optinOrLight=="lightbox") { lightboxTimedBuilt==1; }
                    if(optinOrLight=="lightbox") { optinTimedBuilt==1; }

                }

                // build array for click trigger functions - links mustn't be bound with jquery more than once, but can have unlimited lightbox or optin boxes on page (including links to the same connector).
                optinOrLight = jQuery("#lightbox_or_optin" + hc_rand_ids[i]).val();
                alreadyAdded=0;
                for (var j = 0; j < connectorsTriggered.length; j++) {
                    if(connectorsTriggered[j]==temp_connector.IntegrationID && connectorsType[j]==optinOrLight) {
                        alreadyAdded=1;
                    }
                }

                // if length of array is zero, then can't already be added
                if(connectorsTriggered.length==0) { alreadyAdded =0; }

                // add current iteration to array for lookups to prevent binding jquery more than once to same link
                connectorsTriggered.push(temp_connector.IntegrationID);
                connectorsType.push(optinOrLight);
                // if jquery not already bound for this combination:

                if (alreadyAdded==0) {

                    jQuery(".hc_" + optinOrLight + "_click_trigger" + temp_connector.IntegrationID).click(function(){
                        var thisClass=jQuery(this).attr("class");
                        var thisClassNumber=thisClass.match(/\d+/);
                        if(thisClass.indexOf("lightbox") != -1) {
                            //must be a lightbox

                            var lightbox_to_display_id = jQuery(".hc_hidden_rand_id" + thisClassNumber).first().val();
                        }
                        else {
                            //must be an optin - add to to end of selector for optin
                            var lightbox_to_display_id = jQuery(".hc_hidden_rand_id" + thisClassNumber+"o").first().val();
                        }

                        optinOrLight = jQuery("#lightbox_or_optin" + lightbox_to_display_id).val();
                        if(optinOrLight=="lightbox") {
                            //    console.log("Clicked Trigger : Ligthbox & ID = " + lightbox_to_display_id);
                            showLightBox(lightbox_to_display_id, "fade", "yes");

                        } else if(optinOrLight=="optin") {
                            //        console.log("Clicked Trigger : Optin & ID = " + lightbox_to_display_id);
                            showLightBox(lightbox_to_display_id, "animate", "yes");
                        }
                        return false;
                    });

                    var on_click = jQuery("#hc_hidden_lightbox_onclick" + hc_rand_ids[i]).val();
                    if (on_click && on_click != 0) {
                        jQuery("#" + on_click).click(function(){
                            optinOrLight = jQuery("#lightbox_or_optin" + hc_rand_ids[i]).val();
                            if(optinOrLight=="lightbox") {
                                //    console.log("Clicked Trigger : Ligthbox & ID = " + lightbox_to_display_id);
                                showLightBox(lightbox_to_display_id, "fade", "yes");
                            } else if(optinOrLight=="optin") {
                                //    console.log("Clicked Trigger : Optin & ID = " + lightbox_to_display_id);
                                showLightBox(lightbox_to_display_id, "animate", "yes");
                            }
                            return false;
                        });
                    }
                } else {
                }
                // end of lightbox functions
            }


        }
        runHybridTimers(timedtempids, timedtypes, timeddurations);
        jQuery(".hc_template_frontend_wrapper").show();

        jQuery(".fb-login-button").mouseenter(function() {
           var thisConnectorWrapper = jQuery(this).parents(".connectorWrapper").first();
           hc_current_connector = jQuery(thisConnectorWrapper).find('.hc_hidden_connector_id').val();
           hc_current_randid = jQuery(thisConnectorWrapper).find('.hc_hidden_connector_randid').val();
           hc_current_variationid = jQuery(thisConnectorWrapper).find('.hc_hidden_variation_id').val();
        });

           (function($,k,m,i,d){var e=$(i),g="waypoint.reached",b=function(o,n){o.element.trigger(g,n);if(o.options.triggerOnce){o.element[k]("destroy")}},h=function(p,o){if(!o){return -1}var n=o.waypoints.length-1;while(n>=0&&o.waypoints[n].element[0]!==p[0]){n-=1}return n},f=[],l=function(n){$.extend(this,{element:$(n),oldScroll:0,waypoints:[],didScroll:false,didResize:false,doScroll:$.proxy(function(){var q=this.element.scrollTop(),p=q>this.oldScroll,s=this,r=$.grep(this.waypoints,function(u,t){return p?(u.offset>s.oldScroll&&u.offset<=q):(u.offset<=s.oldScroll&&u.offset>q)}),o=r.length;if(!this.oldScroll||!q){$[m]("refresh")}this.oldScroll=q;if(!o){return}if(!p){r.reverse()}$.each(r,function(u,t){if(t.options.continuous||u===o-1){b(t,[p?"down":"up"])}})},this)});$(n).bind("scroll.waypoints",$.proxy(function(){if(!this.didScroll){this.didScroll=true;i.setTimeout($.proxy(function(){this.doScroll();this.didScroll=false},this),$[m].settings.scrollThrottle)}},this)).bind("resize.waypoints",$.proxy(function(){if(!this.didResize){this.didResize=true;i.setTimeout($.proxy(function(){$[m]("refresh");this.didResize=false},this),$[m].settings.resizeThrottle)}},this));e.load($.proxy(function(){this.doScroll()},this))},j=function(n){var o=null;$.each(f,function(p,q){if(q.element[0]===n){o=q;return false}});return o},c={init:function(o,n){this.each(function(){var u=$.fn[k].defaults.context,q,t=$(this);if(n&&n.context){u=n.context}if(!$.isWindow(u)){u=t.closest(u)[0]}q=j(u);if(!q){q=new l(u);f.push(q)}var p=h(t,q),s=p<0?$.fn[k].defaults:q.waypoints[p].options,r=$.extend({},s,n);r.offset=r.offset==="bottom-in-view"?function(){var v=$.isWindow(u)?$[m]("viewportHeight"):$(u).height();return v-$(this).outerHeight()}:r.offset;if(p<0){q.waypoints.push({element:t,offset:null,options:r})}else{q.waypoints[p].options=r}if(o){t.bind(g,o)}if(n&&n.handler){t.bind(g,n.handler)}});$[m]("refresh");return this},remove:function(){return this.each(function(o,p){var n=$(p);$.each(f,function(r,s){var q=h(n,s);if(q>=0){s.waypoints.splice(q,1);if(!s.waypoints.length){s.element.unbind("scroll.waypoints resize.waypoints");f.splice(r,1)}}})})},destroy:function(){return this.unbind(g)[k]("remove")}},a={refresh:function(){$.each(f,function(r,s){var q=$.isWindow(s.element[0]),n=q?0:s.element.offset().top,p=q?$[m]("viewportHeight"):s.element.height(),o=q?0:s.element.scrollTop();$.each(s.waypoints,function(u,x){if(!x){return}var t=x.options.offset,w=x.offset;if(typeof x.options.offset==="function"){t=x.options.offset.apply(x.element)}else{if(typeof x.options.offset==="string"){var v=parseFloat(x.options.offset);t=x.options.offset.indexOf("%")?Math.ceil(p*(v/100)):v}}x.offset=x.element.offset().top-n+o-t;if(x.options.onlyOnScroll){return}if(w!==null&&s.oldScroll>w&&s.oldScroll<=x.offset){b(x,["up"])}else{if(w!==null&&s.oldScroll<w&&s.oldScroll>=x.offset){b(x,["down"])}else{if(!w&&s.element.scrollTop()>x.offset){b(x,["down"])}}}});s.waypoints.sort(function(u,t){return u.offset-t.offset})})},viewportHeight:function(){return(i.innerHeight?i.innerHeight:e.height())},aggregate:function(){var n=$();$.each(f,function(o,p){$.each(p.waypoints,function(q,r){n=n.add(r.element)})});return n}};$.fn[k]=function(n){if(c[n]){return c[n].apply(this,Array.prototype.slice.call(arguments,1))}else{if(typeof n==="function"||!n){return c.init.apply(this,arguments)}else{if(typeof n==="object"){return c.init.apply(this,[null,n])}else{$.error("Method "+n+" does not exist on jQuery "+k)}}}};$.fn[k].defaults={continuous:true,offset:0,triggerOnce:false,context:i};$[m]=function(n){if(a[n]){return a[n].apply(this)}else{return a.aggregate()}};$[m].settings={resizeThrottle:200,scrollThrottle:100};e.load(function(){$[m]("refresh")})})(jQuery,"waypoint","waypoints",window);

        jQuery('.hc_template_frontend_wrapper').waypoint(function(event) {
            event.stopPropagation();
            var elid = jQuery(this).attr('id');
            var randid = elid.substr(19, elid.length);
            var hc_is_testing = jQuery("#hc_hidden_is_testing" + randid).val();
            var id_variation = jQuery("#hc_hidden_variation_id" + randid).val();
            hc_update_variation_views(id_variation);
        }, {
            triggerOnce: true,
            offset: 'bottom-in-view'
        });

    }

    function isSorted(myNum){
        for (var b = 1; b < myNum.length; b++)
        {
            if (myNum[b - 1] > myNum[b])
            {
                return false;
            }
        }
        return true;
    }


    function runHybridTimers(mytempid, type, duration) {

        // hack to make sure that ocnnectors are displayed in correct order.  If second time is less than first time in array then swap the two connector ID's around.
        var correctlySorted = isSorted(duration);
        if(!correctlySorted && duration.length==2) {
            tempConnector=new Array();
            tempConnector[1] = mytempid[0];
            tempConnector[0] = mytempid[1];
            mytempid[0] = tempConnector[0];
            mytempid[1] = tempConnector[1];
            tempType= new Array();
            tempType[1]=type[0];
            tempType[0]=type[1];
            type[0]=tempType[0];
            type[1]=tempType[1];
        }

        var i=0;
        for (var j = 0; j < mytempid.length; j++) {
            setTimeout(function() {
                var thisTimer=duration[i];
                thisTimerOptinOrLight=type[i];
                if(thisTimerOptinOrLight=="lightbox") {
                    //       console.log("Timed Trigger : Lightbox & ID = " + mytempid[i] + ": Time Delay " + duration[i]);
                    showLightBox(mytempid[i], "fade", "no");
                } else if(thisTimerOptinOrLight=="optin") {
                    //       console.log("Timed Trigger : Optin & ID = " + mytempid[i] + ": Time Delay " + duration[i]);
                    showLightBox(mytempid[i], "animate", "no");
                }
                i++;   }, duration[j]);
        }
    }

    function hybridconnect_setCookie(name,value,days) {
        if (days) {
            var date = new Date();
            date.setTime(date.getTime()+(days*24*60*60*1000));
            var expires = "; expires="+date.toGMTString();
        }
        else expires = "";
        document.cookie = name+"="+value+expires+"; path=/";
    }


    function hybridconnect_getCookie(c_name)
    {
        var i,x,y,ARRcookies=document.cookie.split(";");
        for (i=0;i<ARRcookies.length;i++)
        {
            x=ARRcookies[i].substr(0,ARRcookies[i].indexOf("="));
            y=ARRcookies[i].substr(ARRcookies[i].indexOf("=")+1);
            x=x.replace(/^\s+|\s+$/g,"");
            if (x==c_name)
            {
                return unescape(y);
            }
        }
    }


    function submit_subscribe_connector(name, email, id_connector, id_post, randid, id_variation) {


        var cookie_name = "hc_lb_" + id_connector;
        var hcreferer = hybridconnect_getCookie("hc_referer");
        var hctracker = hybridconnect_getCookie("hc_tracking");
        var hcrefererdomain = hybridconnect_getCookie("hc_referer_domain");
        if (id_connector == 0) {
            hc_show_loader(null, "close");
            return;
        }

        // set name to null if email only
        if(jQuery("input#hc_hidden_emailonly" +randid).val()=="1") { name=""; }

        var hc_ajax_nonce = jQuery('#hc_hidden_fb_ajaxnonce').val();
        var data = {
            action: 'hc_frontend_submit_connector',
            id_connector: id_connector,
            id_post: id_post,
            name: name,
            email: email,
            id_variation: id_variation,
            referer: hcreferer,
            trackingcode: hctracker,
            referingdomain: hcrefererdomain,
            security: hc_ajax_nonce
        };
        jQuery.post(ajaxurl, data, function(response) {
            //hide loading bar
           hc_show_loader(null, "close");
            if (response <= 0) {
              hc_show_loader(null, "error");
            } else {
            // valid submission - cookies and tracking code
                var mycookie = jQuery("#hc_hidden_lightbox_on" + randid).val();
                if (mycookie == 1){ hybridconnect_setCookie(cookie_name, "enabled", 720); }
                hybridconnect_setCookie("hc_tracking_subscribed",hctrack,730);
                hybridconnect_setCookie("hc_emailonly",jQuery("input#hc_hidden_emailonly" + randid).val(),730);

                // confused with the response - show error!
                if(response.type=="undefined") { hc_show_loader(null, "error"); return false; }

                // if API submit then redirect to ty page (legacy code - leave alone);
                if(response.type=="api") {
                  window.location = response.typage;
                }

                // custom html submission
                if(response.type=="custom") {

                   var mailListCode=jQuery("div#customsubmit" + randid);
                   var thisForm = jQuery(mailListCode).find("form");

                   // if no form code found then redirect straight to thank you page for wysija, g2w and blog subscriptions
                   if(!jQuery(thisForm).length) {
                     window.location=response.typage;
                     return false;
                   }

                   // else set jquery bind to prevent manual redirect if custom thank you page set
                   if(response.typage) {
                   if(response.typage=="%samepage%") { response.typage = window.location.href; }
                    jQuery(mailListCode).find("form").submit( function (ev) {
                    ev.preventDefault();
                    var formdata = jQuery(this).serialize();
                    jQuery.ajaxSetup({ cache: false });
                    jQuery.ajax({
                    type: "GET",
		                dataType: "jsonp",
                    url: jQuery(mailListCode).find("form").attr("action"),
                    data: formdata,
                    async: false,
                    cache: false,
                    complete: function() {
                     window.location=response.typage;
                    }
                    });
                    });
                  }

                   if(jQuery("input#hc_hidden_emailonly" + randid).val()=="0") {
                     // this is a name and email submit
                     jQuery.fn.exists = function(){return this.length>0;}
                        var findName = jQuery(mailListCode).find("input[name*=name], input[name*=NAME], input[name*=Name]").not("input[type=hidden]").val(name);
                         if (!(jQuery(findName).exists())) {
                          jQuery(mailListCode).find("input[type=text], input[type=email]").not("input[type=hidden]").first().val(name);
                         }
                         var findEmail = jQuery(mailListCode).find("input[name*=email], input[name*=EMAIL], input[type=email], input[name=eMail]").not("input[type=hidden]").val(email);
                         if (!(jQuery(findEmail).exists())) {
                          jQuery(mailListCode).find("input[type=text], input[type=email]").not("input[type=hidden]").last().val(email);
                         }
                         jQuery(mailListCode).find("input[type=submit], button, input[name=submit]").trigger('click');
                   }
                    else {
                      // this is a email only submit
                      jQuery(mailListCode).find("input").not("input[type=hidden]").val(email);
                      jQuery(mailListCode).find("input[type=submit], button, input[name=submit]").trigger('click');
                    }
                }
            }
        }, "json" );
    }

    function hcfindUrls(text)
{
    var source = (text || '').toString();
    var urlArray = [];
    var url;
    var matchArray;

    // Regular expression to find FTP, HTTP(S) and email URLs.
    var regexToken = /(((ftp|https?):\/\/)[\-\w@:%_\+.~#?,&\/\/=]+)|((mailto:)?[_.\w-]+@([\w][\w\-]+\.)+[a-zA-Z]{2,3})/g;

    // Iterate through any URLs in the text.
    while( (matchArray = regexToken.exec( source )) !== null )
    {
        var token = matchArray[0];
        urlArray.push( token );
    }
    return urlArray;
}

    function hc_update_variation_views(id_variation) {
        var hc_ajax_nonce = jQuery('#hc_hidden_fb_ajaxnonce').val();
        var data = {
            action: 'hc_frontend_update_views',
            id_variation: id_variation,
            security: hc_ajax_nonce
        };
        jQuery.post(ajaxurl, data, function(response) {
        });
    }


    function checkURL(value) {
        var urlregex = new RegExp("^(http:\/\/www.|https:\/\/www.|ftp:\/\/www.|www.){1}([0-9A-Za-z]+\.)");
        if (urlregex.test(value)) {
            return (true);
        }
        return (false);
    }

    function del_cookie(name) {
    document.cookie = name + '=; expires=Thu, 01 Jan 1970 00:00:01 GMT;';
    }

    function hc_isValidEmailAddress(emailAddress) {

        var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
        return pattern.test(emailAddress);
    }

    function showLightBox(mytempid, animation, repeat) {

        var popupBox = jQuery("#hc_template_lightbox_popup" + mytempid);
        var templateWrapper = ("#hc_template_wrapper" + mytempid);
        var popupOverlay = jQuery("#hc_template_lightbox_overlay" + mytempid);
        var connectorWrapper = jQuery("div.connectorWrapper" + mytempid);

        // check to see which version of template is being displayed
        var hc_display_type = jQuery("#display_type" + mytempid).val();

        switch(hc_display_type) {
         case 'Form' :
          var specificConnectorWrapper = ".hc_shortcode_form" + mytempid;
          break;
         case 'Facebook' :
          if(window.facebookConnected) {
           var specificConnectorWrapper = ".hc_facebook_connected" + mytempid;
          } else {
           var specificConnectorWrapper = ".hc_facebook_not_connected" + mytempid;
          }
          break;
         case 'Hybrid' :
          var specificConnectorWrapper = "." + window.hybridConnectedStatus + mytempid;
          break;
         case 'None' :
         var specificConnectorWrapper = ".hc_facebook_connected" + mytempid;
         break;
         }

        var stopDisplay=false;
        if(!window.hcAlreadyVisible) {

            //check cookies and drop cookie if requested
            var cookieDropped = cookieDropCheck(mytempid);

            // only show connectors if cookie has not been dropped or if repeat == "yes". If repeat=="yes" then trigger must have been a click and in this case cookies should be ignored.
            if(!cookieDropped || repeat=="yes") {
                if(repeat=="no") {
                    //check to see if already fired - to prevent showing the same popup with different triggers over and over again
                    for (var j = 0; j < connectorsAlreadyDisplayed.length; j++) {
                        if(mytempid==connectorsAlreadyDisplayed[j]) { stopDisplay=true; }
                    }
                }

                if(stopDisplay!=true)  {
                    // build array of displayed connectors only if repeat=no
                    if(repeat=="no") { connectorsAlreadyDisplayed.push(mytempid); }

                    if(!animation) { animation = "fade"; }

                    // if it's a lightbox then it will be fade and will always positioned in centre of screen
                    if(animation=="fade") {

                        var centreCoords = calculateCentreOfScreen(specificConnectorWrapper);
                        jQuery(templateWrapper).css({'position':'fixed', 'left':centreCoords[0] + 'px', 'top':centreCoords[1] + 'px'});

                        // if not already visible then animate
                        var fadeSpeed=parseInt(jQuery("#hc_hidden_fade_in_speed" + mytempid).val());

                        if(!(jQuery(popupBox).is(':visible'))) { jQuery(popupBox).fadeIn(fadeSpeed); }
                        if(!(jQuery(templateWrapper).is(':visible'))) { jQuery(templateWrapper).fadeIn(fadeSpeed); }
                        if(!(jQuery(popupOverlay).is(':visible'))) { jQuery(popupOverlay).fadeIn(fadeSpeed); }
                        if(!(jQuery(connectorWrapper).is(':visible'))) { jQuery(specificConnectorWrapper).fadeIn(fadeSpeed); }

                        // make sure that two lightboxes or popups are NEVER displayed at the same time setting global var
                        window.hcAlreadyVisible=true;
                        // set close triggers on close button
                        jQuery(specificConnectorWrapper + " .lightbox-close, #hc_template_lightbox_overlay" + mytempid).click(function(ev) {
                          ev.preventDefault();
                          jQuery(popupBox).fadeOut(fadeSpeed);
                          jQuery(templateWrapper).fadeOut(fadeSpeed);
                          jQuery(popupOverlay).fadeOut(fadeSpeed);
                          jQuery(specificConnectorWrapper).fadeOut(fadeSpeed);
                          window.hcAlreadyVisible=false;
                          var copyLightboxContents = jQuery(specificConnectorWrapper).find(".connectorDescriptionText").html();
                            jQuery(specificConnectorWrapper).find(".connectorDescriptionText").html("");
                            jQuery(specificConnectorWrapper).find(".connectorDescriptionText").html(copyLightboxContents);
                          // unbind clicks each iteration
                          jQuery(specificConnectorWrapper + " .lightbox-close, #hc_template_lightbox_overlay" + mytempid).off('click');
                        });
                    }

                    if(animation=="animate") {
                        var position = jQuery("#optin_slide_in_from" + mytempid).val();
                        var startCSSAtt = jQuery("#optin_start_pos_attribute"+ mytempid).val();
                        var startCSSVal = jQuery("#optin_start_pos_value" + mytempid).val();
                        var aniDuration = jQuery("#optin_ani_duration" + mytempid).val();
                        var optinSlideDistance = jQuery("#optin_slide_in_distance" + mytempid).val();

                        // check not already visible.  If alreayd visible then no animation.
                        if(!(jQuery(connectorWrapper).is(':visible'))) {

                            // set start position CSS
                            jQuery(connectorWrapper).css('position','fixed');
                            jQuery(connectorWrapper).css(position,'-1000px');
                            jQuery(connectorWrapper).css(startCSSAtt,startCSSVal);

                            // show elements
                            jQuery(specificConnectorWrapper).show();
                            jQuery(popupBox).show();
                            jQuery(templateWrapper).show();

                            // build dynamic config from settings
                            var aniArgs = {};
                            aniArgs[position] = optinSlideDistance;

                            //animate
                            jQuery(specificConnectorWrapper).animate(aniArgs,aniDuration);

                            // make sure that two lightboxes or popups are NEVER displayed at the same time by setting global var
                            window.hcAlreadyVisible=true;

                            // set the close triggers
                            jQuery(specificConnectorWrapper + " .lightbox-close").click(function(ev) {
                              ev.preventDefault();
                              var exitAniArgs = new Array;
                              var exitDirection = jQuery("#optin_slide_in_from" + mytempid).val();
                              exitAniArgs[exitDirection] = '-1000px';
                             jQuery(specificConnectorWrapper).animate(exitAniArgs,aniDuration, 'linear', function(){ jQuery(this).hide(); } );
                            window.hcAlreadyVisible=false;
                            var copyLightboxContents = jQuery(specificConnectorWrapper).find(".connectorDescriptionText").html();
                            jQuery(specificConnectorWrapper).find(".connectorDescriptionText").html("");
                            jQuery(specificConnectorWrapper).find(".connectorDescriptionText").html(copyLightboxContents);
                            jQuery(specificConnectorWrapper + " .lightbox-close").off("click");
                        });

                        }
                    }
                    // end of stop display check
                }
            } else { stopDisplay=true; }
        } else { }
    }

    function calculateCentreOfScreen(specificConnectorWrapper) {

        var viewport_width = jQuery(window).width();
        var viewport_height = jQuery(window).height();
        var dialog_width = jQuery(specificConnectorWrapper).css('width');
        var dialog_height = jQuery(specificConnectorWrapper).css('height');
        if (dialog_width == null || dialog_height == null) {
         console.log("Hybrid Connect error: can't load lightbox because can't find connector");
         return false;
        }
        dialog_width = dialog_width.replace("px", "");
        dialog_height = dialog_height.replace("px", "");
        var my_center = (viewport_width - dialog_width) / 2;
        var my_middle = (viewport_height - dialog_height) / 2;
        var centreScreen = new Array;
        centreScreen[0]=my_center;
        centreScreen[1]=my_middle;
        return centreScreen;
    }

    function cookieDropCheck(mytempid) {

        // get and set relevant cookie variables
        var cookieDropped=false;
        var cookie_enabled=jQuery("#hc_hidden_cookie_enable" + mytempid).val();
        var current_connector_id = jQuery("#hc_hidden_this_connector_id" + mytempid).val();
        var cookie_name = "hc_lb_" + current_connector_id;

        // if cookies enabled then try to get cookie
        var temp_cookie = hybridconnect_getCookie(cookie_name);
        if (temp_cookie != null && temp_cookie == "enabled") {
            //   console.log("cookie dropped");
            cookieDropped=true;
        } else {
            // no cookie dropped.  Set cookie if cookies enabled
            if (cookie_enabled == 1) {
                var ex_days = jQuery("#hc_hidden_cookie_life" + mytempid).val();
                hybridconnect_setCookie(cookie_name, "enabled", ex_days);
            }
        }
        return cookieDropped;
    }

    // function to check input data from email or custom hybrid connectors
    function hybridCheckInputData(hc_hidden_connector_randid) {
       var email = jQuery('#hc_txt_email' + hc_hidden_connector_randid).val();
       var name  = jQuery('#hc_txt_name' + hc_hidden_connector_randid).val();
       var thisEmailOnly = jQuery("input#hc_hidden_emailonly" +hc_hidden_connector_randid).val();
       if ((name == '' && thisEmailOnly=="0") || email == '' || (name == 'Name' && thisEmailOnly=="0") || email == 'Email') {
         if(thisEmailOnly=="0"){
        hc_show_loader(null, "nameandemail");
         } else {
          hc_show_loader(null, "email");
         }
         return false;
            }
            if (!hc_isValidEmailAddress(email)) {
               hc_show_loader(null, "validemail");
              return false;
            }
            return true;
    }
function hc_show_loader(wrapper, type) {

 if(wrapper) {
   // this is the signing up loading icon
      jQuery(wrapper).find(".loading-icon").show();

 } else {
   // error lightbox
        if(type=="error") { alert(window.error); }
        if(type=="nameandemail") { alert(window.nameandemail); }
        if(type=="email") { alert(window.email); }
        if(type=="validemail") { alert(window.validemail); }
    }
    }
    function hc_make_connector_responsive(id) {
        var i_min_w = jQuery("#hc_hidden_img_min_width" + id).val() + "px";
        var i_max_w = jQuery("#hc_hidden_img_max_width" + id).val() + "px";
        var i_min_h = jQuery("#hc_hidden_img_min_height" + id).val() + "px";
        var i_max_h = jQuery("#hc_hidden_img_max_height" + id).val() + "px";
        var c_min_w = jQuery("#hc_hidden_con_min_width" + id).val() + "px";
        var c_max_w = jQuery("#hc_hidden_con_max_width" + id).val() + "px";
        jQuery("div.connectorWrapper" + id).css("min-width", c_min_w);
        jQuery("div.connectorWrapper" + id).css("max-width", c_max_w);
        jQuery("div.graphicWrapper" + id).css("min-width", i_min_w);
        jQuery("div.graphicWrapper" + id).css("max-width", i_max_w);
        jQuery("div.graphicWrapper" + id).css("min-height", i_min_h);
        jQuery("div.graphicWrapper" + id).css("max-height", i_max_h);
        jQuery("div.connectorWrapper" + id).css("width","100%");
        jQuery("div.connectorWrapper" + id).css("height","100%");
        jQuery("div.graphicWrapper" + id).css("width","40%");
        jQuery("img.optinGraphic" + id).css("width","100%");
        jQuery("div.optinWrapper" + id).css("position","relative");
        jQuery("div.optinWrapper" + id).css("height","");
        jQuery("div.optinWrapper" + id).css("overflow","hidden");
        jQuery("div.connectorDescriptionText" + id).css("height","");
    }