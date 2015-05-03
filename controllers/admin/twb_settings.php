<?php defined('SYSPATH') or die('No direct script access.');

class Twb_Settings_Controller extends Admin_Controller
{
    public function index()
    {
        $this->template->this_page = 'addons';
        $this->template->content = new View("admin/addons/plugin_settings");
        $this->template->content->title = "TWB Plugin Settings";
        $this->template->content->settings_form = new View("twb/admin/twb_settings");

        $form = array
        (
            'api_key' => '',
        );

        //  Copy the form as errors, so the errors will be stored with keys
        //  corresponding to the form field names
        $errors = $form;
        $form_error = FALSE;
        $form_saved = FALSE;

        // check, has the form been submitted, if so, setup validation
        if ($_POST)
        {
            $post_validator = new Validation($_POST);
            $post_validator->pre_filter('trim', TRUE);

            // Check the length of the API Key.
            // TODO: Replace length value with corrected length
            $post_validator->add_rules('api_key', 'length[0,40]');

            if ($post_validator->validate())
            {
                $settings = ORM::factory('twb_settings', 1);
                $settings->api_key = $post_validator->api_key;
                $settings->save();

                $form_saved = TRUE;

                $form = arr::overwrite($form, $post_validator->as_array());
            }

            else
            {
                $form = arr::overwrite($form, $post_validator->as_array());

                $errors = arr::overwrite($errors, $post_validator->errors('twb'));
                $form_error = TRUE;
            }
        }
        else
        {
            $settings = ORM::factory('twb_settings', 1);

            $form = array
            (
                'api_key' => ($settings->api_key == null ? '' : $settings->api_key)
            );
        }

        // Settings form is our view, found in views/twb/admin/twb_setting.php
        $this->template->content->settings_form->form = $form;

        $this->template->content->errors = $errors;
        $this->template->content->form_error = $form_error;
        $this->template->content->form_saved = $form_saved;
    }


}