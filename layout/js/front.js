$(function(){

        'use strict';

        $('.login-page h1 span').click(function(){
            $(this).addClass('selected').siblings().removeClass('selected') ;

            $('.login-page form').hide()

            $('.' + $(this).data('class')).fadeIn(100);
        })

      


        // for select
        $("select").selectBoxIt({
            autoWidth: false 
        });
        // hide placeholder on form focus
        $('[placeholder]').focus(function(){ 

            $(this).attr('data-text',$(this).attr('placeholder'));

            $(this).attr('placeholder','');
        }).blur(function(){
            $(this).attr('placeholder',$(this).attr('data-text'))
        })


        // add asterisk on required filed 
        $('input').each(function(){
          if(  $(this).attr('required') === 'required') {
              $(this).after('<span class = "asterisk">*</span>')
          }
        }) 
      

        // confirmation masseg on button 

        $('.confirm').click(function(){
            return confirm('Are you shor');
        }) ;

        // dor add new item to show the item in the same time when i insert the data

        $('.live').keyup(function(){
            $($(this).data('class')).text($(this).val()) ;
        })
     









})

// ==========================
