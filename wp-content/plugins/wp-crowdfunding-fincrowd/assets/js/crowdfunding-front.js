/* ========================================================================
 * Neo Crowdfunding
 * ======================================================================== */
jQuery(document).ready(function($){

    //Google place autocomplete for adresse
    if (typeof google !== 'undefined') {
      var input = document.getElementById('fi_user_address');
      var autocomplete = new google.maps.places.Autocomplete(input);
    }

    var count = 0;
    var numItems = $('.wpneo-block').length;
    if(numItems!=0){ count = numItems; }

    $.fn.createNewForm = function( count ){
        return this.each(function(){
            $(this).find('input,textarea,select').each(function(){
                var $that = $(this);
                $that.attr('name', this.name.replace(/\d/, count ));
                $that.val('');
            });
        });
    };
    $('.add-new').on('click', function(e){
        var $form       = $('.wpneo-block').last(),
            $cloned     = $form.clone();
        $cloned.createNewForm(count);
        $('#wpneo-clone .add-new').before( $($cloned) );
        count = count+1;
    });
    $(document).on('click','.remove-button',function(events){
        if($('.wpneo-block').length > 1){
            $(this).parent('.wpneo-block').remove();
        }
    });
    $('#wpneo_form_start_date, #wpneo_form_end_date').datepicker({
        dateFormat : 'dd-mm-yy'
    });

    // POST Insert
    $('#wpneofrontenddata').submit(WpneoAjaxSubmitFrontend);
    function WpneoAjaxSubmitFrontend(){
        //tinyMCE.triggerSave();//fincrowd no more tiny mce
        var wpneofrontenddata = $(this).serialize();
        $.ajax({
            type:"POST",
            url: ajax_object.ajax_url,
            data: wpneofrontenddata,
            success:function(data){
                //console.log("Data Send Done!!");
                if (wpneo_crowdfunding_modal(data)){

                }
            },
            error: function(jqXHR, textStatus, errorThrown){
                //console.log('Data Not Send!');
                wpneo_crowdfunding_modal({'success':0, 'message':'Error sending data'})
            }
        });
        return false;
    }

    function wpneo_crowdfunding_modal(data){
        var data = JSON.parse(data);

        var html = '<div class="wpneo-modal-wrapper"> ' +
            ' <div class="wpneo-modal-content"> ' +
            '<div class="wpneo-modal-wrapper-head">' +
            '<h4 id="wpneo_crowdfunding_modal_title">Message</h4>' +
            ' <a href="javascript:;" class="wpneo-modal-close">&times;</a>' +
            '</div>' +
            '<div class="wpneo-modal-content-inner">' +
            '<p id="wpneo_crowdfunding_modal_message"></p>' +
            '</div>' +
            '</div>' +
            '</div>';

        //FIncrowd fix (not sure if impact somewhere else)
        if ($('.wpneo-modal-wrapper').length != 0){
          $('.wpneo-modal-wrapper').remove();
        }

        $('body').append(html);

        if (data.redirect){
            if ( $('#wpneo_crowdfunding_redirect_url').length == 0 ){
                $('body').append('<input type="hidden" id="wpneo_crowdfunding_redirect_url" value="'+data.redirect+'" />');
            }
        }

        if (data.success == 1){
            $('.wpneo-modal-wrapper #wpneo_crowdfunding_modal_message').html(data.message);
            $('.wpneo-modal-wrapper').css({'display': 'block'});
            //$("#wpneofrontenddata")[0].reset();//FINCRowd not sure if impact
            return true;
        }else {
            $('.wpneo-modal-wrapper #wpneo_crowdfunding_modal_message').html(data.message);
            $('.wpneo-modal-wrapper').css({'display': 'block'});
            return false;
        }
    }


    function wpneo_upload_image( button_class ) {
        var _custom_media = true,
            _orig_send_attachment = wp.media.editor.send.attachment;
        $('body').on('click',button_class, function(e) {
            var button_id ='#'+$(this).attr('id');
            var button_class ='.'+$(this).attr('id');
            var site_url = $(this).data('url');
            /* console.log(button_id); */
            var self = $(button_id);
            var send_attachment_bkp = wp.media.editor.send.attachment;
            var button = $(button_id);
            var id = button.attr('id').replace('_button', '');
            _custom_media = true;

            wp.media.editor.send.attachment = function(props, attachment){
                if ( _custom_media  ) {
                    var attachment_url = attachment.url;
                    $('.wpneo-form-image-url').val(attachment_url);
                    $('.wpneo-form-image-id').val(attachment.id);
                } else {
                    return _orig_send_attachment.apply( button_id, [props, attachment] );
                }
            };
            wp.media.editor.open(button);
            return false;
        });
    }
    wpneo_upload_image('.wpneo-image-upload');

    $(document).on('click','.add_media', function(){
        _custom_media = false;
    });

    $('.wpneoShowLogin').on('click', function (e) {
        e.preventDefault();
        $('.wpneo_login_form_div').slideToggle();
    });

    //Repeatable Rewards
    function countRemovesBtn(btn) {
        var rewards_count = $(btn).length;
        if (rewards_count > 1){
            $(btn).show();
        }else {
            $(btn).hide();
            if (btn == '.removeCampaignRewards') {
                $('.reward_group').show();
            }
            if (btn == '.removecampaignupdate') {
                $('#campaign_update_field').show();
            }
        }
        $(btn).first().hide();
    }
    //Add rewards
    $('#addreward').on('click', function (e) {
        e.preventDefault();
        var wpneo_rewards_fields = $('.reward_group').html();
        $('#rewards_addon_fields').append(wpneo_rewards_fields);
        $('#rewards_addon_fields .campaign_rewards_field_copy:last-child').find('input,textarea,select').each(function(){
            if ( ($(this).attr('name') != 'remove_rewards')&&($(this).attr('type') != 'button') ){
                $(this).val('');
            }
        });
        countRemovesBtn('.removeCampaignRewards');
    });

    $('body').on('click', '.removeCampaignRewards', function (e) {
        e.preventDefault();
        $(this).closest('.campaign_rewards_field_copy').html('');
        countRemovesBtn('.removeCampaignRewards');
    });
    countRemovesBtn('.removeCampaignRewards');

    //Repeatable Update
    //Add More Campaign Update Field
    $('#addcampaignupdate').on('click', function (e) {
        e.preventDefault();
        var wpneo_update_fields = $('#campaign_update_field').html();
        $('#campaign_update_addon_field').append(wpneo_update_fields);
        countRemovesBtn('.removecampaignupdate');
    });

    $('body').on('click', '.removecampaignupdate', function (e) {
        e.preventDefault();
        $(this).closest('.campaign_update_field_copy').html('');
        countRemovesBtn('.removecampaignupdate');
    });
    countRemovesBtn('.removecampaignupdate');

    //wpneo_active_edit_form
    $('#wpneo_active_edit_form').on('click', function(e){
        e.preventDefault();
        $('#wpneo_update_display_wrapper').hide();
        $('#wpneo_update_form_wrapper').fadeIn('slow');
    });

    // Edit Enable
    $('#wpneo-edit').on('click', function (e) {
        e.preventDefault();
        $('#wpneo-edit').hide();
        $('.wpneo-content input,.wpneo-content textarea,.wpneo-content select').not('.wpneo-content input[name="username"]').removeAttr("disabled").css( "border", "1px solid #dfe1e5" );
        $('.wpneo-save-btn').delay(100).fadeIn('slow');
        $('.wpneo-cancel-btn').delay(100).fadeIn('slow');
    });

    // Dashboard Data Save
    function wpneo_data_dashboard_data_save(){
        var return_data;
        var postdata = $('#wpneo-dashboard-form').serializeArray();
        $.ajax(
            {
                async: false,
                url : ajax_object.ajax_url,
                type: "POST",
                data : postdata,
                success:function(data, textStatus, jqXHR) {
                    //console.log(postdata);
                    //$('.wp-neo-modal-wrapper.wpneo-success').css({'display': 'block'});
                    wpneo_crowdfunding_modal(data);
                    return_data = data;
                },
                error: function(jqXHR, textStatus, errorThrown){
                    //console.log('Data Not Send!');
                    //$('.wp-neo-modal-wrapper.wpneo-error').css({'display': 'none'});
                    wpneo_crowdfunding_modal({'success':0, 'message':'Error sending data'})
                }
            });

        $('.wpneo-content input,.wpneo-content textarea,.wpneo-content select').attr("disabled","disabled").css( "border", "none" );
        $('.wpneo-cancel-btn').hide();
        $('#wpneo-edit').delay(100).fadeIn('slow');

        return return_data;
    }

    $('.wpneo-cancel-btn').on('click', function(e){
        e.preventDefault();
        $('.wpneo-content input,.wpneo-content textarea,.wpneo-content select').attr("disabled","disabled").css( "border", "none" );
        $('.wpneo-cancel-btn').hide();
        $('#wpneo-dashboard-save').hide();
        $('#wpneo-profile-save').hide();
        $('#wpneo-contact-save').hide();
        $('#wpneo-edit').delay(100).fadeIn('slow');
    });
    // Dashboard Froentend ( Dashboard )
    $('#wpneo-dashboard-save').on('click', function (e) {
        e.preventDefault(); //STOP default action
        var postdata = $('#wpneo-dashboard-form').serializeArray();
        wpneo_data_dashboard_data_save();
    });

    // Dashboard Froentend ( Profile )
    $('#wpneo-profile-save').on('click', function (e) {
        e.preventDefault(); //STOP default action
        wpneo_data_dashboard_data_save();
    });

    // Dashboard Froentend ( Contact )
    $('#wpneo-contact-save').on('click', function (e) {
        e.preventDefault(); //STOP default action
        wpneo_data_dashboard_data_save();
        console.log('asd');
    });

    // Dashboard Froentend ( Password )
    $('#wpneo-password-save').on('click', function (e) {
        e.preventDefault(); //STOP default action
        wpneo_data_dashboard_data_save();
        console.log('asd');
    });
    // Dashboard Froentend ( Update )
    $('#wpneo-update-save').on('click', function (e) {
        e.preventDefault(); //STOP default action
        var return_respone = wpneo_data_dashboard_data_save();
        wpneo_crowdfunding_modal(return_respone);
    });

    //-------------------------------------
    // Tab
    //-------------------------------------
    $('.wpneo-tabs-menu a').on("click", (function (e) {
        e.preventDefault();
        $('.wpneo-tabs-menu li').removeClass('wpneo-current');
        $(this).parent().addClass('wpneo-current');
        var currentTab = $(this).attr('href');
        $('.wpneo-tab-content').hide();
        $(currentTab).fadeIn();
        return false;
    }));
    $($('.wpneo-current a').attr('href')).fadeIn();

    //-------------------------------------
    //Modal
    //-------------------------------------
    $('.wpneo-fund-modal-btn').on('click', function (e) {
        e.preventDefault();
        $('.wpneo-modal-wrapper').css({'display': 'block'});
    });
    $(document).on('click', '.wpneo-modal-close', function(){
        $('.wpneo-modal-wrapper').css({'display': 'none'});

        if ( $('#wpneo_crowdfunding_redirect_url').length > 0 ) {
            location.href = $('#wpneo_crowdfunding_redirect_url').val();
        }
    });


    var currentRequest = null;
    $('input[name="wpneo_donate_amount_field"]').on('keyup', function(){
        var input_price = $(this).val();
        var min_price = $(this).data('min-price');
        var max_price = $(this).data('max-price');
        if (input_price < min_price){
            if(min_price){
                //$(this).val( min_price );
                $('.wpneo-tooltip-min').css({'visibility': 'visible'});
                $('#wpneo-fi-donate-button').prop("disabled",true);
            }
        }else if (max_price < input_price){
            if(max_price){
                //$(this).val( max_price );
                $('.wpneo-tooltip-max').css({'visibility': 'visible'});
                $('#wpneo-fi-donate-button').prop("disabled",true);
            }
        } else {
            $('.wpneo-tooltip-min,.wpneo-tooltip-max').delay( 500 ).css({'visibility': 'hidden'});
            $('#wpneo-fi-donate-button').prop("disabled",false);
            //Si montant ok, pré-calcul des intérêts
            //DESACTIVER PRE CALCUL DES intérêts, TROP lent TOUT est dans le page suivante
            $('#wpneo-fi-total-interest').html('');
            $('.wpneofiloader').show();
            var campaign_id = $(this).data('campaign-id');
            currentRequest = $.ajax(
                      {
                          async: true,
                          url : ajax_object.ajax_url,
                          beforeSend : function()    {
                              if(currentRequest != null) {
                                  currentRequest.abort();
                              }
                          },
                          type: "POST",
                          data: {'action': 'wpneo_fi_compute_interest', 'campaign_id': campaign_id, 'total': $(this).val() },
                          success:function(data, textStatus, jqXHR) {
                              //wpneo_crowdfunding_modal(data);
                              //return_data = data;
                              $('.wpneofiloader').hide();
                              isComputingInterest = null;
                              if( data != 0) {
                                $('#wpneo-fi-total-interest').html(data);
                              }


                          },
                          error: function(jqXHR, textStatus, errorThrown){
                            //$('.wpneofiloader').hide();
                            //todoHIDE IF NOT ABORT
                              //wpneo_crowdfunding_modal({'success':0, 'message':'Error sending data'})
                              //TODO fincrowd error management
                          }
                      });

        }
    });

    $(document).on('click', '#love_this_campaign', function () {
        var campaign_id = $(this).data('campaign-id');
        $.ajax({
            type:"POST",
            url: ajax_object.ajax_url,
            data: {'action': 'love_campaign_action', 'campaign_id': campaign_id},
            success:function(data){
                data = JSON.parse(data);
                if (data.success == 1){
                    $('#campaign_loved_html').html(data.return_html);
                }

            },
            error: function(jqXHR, textStatus, errorThrown){
                wpneo_crowdfunding_modal({'success':0, 'message':'Error'})
            }
        });
    });

    $(document).on('click', '#remove_from_love_campaign', function () {
        var campaign_id = $(this).data('campaign-id');
        $.ajax({
            type:"POST",
            url: ajax_object.ajax_url,
            data: {'action': 'remove_love_campaign_action', 'campaign_id': campaign_id},
            success:function(data){
                data = JSON.parse(data);
                $('#campaign_loved_html').html(data.return_html);
            },
            error: function(jqXHR, textStatus, errorThrown){
                //wpneo_crowdfunding_modal({'success':0, 'message':'Error'})
            }
        });
    });

    $(document).on('click', '#user-registration-btn', function (e) {
        e.preventDefault();
        var registration_form_data = $(this).closest('form').serialize();
        $.ajax({
            type:"POST",
            url: ajax_object.ajax_url,
            data: registration_form_data,
            success:function(data){
                wpneo_crowdfunding_modal(data)
            },
            error: function(jqXHR, textStatus, errorThrown){
                wpneo_crowdfunding_modal({'success':0, 'message':'Error'})
            }
        });
    });





    var image = $('input[name=wpneo-form-image-url]').val();
    if(image!=''){
        $('#wpneo-image-show').html('<img width="150" src="'+image+'" />');
    }
    $(document).on('click','.media-button-insert',function(e){
        var image = $('input[name=wpneo-form-image-url]').val();
        if(image!=''){
            $('#wpneo-image-show').html('<img width="150" src="'+image+'" />');
        }
    });

    // Hide Billing and Shipping Information
    if( $('body.woocommerce-checkout').length >= 1 ){
        if( $('#billing_email').length < 1 ){
            $('#customer_details').css({'display': 'none'});
        }
    }

    // Form Reward Image Upload
    $('body').on('click','.wpneo-image-upload-btn',function(e) {
        e.preventDefault();
        var that = $(this);

        var image = wp.media({
            title: 'Upload Image',
            multiple: false
        }).open()
            .on('select', function(e){
                var uploaded_image = image.state().get('selection').first();
                var uploaded_url = uploaded_image.toJSON().url;
                uploaded_image = uploaded_image.toJSON().id;
                $(that).parent().find( '.wpneo_rewards_image_field' ).val( uploaded_image );
                $(that).parent().find( '.wpneo_rewards_image_field_url' ).val( uploaded_url );
            });
    });

    $('body').on('click','.wpneo-image-remove',function(e) {
        var that = $(this);
        $(that).parent().find( 'wpneo_rewards_image_field_url' ).val( '' );
        $(that).parent().find( '.wpneo_rewards_image_field' ).val( '' );
        //$(that).parent().find( '.wpneo-image-container' ).html( '' );
    });

    // Reward On click
    $('body').on('click','.price-value-change',function(e) {
        e.preventDefault();
        var reward = $(this).data('reward-amount');
        $("html, body").animate({ scrollTop: 0 }, 600,
            function() {
                setTimeout(function(){
                    $(".wpneo_donate_amount_field").addClass("wpneosplash");
                }, 100 );
                setTimeout(function(){
                    $(".wpneo_donate_amount_field").val( reward );
                    $( ".wpneo_donate_amount_field" ).removeClass( "wpneosplash" );
                }, 1000 );
            });
    });


    $(document).on('click','table.reward_table_dashboard tr',function(e) {
        $(this).find('.reward_description').slideToggle();
    });

    //Fincrowd
    //Personne phyisque par défaut
    $('.fi_reg_pp').show();
    $('.fi_reg_pm').hide();
    //Modify set a fields depending on type selected
    $('#fi_reg_physical_person,#fi_reg_society').on('click',function(e) {
      if(e.target.id == 'fi_reg_physical_person'){
        //show pp fields
        $('.fi_reg_pp').show();
        $('.fi_reg_pm').hide();
      } else {
        //hide
        $('.fi_reg_pp').hide();
        $('.fi_reg_pm').show();
        //$('.lname').attr("placeholder", "Type a Location").val("").focus().blur();

      }

    });

    //Interest table created add line ??TODO not working
      // Hide with insurance at first
    $('.fi-interest-insurance-row').hide();

    $('#wpneo_fi_interest_insurance[name="wpneo_fi_interest_insurance"]').change(function(){
      if(this.checked){
        $('.fi-interest-row').hide();
        $('.fi-interest-insurance-row').show();
      } else {
        $('.fi-interest-row').show();
        $('.fi-interest-insurance-row').hide();
      }
    });

    // Dashboard Data Save
      $(document).on('click', '#wpneo_fi_cancel_order', function () {
        var order_id = $(this).data('order-id');
        console.log(order_id);
        $.ajax(
            {
                async: false,
                url : ajax_object.ajax_url,
                type: "POST",
                data: {'action': 'wpneo_fi_cancel_order', 'order_id': order_id},
                success:function(data, textStatus, jqXHR) {
                    wpneo_crowdfunding_modal(data);
                    return_data = data;
                },
                error: function(jqXHR, textStatus, errorThrown){
                    wpneo_crowdfunding_modal({'success':0, 'message':'Error sending data'})
                }
            });
    });

    //compute interests
    // var isComputingInterest = null;
    // $('input[name="wpneo_donate_amount_field"]').on('keyup', function(){
    //     var campaign_id = $(this).data('campaign-id');
    //     $('html, body').css("cursor", "wait");
    //     if( isComputingInterest != null ) {
    //             isComputingInterest.abort();
    //             isComputingInterest = null;
    //     }
    //     isComputingInterest = $.ajax(
    //         {
    //             async: true,
    //             url : ajax_object.ajax_url,
    //             type: "POST",
    //             data: {'action': 'wpneo_fi_compute_interest', 'campaign_id': campaign_id, 'total': $(this).val() },
    //             success:function(data, textStatus, jqXHR) {
    //                 //wpneo_crowdfunding_modal(data);
    //                 //return_data = data;
    //                 $('#wpneo-fi-total-interest').html(data);
    //                 $('html, body').css("cursor", "auto");
    //                 isComputingInterest = null;
    //             },
    //             error: function(jqXHR, textStatus, errorThrown){
    //                 //wpneo_crowdfunding_modal({'success':0, 'message':'Error sending data'})
    //                 //TODO fincrowd error management
    //                 $('html, body').css("cursor", "auto");
    //             }
    //         });
    // });

    // Dashboard Validate campaign
    $(document).on('click', '#wpneo_fi_validate_campaign', function () {
      var campaign_id = $(this).data('campaign-id');
      $.ajax(
          {
              async: false,
              url : ajax_object.ajax_url,
              type: "POST",
              data: {'action': 'wpneo_fi_validate_campaign', 'campaign_id': campaign_id },
              success:function(data, textStatus, jqXHR) {
                  wpneo_crowdfunding_modal(data);
                  return_data = data;
                  location.reload();
              },
              error: function(jqXHR, textStatus, errorThrown){
                  wpneo_crowdfunding_modal({'success':0, 'message':'Error sending data'})
              }
          });
    });

    // Dashboard cancel campaign
    $(document).on('click', '#wpneo_fi_cancel_campaign', function () {
      var campaign_id = $(this).data('campaign-id');
      $.ajax(
          {
              async: false,
              url : ajax_object.ajax_url,
              type: "POST",
              data: {'action': 'wpneo_fi_cancel_campaign', 'campaign_id': campaign_id },
              success:function(data, textStatus, jqXHR) {
                  wpneo_crowdfunding_modal(data);
                  return_data = data;
                  location.reload();
              },
              error: function(jqXHR, textStatus, errorThrown){
                  wpneo_crowdfunding_modal({'success':0, 'message':'Error sending data'})
              }
          });
    });

    // Dashboard mail reminder
    $(document).on('click', '#wpneo_fi_reminder_mail', function () {
      var campaign_id = $(this).data('campaign-id');
      $.ajax(
          {
              async: false,
              url : ajax_object.ajax_url,
              type: "POST",
              data: {'action': 'wpneo_fi_reminder_mail', 'campaign_id': campaign_id },
              success:function(data, textStatus, jqXHR) {
                  wpneo_crowdfunding_modal(data);
                  return_data = data;
                  location.reload();
              },
              error: function(jqXHR, textStatus, errorThrown){
                  wpneo_crowdfunding_modal({'success':0, 'message':'Error sending data'})
              }
          });
    });


});
