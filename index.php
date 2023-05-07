
<!DOCTYPE html>
<html lang="en" >

<head>
  <meta charset="UTF-8">
  

    <link rel="apple-touch-icon" type="image/png" href="https://cpwebassets.codepen.io/assets/favicon/apple-touch-icon-5ae1a0698dcc2402e9712f7d01ed509a57814f994c660df9f7a952f3060705ee.png" />

    <meta name="apple-mobile-web-app-title" content="CodePen">

    <link rel="shortcut icon" type="image/x-icon" href="https://cpwebassets.codepen.io/assets/favicon/favicon-aec34940fbc1a6e787974dcd360f2c6b63348d4b1f4e06c77743096d55480f33.ico" />

    <link rel="mask-icon" type="image/x-icon" href="https://cpwebassets.codepen.io/assets/favicon/logo-pin-b4b4269c16397ad2f0f7a01bcdf513a1994f4c94b8af2f191c09eb0d601762b1.svg" color="#111" />



  
  <title>CodePen - Text hover effect</title>
    <link rel="canonical" href="https://codepen.io/colloque/pen/poOLPg" />
  
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">

  
  
<style>
@import url(https://fonts.googleapis.com/css?family=Dosis:300,400);

body {
  background-color:#222;
  background: url('images/bc1.png') no-repeat center center fixed;
  background-size: cover;
}

.wrapper {
  display: block;
  position: absolute;
  top: 50%;
  left:50%;
  -webkit-transform: translate(-50%,-50%);
}

h1 a {
  color: #222;
  font-size: 2em;
  text-decoration: none;
  display: inline-block;
  position: relative;
  font-family: 'Dosis', sans-serif;
}

/*effect-underline*/
a.effect-underline:after {
	content: '';
  position: absolute;
  left: 0;
  display: inline-block;
  height: 1em;
  width: 100%;
  border-bottom: 1px solid;
  margin-top: 10px;
  opacity: 0;
	transition: opacity 0.35s, transform 0.35s;
	transform: scale(0,1);
}

a.effect-underline:hover:after {
  opacity: 1;
	transform: scale(1);
}

/*effect-box*/
a.effect-box:after,
a.effect-box:before {
	content: '';
  position: absolute;
  left: 0;
  display: inline-block;
  height: 1em;
  width: 100%;
  margin-top: 10px;
  opacity: 0;
	transition: opacity 0.35s, transform 0.35s;
	
}

a.effect-box:before {
  border-left: 1px solid;
  border-right: 1px solid;
	transform: scale(1,0);
}

a.effect-box:after {
  border-bottom: 1px solid;
  border-top: 1px solid;
	transform: scale(0,1);
}

a.effect-box:hover:after,
a.effect-box:hover:before {
  opacity: 1;
	transform: scale(1);
}

/* effect-shine */
a.effect-shine:hover {
  -webkit-mask-image: linear-gradient(-75deg, rgba(0,0,0,.6) 30%, #000 50%, rgba(0,0,0,.6) 70%);
  -webkit-mask-size: 200%;
  -webkit-animation: shine 2s infinite;
          animation: shine 2s infinite;
}

@-webkit-keyframes shine {
  from {
    -webkit-mask-position: 150%;
  }
  
  to {
    -webkit-mask-position: -50%;
  }
}
</style>

  <script>
  window.console = window.console || function(t) {};
</script>

  
  
</head>

<body translate="no">
  <div class="wrapper">
  <h1 align="center"><a href="index2.php" class="effect-underline">Animal Rescue</a></h1>
  <!-- <h1 align="center"><a href="#" class="effect-box">box</a></h1>
  <h1 align="center"><a href="#" class="effect-shine">shine</a></h1> -->
</div>
  
  
  
  
</body>

</html>
