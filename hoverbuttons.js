const navbarButton = document.getElementsByClassName('.main-navbar-button');

var tween = TweenMax.to(this, 0.5, {backgroundImage:"linear-gradient(45deg,rgb(102, 74, 205),rgb(170, 154, 226))"});

$( ".main-navbar-button" ).hover(
  function() {
    tween.play()
    //$( this ).append( $( "<span> ***</span>" ) );

  }, function() {

    TweenMax.to(this, 0.5, {background:"linear-gradient(45deg, rgb(21, 21, 21),rgb(18, 18, 18) ));"});
   // $( this ).find( "span" ).last().remove();
    
  }
);



//  TweenMax.to(this, 0.5, {background:"linear-gradient(45deg,rgb(102, 74, 205),rgb(170, 154, 226))"});
//  TweenMax.to(this, 0.5, {background:"linear-gradient(45deg, rgb(21, 21, 21),rgb(18, 18, 18) ));"});