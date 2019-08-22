# Safe Anon
MantisBT Plugin to prevent anonymous users from posting URLs in public notes.

How to install
--------------

1. Copy SafeAnon folder into plugins folder.
2. Open Mantis with browser.
3. Log in as administrator.
4. Go to Manage -> Manage Plugins.
5. Find SafeAnon in the list.
6. Click Install.

Optional:  
The event `EVENT_BUGNOTE_DATA` does not currently pass whether the note in question is public or private. If you wish to allow private notes to bypass this plugin, please make the following change to your MantisBT installation:

core/bugnote_api.php; Line 262  
> `- $t_bugnote_text = event_signal( 'EVENT_BUGNOTE_DATA', $p_bugnote_text, $c_bug_id );`  
> `+ $t_bugnote_text = event_signal( 'EVENT_BUGNOTE_DATA', array( $p_bugnote_text, $p_private ), $c_bug_id );`

Please be aware that this may break other plugins that use the `EVENT_BUGNOTE_DATA` signal. You should check your plugins' hook lists to make any necessary changes. Any functions that are called by the signal should include a new `$p_private` parameter between `$p_bugnote_text` and `$c_bug_id`.

Supported Versions
------------------

- MantisBT 1.2.x and higher - Unknown
- MantisBT 2.0 and higher - Supported
