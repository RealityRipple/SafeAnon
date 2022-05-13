<?php
class SafeAnonPlugin extends MantisPlugin {
 const ERROR_SPAM_ANONURL = 'spam_url_detected';

	function register() {
		$this->name = plugin_lang_get( 'title' );
		$this->description = plugin_lang_get( 'description' );
		$this->page = '';

		$this->version = '1.0';
		$this->requires = array(
			'MantisCore' => '2.0.0',
		);

		$this->author  = 'Andrew Sachen';
		$this->contact = 'webmaster@RealityRipple.com';
		$this->url     = 'https://github.com/RealityRipple/SafeAnon';
	}

	function hooks() {
		return array(
			'EVENT_BUGNOTE_DATA' => 'bug_note',
		);
	}

	function errors() {
		$t_errors_list = array(
			self::ERROR_SPAM_ANONURL,
		);

		foreach( $t_errors_list as $t_error ) {
			$t_errors[$t_error] = plugin_lang_get( 'error_' . $t_error );
		}

		return array_merge( parent::errors(), $t_errors );
	}

	function bug_note ( $p_event, $p_bugnote_text, $p_private, $c_bug_id = false ) {
  if ($c_bug_id === false)
  {
   $c_bug_id = $p_private;
   $p_private = null;
  }
  if (self::antispam_note_check($p_bugnote_text, $p_private) === false)
  {
   plugin_error( self::ERROR_SPAM_ANONURL );
   return '';
		}
  return $p_bugnote_text;
	}

 function antispam_note_check( $p_bugnote_text, $p_private ) {
  global $g_allow_anonymous_login, $g_anonymous_account;
  if ( OFF == $g_allow_anonymous_login ) {
   return true;
  }
  if ( true === $p_private ) {
   return true;
  }
  $e_user_id = auth_get_current_user_id();
  $e_username = user_get_username( $e_user_id );
  if ( $e_username != $g_anonymous_account ) {
   return true;
  }
  $t_limit = false;
  if ( strpos($p_bugnote_text, '<a href=') !== false ) {
   $t_limit = true;
  } else if ( strpos($p_bugnote_text, '[url=') !== false ) {
   $t_limit = true;
  } else if ( substr($p_bugnote_text, 0, 4) === 'http' ) {
   $t_limit = true;
  } else if ( strpos($p_bugnote_text, ' ') !== false ) {
   $t_count = 0;
   $t_msg_arr = preg_split("/[\s,\.]+/", $p_bugnote_text, -1, PREG_SPLIT_NO_EMPTY);
   foreach ( $t_msg_arr as $t_word ) {
    if ( substr(trim($t_word), 0, 4) === 'http' ) {
     $t_count++;
    }
   }
   if ( $t_count > 3 || $t_count >= count($t_msg_arr) - 1 ) {
    $t_limit = true;
   }
  }
  if ( !$t_limit ) {
   return true;
  }
  return false;
 }
}
?>
