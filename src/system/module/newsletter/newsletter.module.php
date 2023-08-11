<?php lock();

class Newsletter_Module extends Core{
  
    private $prefs, $user_prefs;

    function __Construct(){
      
      $this->prefs = array(
        
        'type' => 'php',
        'from_name' => '',
        'reply_to_mail' => $this->pref('email'),
        'reply_to_name' => '',
        'smtp_host' => 'localhost',
        'smtp_auth' => 'false',
        'smtp_port' => '25',
        'smtp_username' => '',
        'smtp_password' => '',
        'from' => $this->pref('email')

      );
      
      if(!$this->installed('newsletter')){
        $this->install();
      }
      
      $this->load_user_prefs();
        
    }
    
    /* View */
    public function manage(){
      
      $this->write_config('rewrite_template_path', $this->config('system_theme_path').'newsletter/');
        
        $this->OUT()->assign('subscribers', $this->get_subscriber(true));
        
        $out = array(
          'action' => 'showContent',
          'content' => $this->OUT()->draw('manage', true)
        );
        
        return json_encode($out);
      
    }
    
    /* View */
    public function create(){
      
        $this->write_config('rewrite_template_path', $this->config('system_theme_path').'newsletter/');
        
        $this->OUT()->assign('messages', $this->get_message(true));
        $this->OUT()->assign('all_num', $this->DB()->get_var("SELECT count(id) FROM email"));
        
        $out = array(
          'action' => 'showContent',
          'content' => $this->OUT()->draw('create', true)
        );
        
        return json_encode($out);
      
    }
    
    /* View */
    public function send_full(){
        
        $out = $this->send_email($this->POST('message_id'));
        $out['action'] = 'newsletterProcessSend';
        $out['content'] = '';
        $out['successful'] = $out['successful'] + $this->POST('successful');
        $out['unsuccessful'] = $out['unsuccessful'] + $this->POST('unsuccessful');
        $out['message_id'] = $this->POST('message_id');
        
        return json_encode($out);
        
    }
    
    /* View */
    public function settings(){
      
        $this->write_config('rewrite_template_path', $this->config('system_theme_path').'newsletter/');
        
        $this->assign_prefs();
        
        $out = array(
          'action' => 'showContent',
          'content' => $this->OUT()->draw('settings', true)
        );
        
        return json_encode($out);
      
    }
    
    /* View */
    public function process_create(){
        
        if(is_numeric($this->POST('message_id'))){
            $this->add_message($this->POST('subject'), $this->POST('message'), 'id = "'.$this->POST('message_id').'"');
            $msg = $this->lang('newsletter','message_update_success');
        }
        else{
            $this->add_message($this->POST('subject'), $this->POST('message'));
            $msg = $this->lang('newsletter','message_added_success');
        }
        
        
        $out = array();
        $out['action'] = 'refreshPage|alert|newsletterDestroyCreate';
        $out['content'] = $msg;
        
        return json_encode($out);
        
    }
    
    /* View */
    public function send_test(){
        
        $r = $this->send_email($this->POST('id'), true);
        
        if($r['successful'] == 1){
            $msg = $this->lang('newsletter','test_message_success');
        }
        else{
            $msg = $this->lang('newsletter','test_message_unsuccess') . '<br><br>' . $r['error_info'];
        }
 
        $out = array();
        $out['action'] = 'alert|newsletterRemoveTestLoad';
        $out['content'] = $msg;
        
        return json_encode($out);
        
    }
    
    /* View */
    public function process_settings(){
        
        $errors = array();
        
        foreach($this->prefs as $key => $val){
            
            $p = $this->POST($key);
            
            if($key == 'from' && !$this->valid_email($p)){
                $errors['newsletter_'.$key] = $this->lang('newsletter','settings_error_from');
            }
            
            if($p != '' && $key == 'reply_to_mail' && !$this->valid_email($p)){
                $errors['newsletter_'.$key] = $this->lang('newsletter','settings_error_reply_to_mail');
            }
            
            if($key == 'smtp_port' && !is_numeric($p) && $this->POST('type') == 'smtp'){
                $errors['newsletter_'.$key] = $this->lang('newsletter','settings_error_port');
                $this->pref('newsletter_smtp_port', 25);
            }
            
            if($key == 'smtp_host' && $p == '' && $this->POST('type') == 'smtp'){
                $errors['newsletter_'.$key] = $this->lang('newsletter','settings_error_host_empty');
                $this->pref('newsletter_smtp_host', 'localhost');
            }
            
            if(!isset($errors['newsletter_'.$key]))
                $this->pref('newsletter_'.$key, $p);
            
        }
        
        if($errors){
            $msg = $this->lang('newsletter','settings_updated_but') . '<br><br>';
            foreach($errors as $err){
                $msg .= $err . '<br>';
            }
        }
        else{
            $msg = $this->lang('newsletter','settings_updated') . '<br>';
        }
        
        
        $out = array();
        $out['action'] = 'refreshPage|alert';
        $out['content'] = $msg;
        
        return json_encode($out);
        
    }
    
    /* View */
    public function process_delete(){
        
        $out = array();
        
        if(is_numeric($this->POST('id'))){
            $this->delete_message($this->POST('id'));
            $msg = $this->lang('newsletter','delete_message_success');
            $out['action'] = 'refreshPage|alert|newsletterDestroyDelete';
        }
        else{
            $msg = $this->lang('newsletter','delete_message_unsuccess');
            $out['action'] = 'alert|newsletterDestroyDelete';
        }

        $out['content'] = $msg;
        
        return json_encode($out);
        
    }
    
    public function add($str){
        
        $str = str_replace("\\r\\n",",",$str);
        $str = str_replace("\\n",",",$str);
      
        $mails = preg_split("/[\s,|]+/", $str);
        
        $total = count($mails);
        $success = 0;
        $error = 0;
        $duplicated = 0;
        
        foreach($mails as $mail){
          
          if($this->DB()->get_var("SELECT count(id) FROM email WHERE email = '$mail'") > 0){
            $duplicated++;
          }
          elseif($this->valid_email($mail)){
            
            $this->DB()->query("INSERT INTO email ('email', 'date') VALUES ('$mail', '".time()."')");
            $success++;
            
          }
          else{
            $error++;
          }
          
        }
        
        return array(
          'success' => $success,
          'failed' => $error,
          'duplicated' => $duplicated
        );
      
      
    }
    
    public function add_message($subject, $message, $where = false){
      
        if(!$where){
          $this->DB()->query("INSERT INTO newsletter ('text','subject','sent_num','date','done','last_id') VALUES ('$message','$subject','0','0','false','0')");
        }
        else{
          $this->DB()->query("UPDATE newsletter SET 'text' = '$message', 'subject' = '$subject' WHERE ". $where);
        }
        
    }
    
    /* View */
    public function add_emails(){
      
        $out = $this->add(strtolower($this->POST('email')));
        
        $out['action'] = 'refreshPage|newsletterDone';
        $out['content'] = '';
        
        return json_encode($out);
      
    }
    
    /* View */
    public function edit_email(){
      
        $out = array();
        $out['action'] = 'refreshPage|alert|newsletterDestroyEdit';
        
        if($this->POST('email_id') && $this->POST('email') && $this->valid_email($this->POST('email'))){
            $this->edit(strtolower($this->POST('email')), $this->POST('email_id'));
            $out['content'] = $this->lang('newsletter','successful_edit');
        }
        else{
            $out['content'] = $this->lang('newsletter','unsuccessful_edit');
            $out['action'] = 'alert|newsletterDestroyEdit';
        }
        
        return json_encode($out);
      
    }
    
    /* View */
    public function delete_email(){
        
        $out = array();
        $out['action'] = 'refreshPage|alert|newsletterDestroyDelete';
        
        if($this->POST('email_id')){
            $this->delete($this->POST('email_id'));
            $out['content'] = $this->lang('newsletter','successful_delete');
        }
        else{
            $out['content'] = $this->lang('newsletter','unsuccessful_delete');
            $out['action'] = 'alert|newsletterDestroyDelete';
        }
        
        return json_encode($out);
        
    }
    
    /* View */
    public function search_email(){
        
        $this->write_config('rewrite_template_path', $this->config('system_theme_path').'newsletter/');
        
        $q = $this->POST('email');
        
        $this->OUT()->assign('subscribers', $this->get_subscriber("email LIKE '%$q%'"));
        
        $out = array(
          'action' => 'showContent|newsletterDestroySearch',
          'content' => $this->OUT()->draw('manage', true)
        );
        
        return json_encode($out);
    
    }
    
    public function delete($id){
        $this->DB()->query("DELETE FROM email WHERE id = '$id' OR email = '$id'");
    }
    
    public function delete_message($id){
        $this->DB()->query("DELETE FROM newsletter WHERE id = '$id'");
    }
    
    public function edit($email, $id = '00'){
        $this->DB()->query("UPDATE email SET email = '$email' WHERE email = '$id' OR id = '$id'");
    }
    
    public function get_subscriber($where = 'id >= 1'){
        
        $limit = "LIMIT ".(($this->klass('pager')->get_page() - 1) * 25) . ', ' . 25;
      
        if($where === true){
          $res = $this->DB()->get_results("SELECT * FROM email $limit", ARRAY_A);
          $count = $this->DB()->get_var("SELECT count(id) FROM email");
        }
        else{
          $res = $this->DB()->get_results("SELECT * FROM email WHERE $where $limit", ARRAY_A);
          $count = $this->DB()->get_var("SELECT count(id) FROM email WHERE $where");
        }
      
        $this->klass('pager')->records($count);
        $this->klass('pager')->records_per_page(25);
        $this->OUT()->assign('pagination', $this->klass('pager')->render(TRUE));
        
        if($res){
            foreach($res as $k => $v){
              $res[$k]['date'] = $this->dayt($v['date']);
            }
        }
        else{
            $res = array();
        }
        
        return $res;
      
    }
    
    public function get_message($where = 'id >= 1'){
      
        if($where === true){
          $res = $this->DB()->get_results("SELECT * FROM newsletter", ARRAY_A);
        }
        else{
          $res = $this->DB()->get_results("SELECT * FROM newsletter WHERE $where", ARRAY_A);
        }

        if($res){
            foreach($res as $k => $v){
              $res[$k]['date'] = ($v['date'] != '0') ? $this->dayt($v['date']) : $this->lang('newsletter','not_sent_yet');
              $res[$k]['text'] = $this->translate_quoted($v['text']);
            }
        }
        else{
            $res = array();
        }
        
        return $res;
      
    }
    
    private function send_email($message_id, $test = false){
        
        $last = false;
        
        $results = array(
            'successful' => 0,
            'unsuccessful' => 0
        );
        
        $successful = 0;
        $unsuccessful = 0;
        
        $message = $this->get_message("id = '$message_id'");
        $message = $message[0];
        
        $message['last_id'] = ($message['done'] == 'true') ? 0 : $message['last_id'];
        $message['sent_num'] = ($message['done'] == 'true') ? 0 : $message['sent_num'];
        
        if($test){
            $emails = array(
                array(
                    'id' => '00',
                    'email' => $this->user_prefs['from']
                )
            );
        }
        else{
            $emails = $this->DB()->get_results("SELECT * FROM email WHERE id > '" . $message['last_id'] . "' LIMIT 10", ARRAY_A);
            $last = (count($emails) == 10) ? false : true;
        }

        $body = htmlspecialchars_decode($message['text']);
        
        
        if($this->user_prefs['type'] == 'sendmail'){
            $this->module('email')->IsSendmail();
        }
        if($this->user_prefs['type'] == 'qmail'){
            $this->module('email')->IsQmail();
        }
        if($this->user_prefs['type'] == 'smtp'){
            
            $this->module('email')->IsSMTP();
            $this->module('email')->Host = $this->user_prefs['smtp_host'];
            $this->module('email')->Port = $this->user_prefs['smtp_port'];
            
            if($this->user_prefs['smtp_username'] != '' && $this->user_prefs['smtp_password'] != ''){
                $this->module('email')->SMTPAuth = true;
                $this->module('email')->Username = $this->user_prefs['smtp_username'];
                $this->module('email')->Password = $this->user_prefs['smtp_password'];
            }
            
        }

        
        $this->module('email')->CharSet = 'utf-8';
        $this->module('email')->SetFrom($this->user_prefs['from'], $this->user_prefs['from_name'] ? $this->user_prefs['from_name'] : $this->user_prefs['from']);
        $this->module('email')->AddReplyTo($this->user_prefs['reply_to_mail'] ? $this->user_prefs['reply_to_mail'] : $this->user_prefs['from'], $this->user_prefs['reply_to_name'] ? $this->user_prefs['reply_to_name'] : $this->user_prefs['reply_to_mail']);
        $this->module('email')->AltBody = "To view the message, please use an HTML compatible email viewer!";
        $this->module('email')->Subject = $message['subject'];
        
        foreach($emails as $key => $email){
            
            $this->module('email')->ClearAddresses();
            
            $body = $this->replace_tags($body, $email);

            $this->module('email')->MsgHTML($body);
            
            $this->module('email')->AddAddress($test ? $this->user_prefs['from'] : $email['email'], $test ? $this->user_prefs['from'] : $email['email']);
            
            if(!$this->module('email')->Send()) {
                $results['unsuccessful']++;
            } else {
                $results['successful']++;
            }
            
            $results['error_info'][] = $this->module('email')->ErrorInfo;
            
            $message['sent_num']++;
            
            if(!$test){
                $status = $last ? 'true' : 'pending';
                $this->DB()->query("UPDATE newsletter SET sent_num = '" . $message['sent_num'] . "', date = '" . time() . "', done = '$status', last_id = '" . $email['id'] . "' WHERE id = '$message_id'");
            }
            
        }
        
        $results['last'] = $last ? 'true' : 'false';

        return $results;
        
    }
    
    private function assign_prefs(){
        
        foreach($this->prefs as $key => $val){
            
            $this->OUT()->assign('newsletter_'.$key, $this->pref('newsletter_'.$key));
            
        }
        
    }
    
    private function load_user_prefs(){
        
        $this->user_prefs = array();
        
        foreach($this->prefs as $key => $val){
            
            $this->user_prefs[$key] = $this->pref('newsletter_'.$key);
            
        }
        
    }
    
    private function replace_tags($body, $email){
        
        $tags = array(
            '{EMAIL_ADDRESS}' => $email['email'],
            '{UNSUBSCRIBE_LINK}' => $this->generate_unsub_link($email)
        );
        
        foreach($tags as $tag => $value){
            $body = str_replace($tag, $value, $body);
        }
        
        return $body;
        
    }
    
    private function generate_unsub_link($email){
        
        return '<a href="' . $this->config('full_url') . '?newsletter_unsubscribe=' . urlencode($email['email']) . '&d=' . sha1($email['id']) . '">' . $this->lang('newsletter', 'unsubscribe') . '</a>';
        
    }
    
    private function install(){
      
        foreach($this->prefs as $key => $val){
            
            $this->pref('newsletter_'.$key, $val);
            
        }
        
        $this->DB()->query('
          CREATE TABLE "newsletter" (
            "id" integer NOT NULL PRIMARY KEY,
            "text" text NOT NULL,
            "subject" text NOT NULL,
            "sent_num" integer NOT NULL,
            "date" integer NOT NULL,
            "done" text NOT NULL,
            "last_id" integer NOT NULL
          );                   
        ');
        
        $this->DB()->query('
          CREATE TABLE "email" (
            "id" integer NOT NULL PRIMARY KEY,
            "email" text NOT NULL,
            "date" text NOT NULL
          );                   
        ');
        
        $this->installed('newsletter', true);
        
    }
    
}

?>