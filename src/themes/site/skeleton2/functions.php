<?php lock();

// All themes have to come with functions.php to add some datas and init some things, handle AJAX request, etc...
// For class name use your the name starting with uppercase letter followed by '_Theme'
// Your class always extends Core

class Skeleton2_Theme extends Core{
  
  public  $theme_name = 'Skeleton2',
          $theme_author = 'wintercounter',
          $theme_version = '1.0',
          $theme_description = 'Extended version of Skeleton with a few settings.',
          $theme_cover = 'images/cover.png',
          
          /*
          You have to specify here which modules is the theme supports.
          For example there is the content module and it's fields.
          */
          $theme_supports = array(
            'content' => array(
              'title' => 'text',
              'subtitle' => 'text',
              'some_text' => 'textarea',
              'email_placeholder' => 'text'
            ),
            'countdown' => true,
            'newsletter' => true,
            'social_links' => array('twitter','facebook','youtube','custom'),
            'progressbar' => true
          );
          
  private $settings = array(
            'color_scheme' => 'light',
            'layout' => 'home',
            'logo' => 'themes/site/skeleton2/images/logo.png',
            'blocks' => array(
              'blocks_logo' => 'true',
              'blocks_title' => 'true',
              'blocks_subtitle' => 'true',
              'blocks_countdown' => 'true',
              'blocks_progressbar' => 'true',
              'blocks_some_text' => 'true',
              'blocks_subscribe_form' => 'true',
              'blocks_social_links' => 'true'
            )
          );
  
  
  /* NOT A NATIVE constructor. Don't use __Construct like function! */
  public function construct(){
    
    /* Registering allowed methods */
    $this->register_method('add_subscriber');
    
    /* Load selected layout */
    $this->write_config('page', $this->pref('skeleton2_layout'));
    
    
    /* Assigning some variables to your template */
    
    // Simple call for theme url
    $this->OUT()->assign('theme_url', $this->config('site_theme_url') . 'skeleton/');
    
    // Assigning social links (only enabled ones)
    $this->OUT()->assign('social_links', $this->module('social_links')->get_links('disabled = 0'));
    
    // Assigning progressbar (only enabled ones)
    $this->OUT()->assign('progressbar', $this->module('progressbar')->get_progress('disabled = 0'));
    
    // Assigning settings to template
    foreach($this->settings as $setting => $value){
      
      if(is_array($value)){
        
        foreach($value as $k => $v){
          
          $this->OUT()->assign('skeleton2_' . $k, $this->pref('skeleton2_' . $k));
          
        }
        
      }
      else{
        
        $this->OUT()->assign('skeleton2_' . $setting, $this->pref('skeleton2_' . $setting));
        
      }
      
    }


  }
  
  /* If you need to run custom codes when activating your theme */
  public function activate(){
    
    /* Setting default content */
    $this->reset_defaults();
    
  }
  
  /* This will be called when submitting on your settings page. */
  public function settings(){
    
    // Looping through settings, getting it's POST value if exists, and save them
    foreach($this->settings as $setting => $value){
      
      // POST exists for setting?
      if($this->POST($setting)){
      
        // Is our setting value is an array?
        if(is_array($value)){
          
          // Loop through that array
          foreach($value as $k => $v){
            
            // Stringify please
            $val = $this->POST($setting);
            $val = isset($val[$k]) ? 'true' : 'false';
            
            // Save value from POST
            $this->pref('skeleton2_' . $k, $val);
            
          }
          
        }
        // Nope, it's not an array, but only proceed if POST exists...
        elseif($this->POST($setting)){
          
          // Save value from POST
          $this->pref('skeleton2_' . $setting, $this->POST($setting));
          
        }
      
      }
      // It doesn't exists in POST so it must be 'false'
      else{
        $this->pref('skeleton2_' . $setting, 'false');
      }
      
    }
    
    return array(
      'action' => 'refreshPage|refreshIframe|alert',
      'content' => 'Theme settings were successfully updated!'
    );
    
  }
  
  /* If you want to have 'Reset to Defaults' functionality */
  public function reset_defaults(){
    
    /* Set Countdown layout */
    $this->pref('countdown_layout', '<div class="box"><div class="counter">{dnnn}</div><div class="word">days</div></div><div class="box"><div class="counter">{hnn}</div><div class="word">hours</div></div><div class="box"><div class="counter">{mnn}</div><div class="word">minutes</div></div><div class="box"><div class="counter">{snn}</div><div class="word">seconds</div></div>');
    
    /* You can do checks first if the content exists to not to overwrite.
    You may use prefix for your field name to prevent other templates content to be overwritten. Example: skeleton_title */

    $this->module('content')->content('title','Skeleton2');
    $this->module('content')->content('subtitle','The site is under construction');
    $this->module('content')->content('some_text','<p>Having a <em>Coming Soon</em> page is really usefull! You can get more visitors for your project start, you can engage your visitors and get subscribers for news and notications about your project.</p>','textarea');
    $this->module('content')->content('email_placeholder','Your e-mail address');
    
    /* Add progress bar samples if there is no one yet. */
    if($this->DB()->get_var("SELECT count(id) FROM progressbar") == 0){
      
      $this->module('progressbar')->add_progress(array('label' => 'Fetching Cats', 'percentage' => '28'));
      $this->module('progressbar')->add_progress(array('label' => 'Sphering Cubes', 'percentage' => '91'));
      $this->module('progressbar')->add_progress(array('label' => 'Eating Skateboards', 'percentage' => '55'));
      
    }
    
    /* Registering settings values */
    foreach($this->settings as $setting => $value){
      
      if(is_array($value)){
        
        foreach($value as $k => $v){

          $this->pref('skeleton2_' . $k, $v);

        }
        
      }
      else{
        
        $this->pref('skeleton2_' . $setting, $value);
        
      }
      
    }
    
    
  }
  
  public function add_subscriber(){
    
    return $this->module('newsletter')->add($this->POST('email'));
    
  }

  
}