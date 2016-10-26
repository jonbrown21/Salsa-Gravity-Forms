<?php

namespace WdmGFSalsa;

class WdmAdminSettings
{
    public function __construct()
    {
       
        add_action('admin_init', array($this, 'wdmGfSalsa'), 10);
        add_action('admin_enqueue_scripts', array($this, 'wdmEnqueueScripts'), 10);
        add_action('gform_field_standard_settings', array($this, 'wdmStdSettings'), 10, 2);
        add_action('gform_editor_js', array($this, 'editorScript'), 11, 2);
        add_filter('gform_tooltips', array($this, 'addMapTooltips'), 10, 2);
        add_filter('gform_form_settings_menu', array($this, 'wdmGfSalsaMappingPage'), 10, 1);
        add_action('gform_form_settings_page_salsa', array($this, 'mapGroupsSettingPage'));
        add_filter('gform_field_css_class', array($this,'addGroupsClass'), 10, 3);
        add_action('gform_editor_js_set_default_values', array($this,'setDefaultValues'));
        add_filter('gform_pre_render', array($this,'hideAdminGroup'));
        add_action('gform_after_submission', array($this,'wdmAfterSubmission'), 10, 2);
        
        //ajax actions
        add_action('wp_ajax_wdm_custom_field_fetch', array($this, 'fieldFetch'));
        add_action('wp_ajax_nopriv_wdm_custom_field_fetch', array($this, 'fieldFetch'));
        add_action('wp_ajax_wdm_fetch_grp', array($this, 'groupFetch'));
        add_action('wp_ajax_nopriv_wdm_fetch_grp', array($this, 'groupFetch'));
        add_action('wp_ajax_wdm_get_grp_list', array($this, 'wdmGetGrpList'));
        add_action('wp_ajax_nopriv_wdm_get_grp_list', array($this, 'wdmGetGrpList'));
    }
    
    public function wdmGfSalsa()
    {
        \RGForms::add_settings_page('Salsa Connection', array($this, 'wdmGfSalsaConnectionPage'));
    }
    
    public function wdmEnqueueScripts()
    {
        wp_enqueue_script('wdm-gfs-page-js', plugins_url('js/jquery.simplePagination.js', dirname(__FILE__)), array('jquery'));
        wp_enqueue_style('wdm-gfs-page-css', plugins_url('css/simplePagination.css', dirname(__FILE__)));
        wp_enqueue_script('wdm-gfs-fetch', plugins_url('js/wdm-gfs-fetch-fields.js', dirname(__FILE__)), array('jquery'));
        wp_localize_script('wdm-gfs-fetch', 'fetchobj', array('ajaxurl' => admin_url('admin-ajax.php'), 'img_url' => plugin_dir_url(dirname(__FILE__)).'images/ajax-loader.gif'));
        wp_enqueue_script('jquery-ui-dialog');
        wp_enqueue_script('wdm-gfs-display-grps', plugins_url('js/wdm-gfs-display-grps.js', dirname(__FILE__)), array('jquery', 'jquery-ui-dialog'));
        wp_localize_script('wdm-gfs-display-grps', 'grpobj', array('ajaxurl' => admin_url('admin-ajax.php')));
        wp_enqueue_style('wdm-gfs-salsa-grps', plugins_url('css/wdm-gf-salsa.css', dirname(__FILE__)));
    }
    public function setDefaultValues()
    {
        ?>
        case 'user_groups' :
        field.inputType = 'checkbox';
        field.label = <?php echo json_encode(esc_html__('User Groups', 'wdm-gf-salsa')); ?>;
       if (!field.label)
                    field.label = <?php echo json_encode(esc_html__('User Groups', 'wdm-gf-salsa')); ?>;

                if (!field.choices)
                    field.choices = new Array(new Choice(<?php echo json_encode(esc_html__('First Choice', 'wdm-gf-salsa')); ?>), new Choice(<?php echo json_encode(esc_html__('Second Choice', 'wdm-gf-salsa')); ?>), new Choice(<?php echo json_encode(esc_html__('Third Choice', 'wdm-gf-salsa')); ?>));

                field.inputs = new Array();
                for (var i = 1; i <= field.choices.length; i++) {
                    field.inputs.push(new Input(field.id + (i / 10), field.choices[i - 1].text));
                }

                break;
         case 'admin_groups' :
        field.inputType = 'checkbox';
        field.label = <?php echo json_encode(esc_html__('Admin Groups', 'wdm-gf-salsa')); ?>;
       if (!field.label)
                    field.label = <?php echo json_encode(esc_html__('User Groups', 'wdm-gf-salsa')); ?>;

                if (!field.choices)
                    field.choices = new Array(new Choice(<?php echo json_encode(esc_html__('First Choice', 'wdm-gf-salsa')); ?>), new Choice(<?php echo json_encode(esc_html__('Second Choice', 'wdm-gf-salsa')); ?>), new Choice(<?php echo json_encode(esc_html__('Third Choice', 'wdm-gf-salsa')); ?>));

                field.inputs = new Array();
                for (var i = 1; i <= field.choices.length; i++) {
                    field.inputs.push(new Input(field.id + (i / 10), field.choices[i - 1].text));
                }

                break;
        case 'state_groups' :
         field.inputType = 'select';
        field.label = <?php echo json_encode(esc_html__('State Groups', 'wdm-gf-salsa')); ?>;
                
                field.inputs = null;
                if (!field.choices) {
                    field.choices = field["enablePrice"] ? new Array(new Choice(<?php echo json_encode( esc_html__( 'First Choice', 'wdm-gf-salsa' ) ); ?>, "", "0.00"), new Choice(<?php echo json_encode( esc_html__( 'Second Choice', 'wdm-gf-salsa' ) ); ?>, "", "0.00"), new Choice(<?php echo json_encode( esc_html__( 'Third Choice', 'wdm-gf-salsa' ) ); ?>, "", "0.00"))
                        : new Array(new Choice(<?php echo json_encode( esc_html__( 'First Choice', 'wdm-gf-salsa' ) ); ?>), new Choice(<?php echo json_encode( esc_html__( 'Second Choice', 'wdm-gf-salsa' ) ); ?>), new Choice(<?php echo json_encode( esc_html__( 'Third Choice', 'wdm-gf-salsa' ) ); ?>));
                }
                break;
        <?php
    }

    public function addGroupsClass($classes, $field, $form)
    {
        if ($field->type == 'user_groups') {
            $classes .= ' user_groups_setting';
        }
        if ($field->type == 'admin_groups') {
            $classes .= ' admin_groups_setting';
        }
        if ($field->type == 'state_groups') {
            $classes .= ' state_groups_setting';
        }
        return $classes;
    }

    public function hideAdminGroup($form)
    {
        foreach ($form['fields'] as &$field) {
            if ($field->type == 'admin_groups') {
                echo '<style>.admin_groups_setting{display:none;}</style>';
            }
        }

        return $form;
    }
    public function addMapTooltips($tooltips)
    {
        $tooltips['form_field_map'] = '<h6>'.__('Map Salsa Fields', 'wdm-gf-salsa').'</h6>'.__('Select the fields from dropdown.', 'wdm-gf-salsa');

        return $tooltips;
    }

    public function wdmGetGrpList()
    {
        $grp_list = get_option('wdm_salsa_grps');
        if (!empty($grp_list)) {
            foreach ($grp_list as $grp_key => $grp_val) {
                $group .= '<tr class="wdm-salsa-grp-list">';
                $group .= '<td class="wdm-salsa-grp-name">'.$grp_val.'</td>';
                $group .= '<td class="wdm-salsa-grp-key">'.$grp_key.'</td>';
                $group .= '</tr>';
            }
        } else {
            $group = '<tr>';
            $group .= '<td><?php __("No Groups are available.", "wdm-gf-salsa"); ?></td></tr>';
        }
        echo json_encode(array('grp_info' => $group));
        die();
    }
    public function fieldFetch()
    {
        $gf_salsa_options = get_option('gf_salsa_options');

        $response = $response = $this->connectSalsa();
        if (!empty($response)) {
            if (is_wp_error($response)) {
                $error_message = $response->get_error_message();
                $text = sprintf(__('Connection Failed : %1$s Please check the saved salsa details.', 'wdm-gf-salsa'), $error_message);
                $response = array('error' => $text);
            } else {
                $supporter_fields = wp_remote_post($gf_salsa_options['salsa_url'].'/api/describe2.sjs?object=supporter', array(
                            'method' => 'POST',
                            'timeout' => 45,
                            'body' => array('object' => 'supporter', 'json' => 'JSON'),
                            'cookies' => $response['cookies'],
                            ));
                $fields = json_decode($supporter_fields['body']);
                $supporter = array();
                foreach ($fields as $value) {
                    $supporter[$value->name] = $value->label;
                }
                update_option('wdm_salsa_fields', $supporter);
                $text = __('Fields List updated.', 'wdm-gf-salsa');
                $response = array('success' => $text);
            }
        } else {
            $text = __('Please fill all the above SalsaLabs Account details.', 'wdm-gf-salsa');
            $response = array('error' => $text);
        }
        echo json_encode($response);
        die();
    }
    public function groupFetch()
    {
        $gf_salsa_options = get_option('gf_salsa_options');
        $response = $response = $this->connectSalsa();

        if (!empty($response)) {
            if (is_wp_error($response)) {
                $error_message = $response->get_error_message();
                $text = sprintf(__('Connection Failed : %1$s Please check the saved salsa details.', 'wdm-gf-salsa'), $error_message);
                $response = array('error' => $text);
            } else {
                $groups = wp_remote_post($gf_salsa_options['salsa_url'].'/api/getLeftJoin.sjs', array(
                                    'method' => 'POST',
                                    'timeout' => 45,
                                    'body' => array('object' => 'groups', 'json' => 'JSON'),
                                    'cookies' => $response['cookies'],
                                    ));
                $grps = json_decode($groups['body']);
                $supporter_groups = array();
                foreach ($grps as $value) {
                    $supporter_groups[$value->groups_KEY] = $value->Group_Name;
                }
                update_option('wdm_salsa_grps', $supporter_groups);
                $text = __('Group List updated.', 'wdm-gf-salsa');
                $response = array('success' => $text);
            }
        } else {
            $text = __('Please fill all the above SalsaLabs Account details.', 'wdm-gf-salsa');
            $response = array('error' => $text);
        }
        echo json_encode($response);
        die();
    }
    public function connectSalsa()
    {
        $response = array();
        $gf_salsa_options = get_option('gf_salsa_options');
        if (isset($gf_salsa_options['salsa_url']) && !empty($gf_salsa_options['salsa_url'])) {
            $response = wp_remote_post($gf_salsa_options['salsa_url'].'/api/authenticate.sjs', array(
                                   'method' => 'POST',
                                   'timeout' => 45,
                                   'body' => array('email' => $gf_salsa_options['salsa_username'], 'password' => $gf_salsa_options['salsa_password']),
            ));
        }

        return $response;
    }

    public function wdmStdSettings($position, $form_id)
    {
        //create settings on position 50 (right after Admin Label)
        if ($position == 50) {
            $map_fields = get_option('wdm_salsa_fields');
            ?>
            <li class="map_setting field_setting">
                 <label for="map_field" class="section_label">
                        <?php esc_html_e('Map Salsa Fields', 'wdm-gf-salsa');
            ?>
                        <?php gform_tooltip('form_map_field');
            ?>
                 </label>
                 <select id="map_field" onchange="SetFieldProperty('mapField', this.value);">
                                <?php  foreach ($map_fields as $key => $value) {
        ?>
                                                                     <option value="<?php echo $key;
        ?>"><?php echo $value;
        ?></option>
                                                <?php
}
            ?>
                 </select>
        </li>
        <?php
        }
        unset($form_id);
    }
    public function editorScript()
    {
        ?>
    <script type='text/javascript'>
    //adding setting to fields
   jQuery.each(fieldSettings, function(index, value) {
    fieldSettings[index] += ", .map_setting";
    });

  jQuery(document).bind("gform_load_field_settings", function(event, field, form){
          jQuery("#map_field").val(field["mapField"]);
   });
    </script>
    <?php
    }
    public function wdmGfSalsaMappingPage($menu_items)
    {
        $menu_items['40'] = array('name' => 'salsa', 'label' => __('Salsa Settings', 'wdm-gf-salsa'));

        return $menu_items;
    }
    public function mapGroupsSettingPage()
    {
        $output = '';
        if (isset($_GET['id'])) {
            $form_id = $_GET['id'];
        } else {
            $form_id = null;
        }
        $form = \RGFormsModel::get_form_meta($form_id);
        if ($form) {
            \GFFormSettings::page_header();
            if (isset($_POST['salsa-save'])) {
                $form['salsa']['on_submission']['enabled'] = !empty($_POST['salsa-on-submission']);
                if (\RGFormsModel::update_form_meta($form_id, $form)) {
                    $output .= '<div class="updated below-h2"><p><strong>'.__('The settings have been saved.', 'wdm-gf-salsa').'</strong></p></div>';
                }
            }
            if (isset($form['salsa']['on_submission'])) {
                $on_submission = $form['salsa']['on_submission']['enabled'];
            } else {
                $on_submission = false;
            }
            $output .= '<form method="post">';
            $output .= '<h3>';
            $output .= __('Salsa Form Settings', 'wdm-gf-salsa');
            $output .= '</h3>';
            $output .= '<span style="margin-right:10px;">'.__('Opt-In Condition', 'wdm-gf-salsa').'</span>';
       /*     <i class="fa fa-question-circle"></i>*/
            $output .= ' ';
            $output .= sprintf('<input type="checkbox" name="salsa-on-submission" %s />', $on_submission ? ' checked="checked" ' : '');
            $output .= '<span style="font-style: italic;">'.__('Enable to register users on SalsaLabs on this form submission.', 'wdm-gf-salsa').'</span>';
            $output .= '<br/>';
            $output .= '<br/>';
            $output .= sprintf('<input class="button-primary gfbutton" type="submit" name="salsa-save" value="%s" />', __('Save', 'wdm-gf-salsa'));
            $output .= '</form>';
            echo $output;
            \GFFormSettings::page_footer();
        }
    }

    public function wdmGfSalsaConnectionPage()
    {
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.', 'wdm-gf-salsa'));
        }

        if (isset($_POST['gf_salsa_hidden']) && $_POST['gf_salsa_hidden'] == 'Y') {
            $store = $_POST;
            $this->storeSettings($store);
        }

        $gf_salsa_options = get_option('gf_salsa_options');

        ?>
  <div class="wrap">
    <h3><?php _e('Salsa Connection Settings', 'wdm-gf-salsa');
        ?></h3>
        <form method="post" action="">
      <input type="hidden" name="gf_salsa_hidden" value="Y" />
        <?php wp_nonce_field('gf_salsa_settings');
        ?>
        <p>
        <strong><?php _e('Base URL', 'wdm-gf-salsa');
        ?></strong>*<br />
            <input type="text" id="gf_salsa_url" name="gf_salsa_url" value="<?php if (isset($gf_salsa_options['salsa_url'])) {
                echo $gf_salsa_options['salsa_url'];
}
        ?>" size="26" />
    </p>

  <p>
    <strong><?php _e('Login Email', 'wdm-gf-salsa');
        ?></strong>*<br />
        <input type="text" id="gf_salsa_username" name="gf_salsa_username" value="<?php if (isset($gf_salsa_options['salsa_username'])) {
            echo $gf_salsa_options['salsa_username'];
}
        ?>" size="26" />
    </p>
 <p>
<strong><?php _e('Password', 'wdm-gf-salsa');
        ?></strong>*<br />
    <input type="text" id="gf_salsa_password" name="gf_salsa_password" value="<?php if (isset($gf_salsa_options['salsa_password'])) {
        echo $gf_salsa_options['salsa_password'];
}
        ?>" size="26" />
    </p>
    <p>
 <strong><?php _e('Organization Key', 'wdm-gf-salsa');
        ?></strong><br />
        <input type="text" id="gf_salsa_org_key" name="gf_salsa_org_key" value="<?php if (isset($gf_salsa_options['salsa_org_key'])) {
            echo $gf_salsa_options['salsa_org_key'];
}
        ?>" size="26" />
    </p>
 <p class="submit">
    <input type="submit" name="Submit" class="button-primary" value="Save Changes" />
  </p>
</form>

<div>
 <div><strong><?php _e('Click below button to update the group list from Salsa.', 'wdm-gf-salsa');
        ?></strong></div>
      <input type="button" id="fetch_grps" name="fetch_grps" class="button-primary" value="Fetch Salsa Groups" />
      <div class="loading_img"></div>
  </div>
      <br/>
      <div>
      <div><strong><?php _e('Click below button to update the custom form fields list from Salsa.', 'wdm-gf-salsa');
        ?></strong></div>
          <input type="button" id="fetch_custom_fields" name="fetch_custom_fields" class="button-primary" value="Fetch Salsa Fields" />
           <div class="loading_img"></div>
          </div>
          </div>
            <?php
    }
    public function storeSettings($store)
    {
        check_admin_referer('gf_salsa_settings');
        $gf_salsa_options = array(
        'salsa_username' => $store['gf_salsa_username'],
        'salsa_password' => $store['gf_salsa_password'],
        'salsa_url' => $store['gf_salsa_url'],
         'salsa_org_key' => $store['gf_salsa_org_key'],
        );
        update_option('gf_salsa_options', $gf_salsa_options);
        if (isset($store['gf_salsa_username'])) {
            if (is_email($store['gf_salsa_username'])) {
                $username = $store['gf_salsa_username'];
            } else {
                echo '<span class="error_msg"><strong>'.__('Please fill valid email.', 'wdm-gf-salsa').'</strong></span>';

                return;
            }
        }
        if (isset($store['gf_salsa_password'])) {
            $password = $store['gf_salsa_password'];
        }
        if (isset($store['gf_salsa_url'])) {
            if (preg_match("/^(http[s]?:\/\/){0,1}(www\.){0,1}[a-zA-Z0-9\.\-]+\.[a-zA-Z]{2,5}[\.]{0,1}/", $store['gf_salsa_url'])) {
                $url = $store['gf_salsa_url'];
            } else {
                echo '<span class="error_msg"><strong>'.__('Please fill valid Url.', 'wdm-gf-salsa').'</strong></span>';

                return;
            }
        }
        if (isset($store['gf_salsa_org_key'])) {
            $org_key = $store['gf_salsa_org_key'];
        }
        if (!empty($url) && !empty($username) && !empty($password) || !empty($org_key)) {
            $response = wp_remote_post($url.'/api/authenticate.sjs', array(
                                   'method' => 'POST',
                                   'timeout' => 45,
                                   'body' => array('email' => $username, 'password' => $password),
            ));
            if (is_wp_error($response)) {
                $error_message = $response->get_error_message();
                $text = '<span class="error_msg"><strong>';
                $text .= sprintf(__('Connection Failed : %1$s Please check the saved salsa details.', 'wdm-gf-salsa'), $error_message);
                $text .= '</strong></span>';
                echo $text;
            } else {
                echo '<div class="updated"><strong>'.__('Options saved.', 'wdm-gf-salsa').'</strong></div>';
            }
        } else {
            echo '<span class="error_msg"><strong>'.__('Please fill the required(*) fields.', 'wdm-gf-salsa').'</strong></span>';
        }

        return;
    }

    public function wdmAfterSubmission($entry, $form)
    {

    	if(isset($form['salsa']['on_submission']['enabled'])&&($form['salsa']['on_submission']['enabled']=='1')){


    		$supporter_data = array('object'=>'supporter','json' => 'JSON','link'=>'groups');
            $supporter_data_link = array();
            $supporter_data_linkKey = array();
            foreach ( $form['fields'] as $field ) {
       
                if(!empty($field->mapField)){
                        $field_value = $field->get_value_export( $entry, $field->id, true );
                        $supporter_data[$field->mapField]=$field_value;
				}
                if(($field->type == 'user_groups')||($field->type == 'state_groups')){
                        $field_value = $field->get_value_export( $entry, $field->id, true );
                        $choices = array_map('trim', explode(',',$field_value));
	                foreach($field->choices as $key =>$value){
							if(is_array($value)){
	                        	 	    if(in_array($value['text'],$choices)){

	                        	 		$supporter_data_link[] = 'groups';
	                                    $supporter_data_linkKey[] = $value['value'];

	                                    }

	                        }
	                }
                }
                 if($field->type == 'admin_groups'){
                 	 foreach($field->choices as $key =>$value){
							if(is_array($value)){

								$supporter_data_link[] = 'groups';
	                            $supporter_data_linkKey[] = $value['value'];

							}
						}

                 }

            }
            
        $final_groups = "&link=".implode('&link=',$supporter_data_link) . "&linkKey=".implode('&linkKey=',$supporter_data_linkKey);
        $gf_salsa_options = get_option('gf_salsa_options');
        
        $response = $response = $this->connectSalsa();
            if (!empty($response)) {
                if (is_wp_error($response)) {
                    $error_message = $response->get_error_message();
                    $text = sprintf(__('Connection Failed : %1$s Please check the saved salsa details.', 'wdm-gf-salsa'), $error_message);
                    $response = array('error' => $text);
                } else {
                    $supporter_fields = wp_remote_post($gf_salsa_options['salsa_url'].'/save', array(
                                'method' => 'POST',
                                'timeout' => 45,
                                'headers' => array(
                                    'Content-Type' => 'application/json',
                                ),
                                'body' => urldecode(http_build_query($supporter_data).$final_groups),
                                'cookies' => $response['cookies'],
                                ));
                    
                }
            }
        }
       
    }

    
}
