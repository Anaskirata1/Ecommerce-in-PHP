$(function(){

        'use strict';

        // dashbpard

        $('.toggle-info').click(function () {
            $(this).toggleClass('selected').parent().next('.panel-body').toggle(200);

            if((this).hasClass('selected')) {
                $(this).html('<i class="fa fa-minus fa-lg"></i>') ;
            } else {
                $(this).html('<i class="fa fa-plus fa-lg"></i>') ;
            }
        }) ;

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
      

        // convert password field to text field on hover 

        var passfield = $('.pass') 
        $('.show-pass').hover(function(){
            passfield.attr('type' , 'text')
            
        } , function(){
            passfield.attr('type' , 'password')

        }) ;

        // confirmation masseg on button 

        $('.confirm').click(function(){
            return confirm('Are you shor');
        }) ;


        // category full view 
        $('.cat h3').click(function(){
            $(this).next('.full-view').fadeToggle(500);
        }) ;


        $('.option span').click(function(){
            $(this).addClass('active').siblings('span').removeClass('active');

            if($(this).data('view') === 'full') {
                $('.cat .full-view').fadeIn(200) ;
            } else {
                $('.cat .full-view').fadeOut(200) ;
            }
        })

        // show link delete
        $('.child-link').hover(function(){
            $(this).find('.show-delete').fadeIn(400);
        }, function(){
            $(this).find('.show-delete').fadeOut(400);
        })








})

// ==========================
