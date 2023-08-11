<?php lock(); ?><!DOCTYPE html>

<!-- paulirish.com/2008/conditional-{$static_link}css-vs-css-hacks-answer-neither/ -->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
  <meta charset="utf-8" />

  <!-- Set the viewport width to device width for mobile -->
  <meta name="viewport" content="width=device-width" />

  <title>Skeleton</title>
  
  <!-- Included CSS Files (Compressed) -->
  <link rel="stylesheet" href="{$static_link}css/foundation.min.css">
  <link rel="stylesheet" href="{$static_link}css/app.css">
  <link rel="stylesheet" href="{$static_link}css/light.css">
   
   <!-- CSS hook for modules -->
   {$home_css}

  <script src="{$static_link}js/modernizr.foundation.js"></script>
  
  <!-- HEAD hook for modules for adding META tags for example. For JavaScript please use the 'home_js' hook -->
  {$extended_home_header}
  
</head>
<body>

   <div class="row">
     <div class="twelve columns tc">
       <h2>{$content_title}</h2>
       <p>{$content_subtitle}</p>
     </div>
   </div>

   <div class="row">
      <div class="twelve columns tc">
         {$module_countdown}
      </div>
   </div>
   
   <div class="row">
      <div id="progressbars" class="twelve columns">
         {loop="progressbar"}
         <div data-percentage="{$value.percentage}">
            <span class="progress_label">{$value.label}</span>
            <span class="percentage">{$value.percentage}%</span>
            <div></div>
         </div>
         {/loop}
      </div>
   </div>
   
   <div class="row">
      <div class="twelve columns">
         {$content_some_text}
      </div>
   </div>
   
   <form id="subscribe" class="row collapse">
         <input type="hidden" name="action" value="add_subscriber" />
         <div class="ten mobile-three columns">
            <input type="text" name="email" placeholder="{$content_email_placeholder}" />
         </div>
         <div class="two mobile-one columns">
            <button type="submit" class="button expand postfix">Submit</button>
         </div>
         <div id="msg" class="twelve columns"></div>         
   </form>
   
   <div class="row">
      <div id="social_links" class="twelve columns tc">
         
         {loop="social_links"}
         {if="$value.supported"}
         <a href="{$value.value}" class="button {$value.key}">{$value.title}</a> 
         {/if}
         {/loop}
         
      </div>
   </div>
  
  <!-- Included jQuery -->
  <script src="{$static_link}js/jquery.js"></script>
  
  <!-- Included JS Files (Compressed) -->
  <script src="{$static_link}js/foundation.min.js"></script>
  
  <!-- Initialize JS Plugins -->
  <script src="{$static_link}js/app.js"></script>
  
  <!-- JS hook for modules (for JS file include) -->
  {$home_js}
  
  <!-- JS hook for modules (for inline JS include, or HTML) -->
   {$extended_home_footer}
  
</body>
</html>
