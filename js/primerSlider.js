jQuery(document).ready(function($) {
    
           $('#myCarousel2').carousel({
                   interval: 5000
           });
    
           //Handles the carousel thumbnails
          $('[id^=carousel-selector-]').click( function(){
               var id = this.id.substr(this.id.lastIndexOf("-") + 1);
               var id = parseInt(id);
               $('#myCarousel2').carousel(id);
           });
          
   });
   