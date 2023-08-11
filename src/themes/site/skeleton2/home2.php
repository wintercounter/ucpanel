<?php lock(); ?><!DOCTYPE html>

<!-- paulirish.com/2008/conditional-{$theme_url}css-vs-css-hacks-answer-neither/ -->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
  <meta charset="utf-8" />

  <!-- Set the viewport width to device width for mobile -->
  <meta name="viewport" content="width=device-width" />

  <title>Skeleton2</title>
  
  <!-- Included CSS Files (Compressed) -->
  <link rel="stylesheet" href="{$theme_url}css/foundation.min.css">
  <link rel="stylesheet" href="{$theme_url}css/app.css">
  <link rel="stylesheet" href="{$theme_url}css/{$skeleton2_color_scheme}.css">
   
   <!-- CSS hook for modules -->
   {$home_css}

  <script src="{$theme_url}js/modernizr.foundation.js"></script>
  
  <!-- HEAD hook for modules for adding META tags for example. For JavaScript please use the 'home_js' hook -->
  {$extended_home_header}
  
</head>
<body>
            
  {if="$skeleton2_blocks_title == 'true' || $skeleton2_blocks_subtitle == 'true' || $skeleton2_blocks_logo == 'true'"}
   <div class="row">
     <div class="twelve columns tc">
       {if="$skeleton2_blocks_logo == 'true'"}<img alt="logo" src="{$full_url}{$skeleton2_logo}" />{/if}
       {if="$skeleton2_blocks_title == 'true'"}<h2>{$content_title}</h2>{/if}
       {if="$skeleton2_blocks_subtitle == 'true'"}<p>{$content_subtitle}</p>{/if}
     </div>
   </div>
  {/if}
  
  {if="$skeleton2_blocks_countdown == 'true'"}
   <div class="row">
      <div class="twelve columns tc">
         {$module_countdown}
      </div>
   </div>
  {/if}
  
    <div class="row">
        
      <div class="six columns">
        
        {if="$skeleton2_blocks_some_text == 'true'"}
            {$content_some_text}
        {/if}
        
      </div>
      
      <div class="six columns">
        
        {if="$skeleton2_blocks_progressbar == 'true'"}
            <div id="progressbars">
               {loop="progressbar"}
               <div data-percentage="{$value.percentage}">
                  <span class="progress_label">{$value.label}</span>
                  <span class="percentage">{$value.percentage}%</span>
                  <div></div>
               </div>
               {/loop}
            </div>
        {/if}
        
      </div>
    
    </div>
   
  {if="$skeleton2_blocks_subscribe_form == 'true'"}
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
  {/if}
   
  {if="$skeleton2_blocks_social_links == 'true'"} 
   <div class="row">
      <div id="social_links" class="twelve columns tc">
         
         {loop="social_links"}
         {if="$value.supported"}
         <a href="{$value.value}" class="button {$value.key}">{$value.title}</a> 
         {/if}
         {/loop}
         
      </div>
   </div>
  {/if}
  
  <!-- Included jQuery -->
  <script src="{$theme_url}js/jquery.js"></script>
  
  <!-- Included JS Files (Compressed) -->
  <script src="{$theme_url}js/foundation.min.js"></script>
  
  <!-- Initialize JS Plugins -->
  <script src="{$theme_url}js/app.js"></script>
  
  <!-- JS hook for modules (for JS file include) -->
  {$home_js}
  
  <!-- JS hook for modules (for inline JS include, or HTML) -->
   {$extended_home_footer}
  
</body>
</html>
