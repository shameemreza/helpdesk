<div style="margin-top:100px;display: block;"><!-- space acting footer --></div>

<script type="text/javascript">
	$(".section#new").click(function () {
    	$(".section_view_current").hide();
		$(".section_view_new").show();
    });

    $(".section#current").click(function () {
    	$(".section_view_current").show();
		$(".section_view_new").hide();
    });

    $("#edit-account").click(function () {
    	$(".home-hero .six#main").toggleClass('offset-by-three');
		$(".home-hero .six#sub").toggle();
    });

    $(document).ready(function() {
    	$('.accordionButton').click(function() {
        	$('.accordionButton').removeClass('on');
			$('.accordionContent').slideUp('fast');

        if($(this).next().is(':hidden') == true) {      
            $(this).addClass('on');
            $(this).next().slideDown('fast');
         } 
          
     });
     
    $('.accordionContent').hide();

    $('.alert').append('<div class="close"><\/div>');

    $('.alert .close').click(function(){
    $(this).parent('.alert').fadeOut('fast');
    });

    });
</script>
</body>
</html>
